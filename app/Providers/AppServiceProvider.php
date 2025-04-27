<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

use App\Observers\GlobalModelObserver;
use App\Common\Model\BaseModel;


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
        BaseModel::observe(GlobalModelObserver::class);
            // Fix: Force PostgreSQL to use "public" schema after connecting
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('SET search_path TO public');
        }
    }
}
