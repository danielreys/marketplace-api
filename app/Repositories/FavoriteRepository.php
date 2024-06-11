<?php
namespace App\Repositories;
use App\Contracts\FavoriteRepositoryInterface;
use App\Models\Content;
use App\Models\Favorite;
use Illuminate\Database\Eloquent\Collection;


class FavoriteRepository implements FavoriteRepositoryInterface {
    /**
     * @param Content $content
     */
    public function addFavorite(Content $content): void
    {
        if ($content->favorites()->where('user_id', auth()->id())->exists()) {
            throw new \InvalidArgumentException('Content is already favorited');
        }
        $content->favorites()->attach(auth()->id());
    }


    public function index(): Collection
    {
        $query = Favorite::query();
        $query->where('user_id', auth()->id());
        return $query->get();
    }
}
