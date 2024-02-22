<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PermissaoController;
use App\Http\Controllers\TelasController;
use App\Http\Controllers\UsuariosController;
use App\Http\Middleware\JWTAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login',[AuthController::class, 'login']);
Route::post('AdicionarPermisaoxUser',[AuthController::class, 'AdicionarPermisaoxUser']);
Route::delete('RemoverPermisaoxUser',[AuthController::class, 'RemoverPermisaoxUser']);
Route::get('AcharRoles/{id}',[AuthController::class, 'AcharRoles']);

Route::prefix('/permissao')->group(function(){
    Route::post('criar',[PermissaoController::class, 'criar']);
    Route::delete('delete/{nome}',[PermissaoController::class, 'delete']);
    Route::put('atualiza/{nome}',[PermissaoController::class, 'Atualiza']);
    Route::get('listaUm/{nome}',[PermissaoController::class, 'listarUM']);
    Route::get('listarTodos',[PermissaoController::class, 'listarTodos']);
});

Route::prefix('/tela')->group(function(){
    Route::post('criar',[TelasController::class, 'criar']);
    Route::delete('delete/{id}',[TelasController::class, 'delete']);
    Route::put('atualiza/{id}',[TelasController::class, 'Atualiza']);
    Route::get('listarTodos',[TelasController::class, 'listarAll']);
    Route::post('AdicionarPermisaoTela',[TelasController::class, 'AdicionarPermisaoTela']);
    Route::get('ListarPermissoesTelas',[TelasController::class, 'ListarPermissoesTelas']);
});


Route::post('criar',[UsuariosController::class, 'criar']);
Route::get('listarAll',[UsuariosController::class, 'listarAll']);
Route::put('AtualizaUm/{id}',[UsuariosController::class, 'AtualizaUm']);
Route::delete('deleteUsuario/{id}',[UsuariosController::class, 'deleteUsuario']);

// ROTA APENAS PARA QUEM TEM "ADMIN" como role
Route::middleware('jwt.auth:ADMIN')->get('/autorizada', [AuthController::class, 'show']);
