<?php
namespace App\Repositories;
use App\Contracts\RatingRepositoryInterface;
use App\Models\Content;
use App\Models\Rating;
use App\ValueObjects\ValidatedRating;


class RatingRepository implements RatingRepositoryInterface {

    /**
     * @param ValidatedRating $validatedRating
     * @param int $id
     * @var Content $content
     * @return Rating
     */
    public function addRating(ValidatedRating $validatedRating, int $id): Rating
    {
        $content = Content::findOrFail($id);

        return Rating::create([
            'rating' => $validatedRating->getRating(),
            'content_id' => $content->getId(),
            'user_id' =>  auth()->id(),
        ]);
    }
}
