<?php
namespace App\Http\Controllers;

use App\Contracts\ContentRepositoryInterface;
use App\Http\Requests\StoreContentRequest;
use App\Http\Requests\UpdateContentRequest;
use App\Models\Content;
use App\ValueObjects\ValidatedContent;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ContentController
{
    protected ContentRepositoryInterface $contentRepository;

    public function __construct(ContentRepositoryInterface $contentRepository)
    {
        $this->contentRepository = $contentRepository;
    }

    public function store(StoreContentRequest $request): JsonResponse
    {
        $storeRequestContentValidated = $request->validated();

        $validatedContent = new ValidatedContent($storeRequestContentValidated);

        $content = $this->contentRepository->createContent($validatedContent);

        return response()->json($content, ResponseAlias::HTTP_CREATED);
    }

    public function indexList(Request $request): JsonResponse
    {
        $collection = $this->contentRepository->getContent($request);

        if ($collection->isEmpty()) {
            return response()->json([ 'message' => 'No data found'], ResponseAlias::HTTP_NO_CONTENT);
        }

        return response()->json($collection, ResponseAlias::HTTP_OK);
    }

    public function show($id): JsonResponse
    {
        try {
            $content = $this->contentRepository->getContentById($id);
            return response()->json($content);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Content not found'], ResponseAlias::HTTP_NOT_FOUND);
        }
    }

    public function update(UpdateContentRequest $request, int $id): JsonResponse
    {
        try {

            $content = Content::findOrFail($id);
            $updatedContentValidated = $request->validated();

            $validatedContent = new ValidatedContent($updatedContentValidated);
            $updatedContent = $this->contentRepository->updateContent($content, $validatedContent);

            return response()->json($updatedContent, ResponseAlias::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Content not found'], ResponseAlias::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the content'], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function destroy($id): Response
    {
        $this->contentRepository->deleteContentById($id);
        return response()->noContent();
    }
}
