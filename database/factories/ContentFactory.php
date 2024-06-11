<?php
namespace Database\Factories;

use App\Models\Content;
use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\User;

// Import the User model

/**
 * @extends Factory<Content>
 */
class ContentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => function () {
                return User::all()->random()->first()->id; // Retrieve a random user ID from the users table
            },
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            // You may adjust the following line according to your needs
        ];
    }
}
