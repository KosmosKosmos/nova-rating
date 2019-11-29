<?php namespace KosmosKosmos\Rating;

use Festiware\ApplicationsTool\Http\Middleware\Authorize;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;

class RateableServiceProvider extends ServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        Nova::serving(function () {
            Nova::script('rating', __DIR__.'/../dist/js/rating.js');
        });
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
        $this->app->booted(function () {
            $this->routes();
        });

        $this->publishes([
            __DIR__.'/../migrations/' => database_path('/migrations')
        ], 'migrations');
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

        Route::middleware(['nova', Authorize::class])
            ->prefix('nova-vendor/kosmoskosmos')
            ->group(__DIR__ . '/../routes/api.php');
    }


}
