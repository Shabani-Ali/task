<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::resource('tasks', TaskController::class)->except(['show']);
Route::post('/tasks/reorder', [TaskController::class, 'reorder'])->name('tasks.reorder');
Route::resource('projects',ProjectController::class)->except(['show']);