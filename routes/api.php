<?php

use App\Http\Controllers\NoteController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AuthController;


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


Route::patch('/notes/{id}/pin', [NoteController::class, 'pin']);
Route::patch('/notes/{id}/unpin', [NoteController::class, 'unpin']);
Route::patch('/notes/{id}/publish', [NoteController::class, 'publish']);
Route::patch('/notes/{id}/archive', [NoteController::class, 'archive']);


Route::get('/notes/stats', [NoteController::class, 'statsByStatus']);
Route::patch('/notes/archive-old', [NoteController::class, 'archiveOldDrafts']);
Route::get('/users/{userId}/notes-with-categories', [NoteController::class, 'userNotesWithCategories']);

Route::prefix('notes/{note}')->group(function () {
    Route::get('tasks', [TaskController::class, 'index']);
    Route::get('tasks/{task}', [TaskController::class, 'show']);
    Route::post('tasks', [TaskController::class, 'store']);
    Route::put('tasks/{task}', [TaskController::class, 'update']);
    Route::delete('tasks/{task}', [TaskController::class, 'destroy']);
});

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-all', [AuthController::class, 'logoutAll']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);
    });
});
