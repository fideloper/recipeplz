<?php

namespace App;

use OpenAI;
use DOMDocument;
use App\Recipe;
use Brick\StructuredData\Reader\JsonLdReader;
use Illuminate\Support\Str;

class AIRecipeReader
{
    public static function read($url)
    {
        $prompt = "Extract only the following information from the recipe found here: $url

            - dishName
            - publishDate (in YYYY-MM-DD format)
            - total cook time (in human-readable format)
            - author
            - ingredients 
            - steps (array of strings)
            - servings
            
            Please generate the output as valid JSON, preferably in ld+json format based on schema.org specificiation.";
        
        $client = OpenAI::client(config('ai.open_ai_key'));

        $result = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'user', 'content' => $prompt]
            ],
        ]);

        $dom = new DOMDocument;
        $html = mb_convert_encoding('<script type="application/ld+json">'.$result->choices[0]->message->content.'</script>', 'HTML-ENTITIES', "UTF-8");
        $dom->loadHTML($html);
        $items = (new JsonLdReader)->read($dom, $url);
        return RecipeParser::fromItems($items, $url);
    }
}