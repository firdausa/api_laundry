<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OutletController;

Route::post('register', [UserController::class,'register']);
Route::post('login', [UserController::class, 'login']);

Route::post('outlet', [OutletController::class, 'insert']);