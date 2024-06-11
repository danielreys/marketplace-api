<?php

namespace App\Http\Controllers;

use App\Contracts\RatingRepositoryInterface;
use App\Http\Requests\RatingRequest;
use App\ValueObjects\ValidatedRating;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class RatingController
{
    protected RatingRepositoryInterface $ratingRepository;

    public function __construct(RatingRepositoryInterface $ratingRepository){
        $this->ratingRepository = $ratingRepository;
    }


    /**
     * @param RatingRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function store(RatingRequest $request, int $id): JsonResponse
    {
        $storeRatingValidated = $request->validated();

        $validatedContent = new ValidatedRating($storeRatingValidated);

        $rating = $this->ratingRepository->addRating($validatedContent, $id);

        return response()->json($rating, ResponseAlias::HTTP_CREATED);
    }
}
