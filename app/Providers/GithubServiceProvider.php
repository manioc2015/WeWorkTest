<?php

namespace App\Providers;

use App\Repositories\GithubRepository;
use Illuminate\Support\ServiceProvider;

class GithubServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(GithubRepository::class, function ($app) {
            return new GithubRepository();
        });
    }
}
