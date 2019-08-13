<?php

namespace Yassir3wad\NovaAuditing;

use Laravel\Nova\Nova;
use Laravel\Nova\Events\ServingNova;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Yassir3wad\NovaAuditing\Resources\Audit;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->booted(function () {
            $this->routes();
        });

        $this->publishes([
            __DIR__.'/../config/novaaudit.php' => config_path('novaaudit.php'),
        ]);

        Nova::serving(function (ServingNova $event) {
            Nova::script('nova-auditing', __DIR__.'/../dist/js/tool.js');
            Nova::style('nova-auditing', __DIR__.'/../dist/css/tool.css');
        });

        Nova::resources([
            Audit::class
        ]);
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        Route::middleware(['nova'])
                ->prefix('nova-vendor/nova-auditing')
                ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
