<?php

namespace Tests\Feature;

use App\Contracts\RatingRepositoryInterface;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Tests\TestCase;

class RatingControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_stores_rating()
    {
        $user =  User::factory()->create();
        $this->actingAs($user);

        $mockRepository = $this->mock(RatingRepositoryInterface::class);
        $mockRepository->shouldReceive('addRating')->once()->andReturn(new Rating(['rating' => 3]));
        $this->app->instance(RatingRepositoryInterface::class, $mockRepository);

        $contentId = 2;
        $response = $this->postJson("api/content/{$contentId}/rate", ['rating' => 1]);

        $response->assertStatus(ResponseAlias::HTTP_CREATED);
    }
}
