<?php

namespace Database\Factories\Books;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Books\Books>
 */
class BooksFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'author' => $this->faker->name(),
            'number_book' => 'BK-' . $this->faker->unique()->numerify('###'),
            'publisher' => $this->faker->company(),
            'cover' => $this->faker->imageUrl(400, 600, 'books', true),
            'publication_year' => $this->faker->numberBetween(2010, 2025),
            'category_id' => 1,
            'stock' => $this->faker->numberBetween(1, 20),
        ];
    }
}
