<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/products/add-products', [ProductController::class, 'create'])->name('add-products');

Route::get('/products/view-products', function () {
    return view('view-products');
})->name('view-products');

Route::get('/products/edit-products', function () {
    return view('edit-products');
})->name('edit-products');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::post('/products/store', [ProductController::class, 'store'])->name('products.store');
Route::get('/products/view-products', [ProductController::class, 'index'])->name('view-products');
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

Route::get('/products/edit-products/{id}', [ProductController::class, 'edit'])->name('edit-products');
Route::post('/products/update/{id}', [ProductController::class, 'update'])->name('products.update');
Route::delete('/products/image/{id}', [ProductController::class, 'deleteImage'])->name('products.image.delete');




Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
