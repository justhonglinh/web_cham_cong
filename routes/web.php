<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $employees = (new UserController())->getEmployees();

    return view('dashboard', compact('employees'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/employees/management', function () {
    $employees = (new UserController())->getEmployees();

    return view('employees_management', compact('employees'));
})->middleware(['auth', 'verified'])->name('employees.management');


Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// user
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('users.destroy');


//attendance
Route::middleware(['auth', 'role:manager'])->group(function () {
    Route::get('/attendance', [AttendanceController::class, 'show'])->name('attendance.index');
});
require __DIR__.'/auth.php';
