<?php

namespace App\Http\Controllers;

use App\Contracts\FavoriteRepositoryInterface;
use Illuminate\Http\JsonResponse;
use App\Models\Content;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class FavoriteController
{
    protected FavoriteRepositoryInterface $favoriteRepository;

    public function __construct(FavoriteRepositoryInterface $favoriteRepository) {
        $this->favoriteRepository = $favoriteRepository;
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function store(int $id): JsonResponse
    {
        try {
            $content = Content::findOrFail($id);
            $this->favoriteRepository->addFavorite($content);
            return response()->json(['message' => 'Content favorited successfully'], ResponseAlias::HTTP_CREATED);
        } catch (\InvalidArgumentException $exception) {
            return response()->json(['message' => $exception->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], ResponseAlias::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        try {
            $favorites = $this->favoriteRepository->index();
        } catch (\Exception $exception) {
            return response()->json(['message' => $exception->getMessage()], ResponseAlias::HTTP_BAD_REQUEST);
        }
        return response()->json(['favorites' => $favorites]);
    }
}
