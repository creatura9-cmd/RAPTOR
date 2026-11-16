<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarcaMotoController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\MotoController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\MecanicoController;
use App\Http\Controllers\RepuestoController;
use App\Http\Controllers\DiagnosticoController;
use App\Http\Controllers\PreordenController;
use App\Http\Controllers\PortalClienteController;
use App\Http\Controllers\PreordenRepuestoController;
use App\Http\Controllers\OrdenTrabajoController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\EntregaController;
use App\Http\Controllers\HistorialController;
use App\Http\Controllers\PasswordController;

/*
|--------------------------------------------------------------------------
| Página principal
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

/*

| Autenticación

*/

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'mostrarLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.store');

    // Solicitar restablecimiento de contraseña
    Route::get('/olvidaste-contrasena', [
        PasswordController::class,
        'mostrarSolicitud',
    ])->name('password.request');

    Route::post('/enviar-enlace-restablecimiento', [
        PasswordController::class,
        'enviarEnlace',
    ])->name('password.email');

    // Establecer nueva contraseña
    Route::get('/restablecer-contrasena/{token}', [
        PasswordController::class,
        'mostrarFormulario',
    ])->name('password.reset');

    Route::post('/restablecer-contrasena', [
        PasswordController::class,
        'restablecer',
    ])->name('password.update');

});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

/*
| Portal del cliente
*/

Route::middleware(['auth', 'role:cliente'])->group(function () {

    Route::get('/portal-cliente', [
        PortalClienteController::class,
        'index',
    ])->name('cliente.portal');

    Route::patch('/portal-cliente/preorden/{id}/aprobar', [
        PortalClienteController::class,
        'aprobar',
    ])->name('cliente.preorden.aprobar');

    Route::patch('/portal-cliente/preorden/{id}/rechazar', [
        PortalClienteController::class,
        'rechazar',
    ])->name('cliente.preorden.rechazar');

    // Documentos PDF del historial del cliente

    Route::get('/portal-cliente/historial/servicio/{id}/pdf', [
        PortalClienteController::class,
        'descargarServicio',
    ])->name('cliente.historial.servicio.pdf');

    Route::get('/portal-cliente/historial/moto/{idMoto}/pdf', [
        PortalClienteController::class,
        'descargarHistorialMoto',
    ])->name('cliente.historial.moto.pdf');
});

/*

| Administración

*/

Route::middleware(['auth', 'role:administrador'])->group(function () {

    Route::post(
        'cliente/{id}/habilitar-portal',
        [ClienteController::class, 'habilitarPortal']
    )->name('cliente.habilitar_portal');

    Route::post(
         'cliente/{id}/reenviar-invitacion',
            [ClienteController::class, 'reenviarInvitacion']
    )->name('cliente.reenviar_invitacion');

    Route::get('/dashboard', [DashboardController::class, 'index'])
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

    Route::resource('preorden', PreordenController::class)
        ->except(['show'])
        ->names('preorden');


    Route::resource('preorden_repuesto', PreordenRepuestoController::class)
        ->except(['show'])
        ->names('preorden_repuesto');

    Route::resource('orden_trabajo', OrdenTrabajoController::class)
        ->except(['show'])
        ->names('orden_trabajo');

    Route::patch('pago/{id}/confirmar', [PagoController::class, 'confirmar'])
        ->name('pago.confirmar');

    Route::resource('pago', PagoController::class)
        ->except(['show'])
        ->names('pago');

    Route::resource('entrega', EntregaController::class)
        ->except(['show'])
        ->names('entrega');

    // Historial de servicios

    Route::get('historial', [HistorialController::class, 'index'])
        ->name('historial.index');

    Route::get('historial/moto/{idMoto}/pdf', [HistorialController::class, 'descargarHistorialMoto'])
        ->name('historial.moto.pdf');

    Route::get('historial/{id}/descargar', [HistorialController::class, 'descargar'])
        ->name('historial.descargar');

});
