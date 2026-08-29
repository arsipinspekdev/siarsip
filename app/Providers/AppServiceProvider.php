<?php

namespace App\Providers;

use App\Models\SuratKeluar;
use App\Models\SuratMasuk;
use App\Observers\SuratKeluarObserver;
use App\Observers\SuratMasukObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register observers untuk auto-assign dan resequence no_agenda
        SuratMasuk::observe(SuratMasukObserver::class);
        SuratKeluar::observe(SuratKeluarObserver::class);
    }
}
