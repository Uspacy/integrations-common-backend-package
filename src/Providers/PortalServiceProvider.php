<?php

namespace Uspacy\IntegrationsBackendPackage\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class PortalServiceProvider
{
    public function boot()
    {
        // Let's defer our route loading to the end of the boot cycle
        $this->app->booted(function () {
            $this->registerRoutes();
        });
    }

    protected function registerRoutes()
    {
        $apiCode = config('api.code');
        $apiVersion = config('api.version');

        Route::prefix("{$apiCode}/{$apiVersion}")
            ->group(__DIR__.'/../routes/v1/api.php');
    }
}
