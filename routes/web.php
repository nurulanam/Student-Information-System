<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StaticsController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\ProgramController;
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

        // Users
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/update', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/destroy/{id}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    Route::middleware(['auth', 'role:manager|finance'])->group(function () {
        //Students
        Route::get('/students', [StudentController::class, 'index'])->name('students.index');
        Route::post('/students/store', [StudentController::class, 'store'])->name('students.store');
        Route::put('/students/update', [StudentController::class, 'update'])->name('students.update');
        Route::delete('/students/destroy/{id}', [StudentController::class, 'destroy'])->name('students.destroy');

    });

    Route::middleware(['auth', 'role:manager|finance|admin'])->group(function () {
        Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    });

    Route::middleware(['auth', 'role:manager'])->group(function () {
        // Updates
        Route::get('/updates', [UpdateController::class, 'index'])->name('updates.index');

        // Programs
        Route::get('/programs', [ProgramController::class, 'index'])->name('programs.index');
        Route::post('/programs/store', [ProgramController::class, 'store'])->name('programs.store');
        Route::put('/programs/update', [ProgramController::class, 'update'])->name('programs.update');
        Route::delete('/programs/delete/{id}', [ProgramController::class, 'destroy'])->name('programs.destroy');

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
