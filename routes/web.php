<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TaskController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        if (Auth::user()->is_admin)
            return redirect('/dashboard');
        else
            return redirect('/tasks');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/tasks', [TaskController::class, 'index'])->name('task.index');
    Route::post('/task', [TaskController::class, 'store'])->name('task.store');
    Route::post('/task/{id}', [TaskController::class, 'complete'])->name('task.complete');
    Route::put('/task/{id}', [TaskController::class, 'update'])->name('task.update');
    Route::delete('/task/{id}', [TaskController::class, 'destroy'])->name('task.destroy');

    Route::get('/dashboard', [TaskController::class, 'report'])->name('dashboard');
});

require __DIR__ . '/auth.php';
