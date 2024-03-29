<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EntrustCustomServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        \Blade::directive('permission', function ($expression) {
            return "<?php if (Auth::guard('admin')->user()->can({$expression})) : ?>";
        });

        \Blade::directive('endpermission', function ($expression) {
            return "<?php endif; ?>";
        });

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
