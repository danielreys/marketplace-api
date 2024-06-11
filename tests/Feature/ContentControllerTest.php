<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_stores_content()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $requestData = [
            'title' => 'Test Title',
            'description' => 'Test Description'
        ];

        $response = $this->postJson('api/content', $requestData);

        $response->assertStatus(201);
    }

    /** @test */
    public function test_shows_all_content()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Content::factory()->count(5)->create();

        $response = $this->getJson('api/content');

        $response->assertStatus(200)
            ->assertJsonCount(5);
    }

    /** @test */
    public function test_shows_specific_content()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $content = Content::factory()->create();
        $response = $this->getJson("api/content/{$content->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $content->id,
                'title' => $content->title,
                'description' => $content->description,
            ]);
    }

    /** @test */
    public function test_updates_content()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $content = Content::factory()->create();

        $requestData = [
            'title' => 'Updated Title',
            'description' => 'Updated Description'
        ];

        $response = $this->putJson("api/content/{$content->id}", $requestData);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $content->id,
                'title' => 'Updated Title',
                'description' => 'Updated Description',
            ]);
    }

    /** @test */
    public function test_deletes_content()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $content = Content::factory()->create();

        $response = $this->deleteJson("api/content/{$content->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('contents', ['id' => $content->id]);
    }
}
