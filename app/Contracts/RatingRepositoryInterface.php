<?php
namespace App\Contracts;

use App\Models\Rating;
use App\ValueObjects\ValidatedRating;

interface RatingRepositoryInterface {
    public function addRating (ValidatedRating $validatedRating, int $id): Rating;
}
