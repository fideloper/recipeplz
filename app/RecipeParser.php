<?php

namespace App;


use App\Recipe;
use Brick\StructuredData\Item;
use Illuminate\Support\Str;

class RecipeParser
{
    public static function fromItems($items, $url)
    {
        foreach($items as $item) {
            if(Str::contains(Str::lower(implode(',', $item->getTypes())), 'recipe')) {
                return (new static(url: $url))->parse($item);
            }
        }

        // The whole thing might be a recipe
        if (count($items) == 1) {
            return (new static(url: $url))->parse($item);
        }

        throw new RecipeNotFoundException('No recipe found for '.$url);
    }

    public function __construct(
        protected $title = '',
        protected $url = '',
        protected $author = '',
        protected $ingredients = [],
        protected $steps = [],
        protected $yield = '',
        protected $totalTime = '',
        protected $images = []
    )
    {}

    public function parse(Item $item): Recipe
    {
        foreach($item->getProperties() as $name => $values) {
            $fn = "parse_".Str::replace(['http://schema.org/', 'https://schema.org/'], '', Str::lower($name));
            if(method_exists($this, $fn)) {
                $this->$fn($values);
            }
        }

        return new Recipe($this->title, $this->url, $this->author, $this->ingredients, $this->steps, $this->yield, $this->totalTime, $this->images);
    }

    protected function parse_name($values)
    {
        $this->title = (is_array($values) ? $values[0] : $values);
    }

    function parse_recipeyield($values)
    {
        $this->yield = (is_array($values) ? $values[0] : $values);
    }

    function parse_totaltime($values)
    {
        $this->totalTime = (is_array($values) ? $values[0] : $values);
    }

    function parse_image($values)
    {
        foreach($values as $item) {
            if ($item instanceof Item) {
                foreach($item->getProperties() as $name => $values) {
                    $name = Str::replace('http://schema.org/', '', Str::lower($name));
                    // $name may be one of [url, height, thumbnail, width]
                    if ($name == "url") {
                        // If it's relative
                        if (Str::contains($values[0], ["http://", "https://"])) {
                            $this->images[] = $values[0];
                        }
                    }
                }
            } else {
                if (is_array($item)) {
                    throw new \Exception("Handle image items are array of strings");
                } else {
                    if (Str::contains($item, ["http://", "https://"])) {
                        $this->images[] = $item;
                    }
                }
            }
        }
    }

    function parse_recipeingredient($values)
    {
        if (is_array($values)) {
            $this->ingredients = array_merge(collect($values)->transform(function ($item) {
                return html_entity_decode($item);
            })->toArray());
        }
    }

    function parse_recipeinstructions($values)
    {
        foreach($values as $item) {
            if ($item instanceof Item){
                if(Str::contains(Str::lower(implode(',', $item->getTypes())), 'howtostep')) {
                    foreach($item->getProperties() as $name => $values) {
                        $name = Str::replace(['http://schema.org/', 'https://schema.org/'], '', Str::lower($name));
                        if ($name == "text") {
                            $this->steps[] = html_entity_decode($values[0]);
                        }
                    }
                }
            } else{
                $this->steps[] = html_entity_decode($item);
            }
        }
    }
}