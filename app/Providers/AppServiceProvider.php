<?php

namespace App\Providers;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
     // Log queries
        // if (true) {
        //     DB::listen(function ($query) {
        //         Log::info(
        //             $query->sql, $query->bindings, $query->time
        //         );
        //     });
        // }
        if(config('app.env') === 'production') {
            \URL::forceScheme('https');
        }

    }
}
