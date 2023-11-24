<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name"=>fake()->name(),
            'parent_id'=>null,
            "calories"=>fake()->numberBetween(1, 1000),
            "proteins"=>fake()->numberBetween(1, 100),
            "fats"=>fake()->numberBetween(1, 100),
            "carbohydrates"=>fake()->numberBetween(1, 100),
            "description"=>fake()->text(50)
        ];
    }


    public function isModerated(){
        return $this->state(function (array $attributes){
            return [
              "isModerated"=>true
            ];
        });
    }
}
