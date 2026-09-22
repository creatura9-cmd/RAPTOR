<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarcaMotoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\MotoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\MecanicoController;
use App\Http\Controllers\RepuestoController;
use App\Http\Controllers\DiagnosticoController;


Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard.index');

Route::resource('marca-moto', MarcaMotoController::class)
    ->except(['show'])
    ->names('marca_moto');

Route::resource('cliente', ClienteController::class)
    ->except(['show'])
    ->names('cliente');

Route::resource('moto', MotoController::class)
    ->except(['show'])
    ->names('moto');

Route::resource('inventario', InventarioController::class)
    ->except(['show'])
    ->names('inventario');

Route::resource('mecanico', MecanicoController::class)
    ->except(['show'])
    ->names('mecanico');

Route::resource('repuesto', RepuestoController::class)
    ->except(['show'])
    ->names('repuesto');

Route::resource('diagnostico', DiagnosticoController::class)
    ->except(['show'])
    ->names('diagnostico');



