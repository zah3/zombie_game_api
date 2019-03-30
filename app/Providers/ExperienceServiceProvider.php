<?php

namespace App\Providers;

use App\Services\ExperienceService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class ExperienceServiceProvider extends ServiceProvider
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
        App::bind('ExperienceService', function () {
            return new ExperienceService;
        });
    }
}
