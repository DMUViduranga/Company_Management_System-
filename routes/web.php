<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\SupplierManager;
use App\Livewire\ContactManager;
use App\Livewire\CategoryManager;
use App\Livewire\UserAccessTimeManager;


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

    // Admin-only: Device Approvals
    Route::get('/admin/device-approvals', function () {
        return view('admin.device-approvals'); })->name('admin.device-approvals');
        
    // Admin-only: User Access Time Management
    Route::get('/user-access-times', UserAccessTimeManager::class)->name('user-access-times');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
