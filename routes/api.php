<?php

use App\Http\Controllers\NoteController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;

Route::get('/notes', [NoteController::class, 'index']);

Route::get('/notes/{id}', [NoteController::class, 'show']);

Route::post('/notes', [NoteController::class, 'store']);

Route::put('/notes/{id}', [NoteController::class, 'update']);

Route::delete('/notes/{id}', [NoteController::class, 'destroy']);

Route::get('/categories', [CategoryController::class, 'index']);

Route::get('/categories/{id}', [CategoryController::class, 'show']);

Route::post('/categories', [CategoryController::class, 'store']);

Route::put('/categories/{id}', [CategoryController::class, 'update']);

Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

Route::get('/categories/{id}/notes', [NoteController::class, 'notesByCategory']);
