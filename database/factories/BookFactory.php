<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->title,
            'author' => $this->faker->name,
            'summary' => $this->faker->realText,
            'image' => $this->faker->imageUrl,
            'create_time' => now(),
            'update_time' => now(),
        ];
    }
}
