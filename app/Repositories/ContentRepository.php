<?php
namespace App\Repositories;

use App\Contracts\ContentRepositoryInterface;
use App\Models\Content;
use App\ValueObjects\ValidatedContent;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContentRepository implements ContentRepositoryInterface {

    /**
     * @param ValidatedContent $validatedContent
     * @return Content
     */
    public function createContent(ValidatedContent $validatedContent): Content
    {
        $user_id = auth()->id();

        $content = new Content();
        $content->setTitle($validatedContent->getTitle());
        $content->setDescription($validatedContent->getDescription());
        $content->setUserId($user_id);

        if ($validatedContent->getMedia()) {
            $path = $validatedContent->getMedia()->store('media', config('filesystems.default'));
            $content->setFilePath($path);
        }

        $content->save();

        return $content;
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function getContent(Request $request): Collection
    {
        $query = Content::query();

        if ($request->has('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        if ($request->has('title')) {
            $title = $request->input('title');
            $query->where('title', 'like', "%$title%");
        }

        return $query->get();
    }

    /**
     * @param Content $content
     * @param ValidatedContent $validatedContent
     * @return Content
     */
    public function updateContent(Content $content, ValidatedContent $validatedContent): Content
    {
        if ($validatedContent->getTitle()) {
            $content->setTitle($validatedContent->getTitle());
        }
        if ($validatedContent->getDescription()) {
            $content->setDescription($validatedContent->getDescription());
        }
        if ($validatedContent->getMedia()) {
            if ($content->getFilePath()) {
                Storage::delete($content->getFilePath());
            }
            $content->setFilePath($validatedContent->getMedia()->store('media', config('filesystems.default')));
        }

        $content->save();

        return $content;
    }

    /**
     * @param int $id
     * @return Content
     */
    public function getContentById(int $id): Content
    {
        return Content::findOrFail($id);
    }

    /**
     * @param int $id
     * @var Content $content
     * @return void
     */
    public function deleteContentById(int $id): void
    {
        $content = Content::findOrFail($id);

        if ($content->getFilePath()) {
            Storage::delete($content->getFilePath());
        }
        $content->delete();
    }
}
