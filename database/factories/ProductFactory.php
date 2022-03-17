<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'category_id' => Category::factory()->create()->get('id'),
            'title' => $this->faker->title(),
            'uuid' => $this->faker->uuid(),
            'price' => $this->faker->randomFloat(2, 0, 9999999999),
            'description' => $this->faker->text(),
        ];
    }
}
