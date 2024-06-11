<?php
namespace Tests\Unit\Repository;

use App\Models\Content;
use App\Models\Rating;
use App\Models\User;
use App\Repositories\RatingRepository;
use App\ValueObjects\ValidatedRating;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class RatingRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected RatingRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new RatingRepository();
    }

    /** @test */
    public function test_adds_rating_to_content()
    {
        $user = User::factory()->create();
        $content = Content::factory()->create();

        Auth::shouldReceive('id')->once()->andReturn($user->getId());

        $validatedRating = new ValidatedRating(['rating' => 5]);
        $rating = $this->repository->addRating($validatedRating, $content->getId());

        $this->assertInstanceOf(Rating::class, $rating);
        $this->assertEquals(5, $rating->getRating());
        $this->assertEquals($user->id, $rating->getUserId());
        $this->assertEquals($content->id, $rating->getContentId());
    }
}
