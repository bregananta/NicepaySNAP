<?php

namespace Bregananta\Nicepay;

use Illuminate\Support\ServiceProvider;

class NicepaySnapBaseServiceProvider extends ServiceProvider
{

    /**
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/nicepaysnap.php', 'nicepaysnap-config');

        if ($this->app->runningInConsole()) {

            $this->publishes([
                __DIR__.'/../config/nicepaysnap.php' => config_path('nicepaysnap.php'),
            ], 'nicepaysnap-config');
            
        }
    }

    /**
     * @return void
     */
    public function register()
    {
        $this->app->bind('NicepaySnap', function ($app) {
            return new \Bregananta\NicepaySnap\NicepaySnap();
        });
    }
}