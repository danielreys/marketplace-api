<?php

namespace App\Providers;

use App\Contracts\ContentRepositoryInterface;
use App\Contracts\FavoriteRepositoryInterface;
use App\Contracts\RatingRepositoryInterface;
use App\Repositories\ContentRepository;
use App\Repositories\FavoriteRepository;
use App\Repositories\RatingRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(ContentRepositoryInterface::class, ContentRepository::class);
        $this->app->bind(RatingRepositoryInterface::class, RatingRepository::class);
        $this->app->bind(FavoriteRepositoryInterface::class, FavoriteRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {

    }
}
