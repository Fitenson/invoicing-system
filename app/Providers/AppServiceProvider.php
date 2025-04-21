<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
    }
}
