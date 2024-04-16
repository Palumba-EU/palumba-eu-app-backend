<?php

use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\StatementController;
use Illuminate\Support\Facades\Route;

Route::middleware(sprintf('cache.headers:public;max_age=%d;etag', config('cdn.maxAge')))->group(function () {
    Route::get('/localization', [LocalizationController::class, 'index']);
    Route::get('/statements', [StatementController::class, 'index']);
    Route::get('/results', [ResultsController::class, 'index']);
    Route::get('/sponsors', [SponsorController::class, 'index']);
});

Route::post('/responses', [ResponseController::class, 'store'])->middleware(['throttle:responses']);
