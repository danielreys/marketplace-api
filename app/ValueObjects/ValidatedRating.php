<?php
namespace App\ValueObjects;

use Illuminate\Http\Request;

final class ValidatedRating extends Request
{
    public int $rating;
    public function __construct(array $data)
    {
        $this->rating = $data['rating'] ?? null;
    }

    public function getRating(): int
    {
        return $this->rating;
    }
}
