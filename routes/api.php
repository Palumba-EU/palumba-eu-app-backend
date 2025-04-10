<?php

use App\Http\Controllers\ElectionController;
use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\StatementController;
use App\Http\Controllers\StatisticsController;
use App\Http\Middleware\DefaultElectionFallback;
use App\Http\Middleware\DefaultLanguageFallback;
use Illuminate\Support\Facades\Route;

Route::middleware(sprintf('cache.headers:public;max_age=%d;etag', config('cdn.maxAge')))->group(function () {
    Route::get('/statistics', [StatisticsController::class, 'index']);

    Route::prefix('/{language}')->middleware([DefaultElectionFallback::class])->group(function () {
        Route::get('/localization', [LocalizationController::class, 'index']);
        Route::get('/elections', [ElectionController::class, 'index']);
        Route::get('/sponsors', [SponsorController::class, 'index']);

        Route::prefix('/elections/{election}')->group(function () {
            Route::get('/localization', [LocalizationController::class, 'indexScoped']);
            Route::get('/statements', [StatementController::class, 'index']);
            Route::get('/results', [ResultsController::class, 'index']);
            Route::get('/sponsors', [SponsorController::class, 'indexScoped']);
        });

        // Push notifications
        Route::get('/notification-topics', [NotificationsController::class, 'index']);
        Route::put('/devices/{device}/subscriptions', [NotificationsController::class, 'updateSubscriptions']);

        // Deprecated - New routes are all scoped to election
        Route::get('/statements', [StatementController::class, 'index']);
        Route::get('/results', [ResultsController::class, 'index']);
    });

    // Deprecated - Remove once apps have switched to paths with language prefix
    Route::middleware([DefaultLanguageFallback::class, DefaultElectionFallback::class])->group(function () {
        Route::get('/localization', [LocalizationController::class, 'index']);
        Route::get('/statements', [StatementController::class, 'index']);
        Route::get('/results', [ResultsController::class, 'index']);
        Route::get('/sponsors', [SponsorController::class, 'index']);
    });
});

Route::middleware(['throttle:responses'])->group(function () {
    Route::post('/responses', [ResponseController::class, 'store']);
    Route::patch('/responses/{response}', [ResponseController::class, 'update']);
    Route::post('/responses/{response}/answers', [ResponseController::class, 'updateAnswers']);
});
