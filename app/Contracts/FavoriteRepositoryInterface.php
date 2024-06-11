<?php
namespace App\Contracts;

use App\Models\Content;
use Illuminate\Database\Eloquent\Collection;

interface FavoriteRepositoryInterface {
    public function addFavorite(Content $content);
    public function index(): Collection;
}
