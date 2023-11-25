<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CookProcess>
 */
class CookProcessFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $product = Product::factory()->create();
        return [
            "name"=>fake()->text(10),
            "duration"=>fake()->numberBetween(1,10),
            "cookPresenceInterval"=>fake()->numberBetween(0,5),
            "description"=>fake()->text(50),
            "product_id"=>$product->id
        ];
    }
    public function isModerated()
    {
        return $this->state(function (array $attributes){
            return [
                "isModerated"=>true
            ];
        });
    }
}
