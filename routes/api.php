<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\TransaksiController;

Route::post('register', [UserController::class,'register']);
Route::post('login', [UserController::class, 'login']);

Route::group(['middleware' => ['jwt.verify:admin,kasir']], function () {
    Route::post('transaksi', [TransaksiController::class, 'insert']);
    Route::put('transaksi/status', [TransaksiController::class, 'update_status']);
    Route::put('transaksi/bayar', [TransaksiController::class, 'update_bayar']);

});

Route::group(['middleware' => ['jwt.verify:admin']], function () {
    Route::post('outlet', [OutletController::class, 'insert']);
    Route::put('outlet/{id}', [OutletController::class, 'update']);
    Route::delete('outlet/{id}', [OutletController::class, 'delete']);
    Route::get('outlet', [OutletController::class, 'getAll']); //get all
    Route::get('outlet/{id_outlet}', [OutletController::class, 'getById']); //get all
});