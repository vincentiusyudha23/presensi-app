<?php

namespace App\Providers;

use App\Models\Petugas;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        Schema::defaultStringLength(191);
        config(['app.locale' => 'id']);
        date_default_timezone_set('Asia/Jakarta');
        Petugas::observe(\App\Observers\PetugasObserver::class);
    }
}
