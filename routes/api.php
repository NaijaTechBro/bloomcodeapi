<?php

use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\PodcastController;
use App\Http\Controllers\API\EpisodeController;
use Illuminate\Support\Facades\Route;

// Categories
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{category}', [CategoryController::class, 'show']);
Route::get('/categories/{category}/podcasts', [CategoryController::class, 'podcasts']);

// Podcasts
Route::get('/podcasts', [PodcastController::class, 'index']);
Route::get('/podcasts/featured', [PodcastController::class, 'featured']);
Route::get('/podcasts/{podcast}', [PodcastController::class, 'show']);
Route::get('/podcasts/{podcast}/episodes', [PodcastController::class, 'episodes']);
Route::post('/podcasts', [PodcastController::class, 'store']);
Route::put('/podcasts/{podcast}', [PodcastController::class, 'update']);
Route::delete('/podcasts/{podcast}', [PodcastController::class, 'destroy']);

// Episodes
Route::get('/episodes', [EpisodeController::class, 'index']);
Route::get('/episodes/{episode}', [EpisodeController::class, 'show']);
Route::post('/podcasts/{podcast}/episodes', [EpisodeController::class, 'store']);
Route::put('/episodes/{episode}', [EpisodeController::class, 'update']);
Route::delete('/episodes/{episode}', [EpisodeController::class, 'destroy']);