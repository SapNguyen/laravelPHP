<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
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
    protected $model = \App\Models\Product::class;
    public function definition()
    {
        return [
            'product_name' => $this->faker->title,
            'product_image' => $this->faker->image,
            'product_color' => $this->faker->title,
            'product_material' => $this->faker->title,
            'product_des' => $this->faker->paragraph,
            'product_price' => $this->faker->numerify,
        ];
    }
}
