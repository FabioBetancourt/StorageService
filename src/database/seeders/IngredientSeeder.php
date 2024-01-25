<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ingredients = [
            [
                'name' => 'tomato',
                'quantity' => 5
            ],
            [
                'name' => 'lemon',
                'quantity' => 5
            ],
            [
                'name' => 'potato',
                'quantity' => 5
            ],
            [
                'name' => 'rice',
                'quantity' => 5
            ],
            [
                'name' => 'ketchup',
                'quantity' => 5
            ],
            [
                'name' => 'lettuce',
                'quantity' => 5
            ],
            [
                'name' => 'onion',
                'quantity' => 5
            ],
            [
                'name' => 'cheese',
                'quantity' => 5
            ],
            [
                'name' => 'meat',
                'quantity' => 5
            ],
            [
                'name' => 'chicken',
                'quantity' => 5
            ],
        ];

        foreach ($ingredients as $ingredient) {
            Ingredient::create($ingredient);
        }
    }
}
