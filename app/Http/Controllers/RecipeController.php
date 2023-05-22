<?php

namespace App\Http\Controllers;

use App\RecipeParser;
use Brick\StructuredData\HTMLReader;
use Brick\StructuredData\Reader\JsonLdReader;
use Brick\StructuredData\Reader\RdfaLiteReader;
use Brick\StructuredData\Reader\MicrodataReader;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class RecipeController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $recipe = null;
        $validation = null;

        if ($request->recipe) {
            $jsonLDReader = new JsonLdReader();
            $microReader = new MicrodataReader();
            $rdfaReader = new RdfaLiteReader();
            $htmlJsonReader = new HTMLReader($jsonLDReader);
            $htmlMicroReader = new HTMLReader($microReader);
            $htmlRdfaReader = new HTMLReader($rdfaReader);

            $parsers = [
                new JsonLdReader,
                new MicrodataReader,
                new RdfaLiteReader,
            ];

            $response = Http::throw()->get(request('recipe'));

            // The XML HTML readers don't handle UTF-8 for you
            $html = mb_convert_encoding($response->body(), 'HTML-ENTITIES', "UTF-8");

            foreach($parsers as $parser) {
                $reader = new HTMLReader($parser);
                $items = $htmlJsonReader->read($html, $request->recipe);

                if($recipe = RecipeParser::fromItems($items,$request->recipe)) {
                    break;
                }
            }

            if (! $recipe) {
                $validation = 'Unfortunately, we could not parse that recipe!';
            }
        }

        return view('form', [
            'recipe' => $recipe,
            'url' => $request->recipe,
            'validation' => $validation,
        ]);
    }
}
