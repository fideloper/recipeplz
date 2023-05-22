<?php

namespace App;


use Carbon\CarbonInterval;
use Illuminate\Support\Str;

class Recipe
{
    public function __construct(
        public string $title,
        public string $url,
        public string $author,
        public array $ingredients,
        public array $steps,
        public $yield = 0,
        public $totalTime = 0,
        public $images = [],
    ) {}

    public function ingredientRows()
    {
        // 3 rows total, get number of ingredients per row
        $perRow = (int)ceil(count($this->ingredients) / 3);
        $ingredients = [0 => [], 1 => [], 2 => []];

        foreach($ingredients as $column => $rows) {
            $ingredients[$column] = array_slice($this->ingredients, $perRow * $column, $perRow);
        }

        return $ingredients;
    }

    public function humanTotalTime()
    {
        if ($this->totalTime) {
            try {
                // Fix ld+json from foodnetwork.com
                $interval = Str::replace("DT", "D", $this->totalTime);
                $duration = CarbonInterval::fromString($interval);
                return $duration->forHumans();
            } catch(\Exception $e){
                return null;
            }
        }

        return null;
    }
}