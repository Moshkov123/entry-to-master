<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecordingtimeController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Admin;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard',[AdminController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/post', [PostController::class, 'post'])->name('post');
    Route::post('/post/check', [PostController::class, 'post_check'])->name('post.check');
    Route::delete('/post/{id}', [PostController::class, 'delete'])->name('post.delete');  
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [RecordingtimeController::class, 'index'])->name('dashboard');
    Route::post('/dashboard', [RecordingtimeController::class, 'check']);
    Route::post('/dashboard/book-appointment', [RecordingtimeController::class, 'bookAppointment'])->name('book-appointment');

});

require __DIR__.'/auth.php';
