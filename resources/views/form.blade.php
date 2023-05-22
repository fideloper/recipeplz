<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Recipe Cardinationizermabobber</title>

    <!-- Literally the best part of throwing together a project is just including all of Tailwind in -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="antialiased bg-gray-100">
    <div class="container max-w-3xl m-auto mt-32 bg-white rounded p-6 shadow">
        <div>
            <form method="get" action="/">
                <div class="grid grid-cols-1 gap-x-8 gap-y-10 border-b border-gray-900/10 pb-12 md:grid-cols-3">
                    <div>
                        <h2 class="text-base font-semibold leading-7 text-gray-900">Recipe</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-600">Clean out the recipe cruft.</p>
                    </div>

                    <div class="grid max-w-2xl grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 md:col-span-2">
                        <div class="sm:col-span-4">
                            <label for="recipe" class="block text-sm font-medium leading-6 text-gray-900">Website</label>
                            <div class="mt-2">
                                <div class="flex rounded-md shadow-sm ring-1 ring-inset ring-gray-300 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600 sm:max-w-md">
                                    <input
                                        type="text"
                                        name="recipe"
                                        id="recipe"
                                        class="block flex-1 border-0 bg-transparent py-1.5 px-2 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                                        placeholder="https://www.recipe.com/so-much-gluten"
                                        value="{{ $url }}"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                @if($validation)
                    <div class="text-red-500 py-4">
                        <p>{{ $validation }}</p>
                    </div>
                @endif

                <div class="mt-6 flex items-center justify-end gap-x-6">
                    <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Try it Out</button>
                </div>
            </form>
        </div>
    </div>


    @if($recipe)
    <div id="the-recipe" class="container m-auto my-32 rounded shadow max-w-5xl bg-white overflow-hidden text-gray-800">
        <div class="py-6 px-8">
            <h2 class="pb-3 text-4xl tracking-wider font-light uppercase">{{ $recipe->title }}</h2>
            <div class="flex text-sm font-light uppercase tracking-widest pb-2">
                @if($recipe->yield)
                <div>Yield: {{ $recipe->yield }}</div>
                <div class="px-2">|</div>
                @endif
                @if($recipe->totalTime)
                <div>Total Time: {{ $recipe->humanTotalTime() }}</div>
                @endif
            </div>
            <div class="border-b border-gray-600"></div>
        </div>
        <div>
            <div class="pb-6 px-8">
                <h3 class="tracking-wider font-medium uppercase">Ingredients</h3>
                <div class="flex pt-3 pb-6">
                    @foreach($recipe->ingredientRows() as $row => $ingredients)
                    <div class="text-md @if($row < 2) pr-4 @endif">
                        @foreach($ingredients as $ingredient)
                            <div class="pb-2 text-sm flex items-center tracking-wide">
                                <svg class="mr-2" style="min-width: 16px; max-width: 16px;" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <g id="ic_fluent_checkbox_unchecked_24_regular" fill="#212121" fill-rule="nonzero">
                                            <path d="M5.75,3 L18.25,3 C19.7687831,3 21,4.23121694 21,5.75 L21,18.25 C21,19.7687831 19.7687831,21 18.25,21 L5.75,21 C4.23121694,21 3,19.7687831 3,18.25 L3,5.75 C3,4.23121694 4.23121694,3 5.75,3 Z M5.75,4.5 C5.05964406,4.5 4.5,5.05964406 4.5,5.75 L4.5,18.25 C4.5,18.9403559 5.05964406,19.5 5.75,19.5 L18.25,19.5 C18.9403559,19.5 19.5,18.9403559 19.5,18.25 L19.5,5.75 C19.5,5.05964406 18.9403559,4.5 18.25,4.5 L5.75,4.5 Z">
                                            </path>
                                        </g>
                                    </g>
                                </svg>
                                {{ $ingredient }}
                            </div>
                        @endforeach
                    </div>
                    @endforeach
                </div>
                <div class="border-b border-gray-600"></div>
            </div>
            <div class="pb-6 px-8">
                <h3 class="tracking-wider font-medium uppercase">Preparation</h3>
                <div class="pt-3">
                    <ol class="list-decimal pl-4 text-sm">
                        @foreach($recipe->steps as $step)
                            <li class="pb-2 tracking-wide">{{ $step }}</li>
                        @endforeach
                    </ol>
                </div>
            </div>
        </div>
    </div>
    @endif
</body>
</html>