# Recipe Parser

Take the cruft out of recipes, and get just the important part.

## What to Check Out

The interesting code is here:

1. Controller to retrieve website HTML and find metadata: [`app/Http/Controllers/RecipeController.php`](https://github.com/fideloper/recipeplz/blob/main/app/Http/Controllers/RecipeController.php)
2. Recipe Parser to find Recipe-specific metadata and parse it: [`app/RecipeParser.php`](https://github.com/fideloper/recipeplz/blob/main/app/RecipeParser.php)
3. A [`Recipe`](https://github.com/fideloper/recipeplz/blob/main/app/Recipe.php) object
4. The class doing the AI stuff: [`app/AIRecipeReader.php`](https://github.com/fideloper/recipeplz/blob/main/app/AIRecipeReader.php)
