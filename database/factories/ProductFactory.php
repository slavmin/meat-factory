<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['Мясо', 'Птица', 'Колбаса', 'Полуфабрикат', 'Субпродукт'];

        return [
            'name' => $this->faker->unique()->words(2, true),
            'description' => $this->faker->sentence(10),
            'price' => $this->faker->numberBetween(100, 2000),
            'category' => $this->faker->randomElement($categories),
            'stock' => $this->faker->numberBetween(0, 500),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }

    public function meat(): self|Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'category' => 'Мясо',
                'name' => $this->faker->randomElement(['Говядина', 'Свинина', 'Баранина', 'Телятина', 'Крольчатина']),
            ];
        });
    }

    public function poultry(): self|Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'category' => 'Птица',
                'name' => $this->faker->randomElement(['Курица', 'Индейка', 'Утка', 'Гусь', 'Фазан']),
            ];
        });
    }
}
