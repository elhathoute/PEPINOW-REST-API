<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ControllerRegister;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// register
Route::post('register',[ControllerRegister::class,'register'])->name('register');
// login
Route::post('login',[ControllerRegister::class,'login'])->name('login');


// middleware verify token
Route::group(['middleware'=>['jwt.verify']],function (){
// logout
Route::get('logout',[ControllerRegister::class,'logout'])->name('logout');

});

// admin role

Route::group(['middleware'=>['jwt.verify.admin']],function (){

    // Manage ALL categories
    Route::apiResource('category',CategoryController::class);
});
// Vendeur Role
Route::group(['middleware'=>['jwt.verify.vendeur']],function (){

    //Can only show categories
    Route::apiResource('category',CategoryController::class)->only('index');
});
