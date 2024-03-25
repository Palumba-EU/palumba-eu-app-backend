<?php

use App\Http\Controllers\LocalizationController;
use App\Http\Controllers\ResultsController;
use App\Http\Controllers\SponsorController;
use App\Http\Controllers\StatementController;
use Illuminate\Support\Facades\Route;

Route::get('/localization', [LocalizationController::class, 'index']);
Route::get('/statements', [StatementController::class, 'index']);
Route::get('/results', [ResultsController::class, 'index']);
Route::get('/sponsors', [SponsorController::class, 'index']);
