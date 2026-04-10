<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Observers\UserObserver;
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
        $settings = null;

        try {
            if (Schema::hasTable('site_settings')) {
                $settings = SiteSetting::query()->first();
            }
        } catch (\Throwable $e) {
            $settings = null;
        }

        View::share('settings', $settings);

        User::observe(UserObserver::class);
    }
}
