<?php

namespace App\Providers;

use App\Repositories\CoordinateRepository;
use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class CoordinateRepositoryProvider extends ServiceProvider
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
        App::bind('CoordinateRepository',function(){
           return new CoordinateRepository;
        });
    }
}
