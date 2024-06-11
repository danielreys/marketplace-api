<?php

namespace Tests\Unit\Repository;

use App\Models\Content;
use App\Models\Favorite;
use App\Models\User;
use App\Repositories\FavoriteRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;


class FavoriteRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected FavoriteRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new FavoriteRepository();
    }

    /** @test */
    public function test_adds_favorite()
    {
        $user = User::factory()->create();
        $content = Content::factory()->create();

        Auth::shouldReceive('id')->twice()->andReturn($user->getId());

        $this->repository->addFavorite($content);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->getId(),
            'content_id' => $content->getId(),
        ]);

        $this->assertTrue($content->favorites()->where('user_id', $user->getId())->exists());
    }

    /** @test */
    public function test_indexes_favorites()
    {
        $user = User::factory()->create();
        $user2 = User::factory()->create();
        $content1 = Content::factory()->create();
        $content2 = Content::factory()->create();

        Favorite::create([
            'user_id' => $user->getId(),
            'content_id' => $content1->getId(),
        ]);

        Favorite::create([
            'user_id' => $user->getId(),
            'content_id' => $content2->getId(),
        ]);

        Favorite::create([
            'user_id' => $user2->getId(),
            'content_id' => $content1->getId(),
        ]);

        // Mock authenticated user
        Auth::shouldReceive('id')->once()->andReturn($user->getId());

        // Act
        $favorites = $this->repository->index();

        // Assert
        $this->assertInstanceOf(Collection::class, $favorites);
        $this->assertCount(2, $favorites);
        $this->assertTrue($favorites->contains('content_id', $content1->getId()));
        $this->assertTrue($favorites->contains('content_id', $content2->getId()));
    }

    /** @test */
    public function test_prevents_duplicate_favorites()
    {
        $user = User::factory()->create();
        $content = Content::factory()->create();

        Auth::shouldReceive('id')->atLeast()->andReturn($user->getId());

        $this->repository->addFavorite($content);

        $this->expectException(\InvalidArgumentException::class);
        $this->repository->addFavorite($content);
    }

}
