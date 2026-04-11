<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Library;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $totalCopies = $this->faker->numberBetween(1, 10);

        return [
            // This creates a Library automatically if one doesn't exist
            'library_id' => Library::factory(),
            'category_id' => Category::factory(),

            'title' => $this->faker->sentence(3),
            'author' => $this->faker->name,
            'isbn' => $this->faker->unique()->isbn13(),
            'published_year' => $this->faker->year,
            'total_copies' => $totalCopies,
            // Logic: available_copies starts equal to total_copies
            'available_copies' => $totalCopies,
            'is_active' => true,
        ];

    }
}
