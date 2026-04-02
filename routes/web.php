<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\PadreController;

// =============================================
// RUTAS PÚBLICAS
// =============================================
Route::get('/', fn() => redirect()->route('login'));
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Reservas públicas (sin login) - EXPLÍCITAMENTE SIN MIDDLEWARE
Route::middleware([])->group(function () {
    Route::get('/reserva', [ReservaController::class, 'create'])->name('reservas.create');
    Route::post('/reserva', [ReservaController::class, 'store'])->name('reservas.store');
    Route::get('/reserva/confirmacion', [ReservaController::class, 'confirmacion'])->name('reservas.confirmacion');
});

// =============================================
// RUTAS ADMIN Y SECRETARIA
// =============================================
Route::middleware(['auth', 'rol:admin,secretaria'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    // Estudiantes (sin create/store — la inscripción de antiguos va por /inscripciones/antiguo)
    Route::get('/estudiantes', [EstudianteController::class, 'index'])->name('estudiantes.index');
    Route::get('/estudiantes/{estudiante}', [EstudianteController::class, 'show'])->name('estudiantes.show');
    Route::get('/estudiantes/{estudiante}/editar', [EstudianteController::class, 'edit'])->name('estudiantes.edit');
    Route::patch('/estudiantes/{estudiante}', [EstudianteController::class, 'update'])->name('estudiantes.update');
    Route::delete('/estudiantes/{estudiante}', [EstudianteController::class, 'destroy'])->name('estudiantes.destroy');

    // Inscripciones
    Route::get('/inscripciones', [InscripcionController::class, 'index'])->name('inscripciones.index');
    Route::get('/inscripciones/{inscripcion}/editar', [InscripcionController::class, 'edit'])->name('inscripciones.edit');
    Route::put('/inscripciones/{inscripcion}', [InscripcionController::class, 'update'])->name('inscripciones.update');
    Route::get('/inscripciones/crear', [InscripcionController::class, 'create'])->name('inscripciones.create');
    Route::post('/inscripciones', [InscripcionController::class, 'store'])->name('inscripciones.store');
    Route::patch('/inscripciones/{inscripcion}/estado', [InscripcionController::class, 'cambiarEstado'])->name('inscripciones.estado');
    // Inscripción inteligente para alumnos ANTIGUOS (Select2 + AJAX)
    Route::get('/inscripciones/antiguo', [InscripcionController::class, 'createAntiguo'])->name('inscripciones.antiguo.create');
    Route::post('/inscripciones/antiguo', [InscripcionController::class, 'storeAntiguo'])->name('inscripciones.antiguo.store');
    // Endpoint AJAX: buscar estudiantes antiguos por nombre
    Route::get('/api/estudiantes/buscar', [EstudianteController::class, 'buscarAjax'])->name('estudiantes.buscar');

    // Reservas admin
    Route::get('/reservas', [ReservaController::class, 'index'])->name('reservas.index');
    Route::get('/reservas/{reserva}', [ReservaController::class, 'show'])->name('reservas.show');
    Route::patch('/reservas/{reserva}/estado', [ReservaController::class, 'cambiarEstado'])->name('reservas.estado');
    Route::post('/reservas/{reserva}/convertir', [ReservaController::class, 'convertirAEstudiantes'])->name('reservas.convertir');
    Route::delete('/reservas/{reserva}', [ReservaController::class, 'destroy'])->name('reservas.destroy');

    // Notificaciones
    Route::get('/notificaciones/crear', [NotificacionController::class, 'crear'])->name('notificaciones.crear');
    Route::post('/notificaciones', [NotificacionController::class, 'crear'])->name('notificaciones.store');

    // Padres
    Route::get('/padres', [PadreController::class, 'index'])->name('padres.index');
    Route::get('/padres/crear', [PadreController::class, 'create'])->name('padres.create');
    Route::post('/padres', [PadreController::class, 'store'])->name('padres.store');
    Route::get('/padres/{padre}', [PadreController::class, 'show'])->name('padres.show');
    Route::get('/padres/{padre}/editar', [PadreController::class, 'edit'])->name('padres.edit');
    Route::patch('/padres/{padre}', [PadreController::class, 'update'])->name('padres.update');
    Route::patch('/padres/{padre}/toggle', [PadreController::class, 'toggleActivo'])->name('padres.toggle');
    Route::get('/padres/{padre}/usuario', [PadreController::class, 'createUser'])->name('padres.create_user');
    Route::post('/padres/{padre}/usuario', [PadreController::class, 'storeUser'])->name('padres.store_user');
    Route::delete('/padres/{padre}', [PadreController::class, 'destroy'])->name('padres.destroy');
});

// =============================================
// RUTAS PADRE DE FAMILIA
// =============================================
Route::middleware(['auth', 'rol:padre'])->group(function () {
    Route::get('/mi-panel', [DashboardController::class, 'padre'])->name('padre.dashboard');
    Route::get('/mis-notificaciones', [NotificacionController::class, 'index'])->name('notificaciones.index');
    Route::patch('/notificaciones/{notificacion}/leer', [NotificacionController::class, 'marcarLeida'])->name('notificaciones.leer');
});

// =============================================
// RUTAS DOCENTE
// =============================================
Route::middleware(['auth', 'rol:docente'])->group(function () {
    Route::get('/docente', [DashboardController::class, 'docente'])->name('docente.dashboard');
});
