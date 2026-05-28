<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\SupplierManager;
use App\Livewire\ContactManager;
use App\Livewire\CategoryManager;


Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified', 'working.hours'])->group(function () {
    
    // Dashboard (Supplier Manager)
    Route::get('/dashboard', SupplierManager::class)->name('dashboard');
    Route::get('/suppliers', SupplierManager::class)->name('suppliers');

    // Contacts Manager
    Route::get('/contacts', ContactManager::class)->name('contacts');

    //categories
    Route::get('/categories', CategoryManager::class)->name('categories');
    
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
