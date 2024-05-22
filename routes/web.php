<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\TasksController;

Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::resource('projects', ProjectsController::class)->only(['store', 'update', 'destroy']);
Route::resource('tasks', TasksController::class)->only(['store', 'update', 'destroy']);

Route::prefix('tasks')->name('tasks.')->group(function (){
    Route::post('toggle-completion/{task?}', [TasksController::class, 'toggleCompletion'])->name('toggleCompletion');
    Route::post('update-priority', [TasksController::class, 'updatePriority'])->name('updatePriority');
});
