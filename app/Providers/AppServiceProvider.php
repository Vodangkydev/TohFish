<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        // Tạo helper function để xử lý asset URLs với base path
        if (!function_exists('asset_with_base')) {
            function asset_with_base($path) {
                $basePath = request()->getBasePath();
                $path = ltrim($path, '/');
                return $basePath . '/' . $path;
            }
        }
    }
}
