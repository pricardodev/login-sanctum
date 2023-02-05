<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;

Route::get('/login', [UserController::class, 'authenticate'])->name('login');
Route::post('/autenticacao', [UserController::class, 'authenticate'])->name('autenticacao');

Route::middleware('auth:sanctum')->controller(UserController::class)->prefix('usuario')->group(function () {
    Route::get('listar-todos', 'index')->name('usuario.listar-todos');
    Route::get('listar/{usuario}', 'show')->name('usuario.listar');
    Route::post('cadastrar', 'store')->name('usuario.cadastrar');
    Route::put('atualizar', 'update')->name('usuario.atualizar');
    Route::delete('deletar/{usuario}', 'destroy')->name('usuario.deletar');
});
