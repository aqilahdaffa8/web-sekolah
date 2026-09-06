<?php

namespace Database\Factories;

use App\Models\Program;
use App\Models\TefaProduct;
use Illuminate\Database\Eloquent\Factories\Factory;

class TefaProductFactory extends Factory
{
    protected $model = TefaProduct::class;

    public function definition(): array
    {
        return [
            'product_name' => fake()->words(3, true),
            'description' => fake()->sentence(),
            'price' => fake()->numberBetween(5000, 500000),
            'stock' => fake()->numberBetween(1, 100),
            'image_url' => null,
            'program_id' => Program::factory(),
        ];
    }

    public function outOfStock(): static
    {
        return $this->state(['stock' => 0]);
    }

    public function inStock(int $qty = 10): static
    {
        return $this->state(['stock' => $qty]);
    }
}
