<?php

namespace Database\Seeders;

use App\Models\Favorite;
use App\Models\Rating;
use App\Models\User;
use App\Models\Content;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 10 random users
        $users = User::factory(10)->create();

        // Create a specific test user
        $testUser = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Add the test user to the users collection
        $users->push($testUser);

        // Create 5 pieces of content
        $contents = Content::factory(5)->create();

        // Assign some favorites to each user
        foreach ($users as $user) {
            // Each user favorites 2 random pieces of content
            $user->favorites()->syncWithoutDetaching(
                $contents->random(2)->pluck('id')->toArray()
            );

            foreach ($contents as $content) {
                Rating::factory()->create([
                    'user_id' => $user->id,
                    'content_id' => $content->id,
                    'rating' => rand(1, 5),
                ]);
            }
        }
    }
}
