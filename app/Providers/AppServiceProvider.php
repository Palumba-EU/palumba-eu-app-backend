<?php

namespace App\Providers;

use CrowdinApiClient\Crowdin;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

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
        if (! $this->app->environment('production')) {
            Model::shouldBeStrict();
        }

        $this->app->bind(Crowdin::class, function () {
            return new Crowdin([
                'access_token' => config('crowdin.token'),
            ]);
        });
    }
}
