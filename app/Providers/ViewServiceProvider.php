<?php

namespace App\Providers;

use App\Models\SiteSetting;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;

class ViewServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
           View::composer('*', function ($view) {
            $settings = null;

            try {
                if (Schema::hasTable('site_settings')) {
                    $settings = SiteSetting::query()->first();
                }
            } catch (\Throwable $e) {
                $settings = null;
            }

            $view->with('siteSettings', $settings);
        });
    }
}