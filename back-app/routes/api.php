<?php

use App\Http\Controllers\LivroController;
use App\Http\Controllers\PessoaController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resource('livros', LivroController::class);
Route::resource('pessoas', PessoaController::class);
Route::resource('users', UserController::class);
Route::get('searchby', 'App\Http\Controllers\LivroController@searchBy');