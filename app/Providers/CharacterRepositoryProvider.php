<?php

namespace App\Providers;

use App\Repositories\CharacterRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class CharacterRepositoryProvider extends ServiceProvider
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
        App::bind('CharacterRepository', function () {
            return new CharacterRepository();
        });
    }
}
