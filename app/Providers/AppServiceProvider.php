<?php

namespace App\Providers;

use App\Services\CrowdIn\TranslationRepository;
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

        $this->app->bind(TranslationRepository::class, function () {
            $language = request('language', request()->header('accept-language'));

            $matches = null;

            // try extracting the locale string from something like
            // sv-SE,en;q=0.9,de;q=0.8
            if (preg_match('/^[a-z]{2,3}(-[A-Z]{2})?/m', $language, $matches) === 1) {
                $language = $matches[0];
            }

            if (strlen(trim($language)) === 0) {
                $language = null;
            }

            return new TranslationRepository($language);
        });
    }
}
