<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ArticuloController;

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
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/kom', [Controller::class, 'index']);
Route::get('/articulos', [ArticuloController::class, 'obtenerArticulos']);  
Route::get('/imagenes', [ArticuloController::class, 'mostrarImagenes']);     
//Route::get('/com', [ArticuloController::class, 'urlImagen']);  
Route::get('/stock', [ArticuloController::class, 'mostrarStock']);  