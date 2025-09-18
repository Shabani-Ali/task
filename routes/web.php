<?php

use App\Http\Controllers\ProjectController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get('/', function(){return redirect()->route('tasks.index');});
Route::get('/tasks',[TaskController::class, 'index'])->name('tasks.index');
Route::post('/tasks',[TaskController::class, 'store'])->name('tasks.store');
Route::get('/tasks/{task}/edit',[TaskController::class, 'edit'])->name('tasks.edit');
Route::get('/tasks/{task}', [TaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('tasks.destroy');
Route::post('/tasks/reorder', [TaskController::class, 'reorder'])->name('tasks.reorder');

Route::resource('projects',ProjectController::class)->except(['show']);