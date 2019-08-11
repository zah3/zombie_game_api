<?php

namespace App\Providers;

use App\Services\GameService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class GameServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('GameService', function () {
            return new GameService();
        });
    }
}
