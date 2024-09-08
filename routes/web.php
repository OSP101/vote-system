<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\VotesController;
use App\Http\Controllers\VoteDetailController;
use App\Http\Controllers\VoteEnrollController;



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard',[VotesController::class,'findvotes'])->name('dashboard');

    Route::get('/', function () {
        return redirect('/dashboard');
    });

    Route::get('/vote/{id}', [VoteDetailController::class, 'index'])->name('vote.detail');
});

Route::get('/vote/{id}/{code}',[VoteEnrollController::class,'index'])->name('vote.enroll');

Route::post('/votes/create', [VotesController::class, 'store'])->name('event.store');
Route::post('/options/create', [VotesController::class, 'create'])->name('create.options');
Route::get('/options/delete/{id}', [VotesController::class, 'destroy'])->name('destroy.options');
Route::get('/votes/change/{id}', [VotesController::class, 'change'])->name('change.vote');
Route::post('/vote/enroll', [VoteEnrollController::class,'enroll'])->name('enroll');

