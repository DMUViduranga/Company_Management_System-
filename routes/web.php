<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Livewire\SupplierManager;
use App\Livewire\ContactManager;
use App\Livewire\CategoryManager;
use App\Livewire\UserAccessTimeManager;
use App\Http\Controllers\Admin\DeviceApprovalController;


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

    Route::get('/admin/device-approvals', [DeviceApprovalController::class, 'index'])->name('admin.device-approvals');

 
    Route::post('/admin/device-approvals/{id}/approve', [DeviceApprovalController::class, 'approve'])->name('admin.devices.approve');

    // Admin-only: User Access Time Management
    Route::get('/user-access-times', UserAccessTimeManager::class)->name('user-access-times');

   
    Route::delete('/admin/device-approvals/{id}/revoke', [DeviceApprovalController::class, 'revoke'])->name('admin.devices.revoke');
});


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
