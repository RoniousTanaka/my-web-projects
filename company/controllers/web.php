<?php

use App\Http\Controllers\InternController;
use App\Http\Controllers\JobController;

Route::get('/', fn () => redirect('/interns'));

Route::resource('interns', InternController::class);
Route::resource('jobs', JobController::class);

// Matching Page
Route::get('/matches', [InternController::class, 'match'])->name('interns.match');
?>