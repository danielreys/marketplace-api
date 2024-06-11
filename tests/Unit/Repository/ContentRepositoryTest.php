<?php
namespace Tests\Unit\Repository;

use App\Models\User;
use App\Models\Content;
use App\Repositories\ContentRepository;
use App\ValueObjects\ValidatedContent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected ContentRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new ContentRepository();
    }

    /** @test */
    public function test_creates_content()
    {
        $validatedContent = new ValidatedContent([
            'title' => 'Test Title',
            'description' => 'Test Description',
            'media' => null,
        ]);

        $user = User::factory()->create();
        Auth::shouldReceive('id')->once()->andReturn($user->id);

        $content = $this->repository->createContent($validatedContent);

        $this->assertInstanceOf(Content::class, $content);
        $this->assertEquals('Test Title', $content->getTitle());
        $this->assertEquals('Test Description', $content->getDescription());
        $this->assertEquals($user->id, $content->getUserId());
    }

    /** @test */
    public function test_gets_content()
    {
        $request = new Request([
            'user_id' => 1,
            'title' => 'Test Title',
        ]);

        $result = $this->repository->getContent($request);

        $this->assertInstanceOf(Collection::class, $result);
    }

    /** @test */
    public function test_updates_content()
    {
        $user = User::factory()->create();

        $content = Content::factory()->create([
            'user_id' => $user->id,
            'title' => 'Old Title',
            'description' => 'Old Description',
            'file_path' => null,
        ]);

        $validatedContent = new ValidatedContent([
            'title' => 'New Title',
            'description' => 'New Description',
            'media' => null,
        ]);

        $updatedContent = $this->repository->updateContent($content, $validatedContent);

        $this->assertInstanceOf(Content::class, $updatedContent);
        $this->assertEquals('New Title', $updatedContent->getTitle());
        $this->assertEquals('New Description', $updatedContent->getDescription());
    }

    /** @test */
    public function test_gets_content_by_id()
    {
        $user = User::factory()->create();
        $content = Content::factory()->create([
            'user_id' => $user->id,
        ]);

        $foundContent = $this->repository->getContentById($content->id);

        $this->assertInstanceOf(Content::class, $foundContent);
        $this->assertEquals($content->id, $foundContent->getId());
    }

    /** @test */
    public function test_deletes_content_by_id()
    {
        $user = User::factory()->create();
        $content = Content::factory()->create([
            'user_id' => $user->id,
            'file_path' => 'path/img.jpg',
        ]);

        Storage::shouldReceive('delete')->once()->with('path/img.jpg');

        $this->repository->deleteContentById($content->getId());

        $this->assertDatabaseMissing('contents', ['id' => $content->getId()]);
    }

    /** @test */
    public function test_gets_content_by_invalid_id()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->getContentById(999);
    }

    /** @test */
    public function test_deletes_content_by_invalid_id()
    {
        $this->expectException(ModelNotFoundException::class);

        $this->repository->deleteContentById(999);
    }
}
