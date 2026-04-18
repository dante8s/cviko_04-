<?php

use App\Http\Controllers\NoteController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AttachmentController;



Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum')->group(function () {
        // notes
        Route::get('/notes', [NoteController::class, 'index']);      // nástenka - všetky poznámky
        Route::get('/my-notes', [NoteController::class, 'myNotes']); // len moje poznámky (aj drafty)
        Route::post('/notes', [NoteController::class, 'store']);
        Route::get('/notes/{note}', [NoteController::class, 'show']);
        Route::patch('/notes/{note}', [NoteController::class, 'update']);
        Route::delete('/notes/{note}', [NoteController::class, 'destroy']);
        Route::get('/notes/stats', [NoteController::class, 'statsByStatus']);
        Route::patch('/notes/archive-old', [NoteController::class, 'archiveOldDrafts']);
        Route::get('/users/{userId}/notes-with-categories', [NoteController::class, 'userNotesWithCategories']);

        Route::get('/notes/{note}/attachments', [AttachmentController::class, 'index']);
        Route::post('/notes/{note}/attachments', [AttachmentController::class, 'store'])
            ->middleware('premium');
        Route::get('/attachments/{attachment}/link', [AttachmentController::class, 'link']);

        // vy máte možno iné routy, nekopírujte naslepo...
        Route::patch('/notes/{note}/publish', [NoteController::class, 'publish']);
        Route::patch('/notes/{note}/archive', [NoteController::class, 'archive']);
        Route::patch('/notes/{note}/pin', [NoteController::class, 'pin']);
        Route::patch('/notes/{note}/unpin', [NoteController::class, 'unpin']);

        // tasks
        Route::apiResource('notes.tasks', TaskController::class)->scoped();

        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/logout-all', [AuthController::class, 'logoutAll']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
        Route::put('/profile', [AuthController::class, 'updateProfile']);


        Route::get('/categories', [CategoryController::class, 'index']);
        Route::get('/categories/{id}', [CategoryController::class, 'show']);
        Route::post('/categories', [CategoryController::class, 'store']);
        Route::put('/categories/{id}', [CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

        Route::prefix('notes/{note}')->group(function () {
            Route::get('tasks', [TaskController::class, 'index']);
            Route::get('tasks/{task}', [TaskController::class, 'show']);
            Route::post('tasks', [TaskController::class, 'store']);
            Route::put('tasks/{task}', [TaskController::class, 'update']);
            Route::delete('tasks/{task}', [TaskController::class, 'destroy']);
        });
    });
});
