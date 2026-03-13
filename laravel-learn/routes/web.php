<?php

use App\Http\Controllers\HelloController;
use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello', [HelloController::class, 'hello']);

Route::resource('students', StudentController::class);
