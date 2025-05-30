<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoteController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\VotersController;
use App\Http\Controllers\CandidateController;
use App\Http\Controllers\Auth\LoginController;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('web'); // Allow both

Route::get('/', function () {
    return redirect()->route('login');
});

Route::post('/vote', [VoteController::class, 'store'])->middleware('auth');
Route::post('/candidates', [CandidateController::class, 'store'])->name('candidates.store');
Route::post('/voter', [VotersController::class, 'store'])->name('voter.store');
Route::post('/accounts', [AdminController::class, 'store'])->name('admin.store');



Route::get('/voting', [CandidateController::class, 'index'])->middleware('auth')->name('voting');

Route::get('/admin', [AdminController::class, 'index'])->middleware('auth:admin')->name('admin');
Route::get('/candidates', [CandidateController::class, 'present'])->middleware('auth:admin');
Route::get('/voter', [VotersController::class, 'index'])->name('voters');



Route::get('/accounts', [AdminController::class, 'present'])->middleware('auth:admin');

Route::put('/voter/update/{id}', [VotersController::class, 'update'])->name('voter.update');
Route::put('/candidates/update/{id}', [CandidateController::class, 'update'])->name('candidates.update');
Route::put('/admin/update/{id}', [AdminController::class, 'update'])->name('admin.update');

Route::delete('/candidates/{id}', [CandidateController::class, 'destroy'])->name('candidates.destroy');
Route::delete('/voter/{id}', [VotersController::class, 'destroy'])->name('voter.destroy');
Route::delete('/admin/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');




