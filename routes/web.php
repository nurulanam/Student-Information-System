<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaticsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UpdateController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
    return redirect('login');
});

Route::prefix('/dashboard')->middleware(['auth'])->group(function (){
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard.index');

    // super admin
    Route::middleware(['auth', 'role:super-admin'])->group(function () {
        Route::get('/statics', [StaticsController::class, 'index'])->name('statics.index');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');

    });
    Route::middleware(['auth', 'role:manager|finance'])->group(function () {
        Route::get('/students', [StudentController::class, 'index'])->name('students.index');
    });
    Route::middleware(['auth', 'role:manager|finance|admin'])->group(function () {
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    });
    Route::middleware(['auth', 'role:manager'])->group(function () {

        Route::get('/updates', [UpdateController::class, 'index'])->name('updates.index');

    });
    Route::middleware(['auth', 'role:finance'])->group(function () {

    });

    Route::middleware(['auth', 'role:admin'])->group(function () {

    });
});

//Route::middleware('auth')->group(function () {
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//});

require __DIR__.'/auth.php';
