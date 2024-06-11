<?php

namespace Tests\Feature;

use App\Contracts\FavoriteRepositoryInterface;
use App\Http\Controllers\FavoriteController;
use App\Models\Content;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Mockery;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class FavoriteControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     *
     * @return void
     */
    public function test_it_stores_favorite_content()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $content = Content::factory()->create();

        $mockRepository = $this->mock(FavoriteRepositoryInterface::class);
        $mockRepository->shouldReceive('addFavorite')->once()->andReturnNull();
        $this->app->instance(FavoriteRepositoryInterface::class, $mockRepository);

        $controller = new FavoriteController($this->app->make(FavoriteRepositoryInterface::class));

        $response = $controller->store($content->id);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(ResponseAlias::HTTP_CREATED, $response->getStatusCode());
    }

    /**
     *
     * @return void
     */
    public function test_it_shows_favorite_contents()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $favoriteData = [
            [
                'id' => 1,
                'user_id' => 1,
                'content_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 2,
                'user_id' => 1,
                'content_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $favorites = new Collection();

        foreach ($favoriteData as $data) {
            $favorite = new Favorite($data);
            $favorites->push($favorite);
        }

        $mockRepository = $this->mock(FavoriteRepositoryInterface::class);
        $mockRepository->shouldReceive('index')->once()->andReturn($favorites);
        $this->app->instance(FavoriteRepositoryInterface::class, $mockRepository);

        $controller = new FavoriteController($this->app->make(FavoriteRepositoryInterface::class));

        $response = $controller->index();
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(ResponseAlias::HTTP_OK, $response->getStatusCode());
        $this->assertJson($response->getContent());
    }
}
