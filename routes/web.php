<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});
Route::get('/admin', function () {
    return view('admin');
});
Route::get('/admin/add-products', function () {
    return view('add-products');
})->name('add-products');
Route::get('/admin/view-products', function () {
    return view('view-products');
})->name('view-products');
Route::get('/admin/edit-products', function () {
    return view('edit-products');
})->name('edit-products');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
