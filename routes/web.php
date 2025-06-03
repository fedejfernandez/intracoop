<?php

use Illuminate\Support\Facades\Route;

// Controladores o Componentes Livewire (asegúrate de que los namespaces sean correctos)
// Componentes para el Panel de Administración
use App\Livewire\Admin\Dashboard as AdminDashboard; // Suponiendo que tienes un dashboard de admin
use App\Livewire\Admin\Trabajadores\Index as AdminTrabajadoresIndex;
use App\Livewire\Admin\Trabajadores\Create as AdminTrabajadorCreate; // O un CreateEditForm
use App\Livewire\Admin\Trabajadores\Edit as AdminTrabajadorEdit;   // O un CreateEditForm
use App\Livewire\Admin\Licencias\Requests as AdminLicenciasRequests;
use App\Livewire\Admin\Vacaciones\Requests as AdminVacacionesRequests;
use App\Livewire\Admin\Trabajadores\Legajo as AdminTrabajadorLegajo;
use App\Livewire\Admin\Users\Index as AdminUsersIndex;
use App\Livewire\Admin\Users\Create as AdminUserCreate;
use App\Livewire\Admin\Users\Edit as AdminUserEdit;

// Componentes para el Portal del Trabajador
use App\Livewire\Portal\Dashboard as PortalDashboard;
use App\Livewire\Portal\Licencias\Index as PortalLicenciasIndex;
use App\Livewire\Portal\Licencias\Create as PortalLicenciasCreate; // O Solicitar
use App\Livewire\Portal\Vacaciones\Index as PortalVacacionesIndex;
use App\Livewire\Portal\Vacaciones\Create as PortalVacacionesCreate; // O Solicitar

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // Redirigir al login o a un dashboard si está autenticado
    if (auth()->check()) {
        if (auth()->user()->isAdmin()) { // Asumiendo que tienes el método isAdmin() en tu modelo User
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('portal.dashboard');
    }
    return view('auth.login'); // O tu vista de bienvenida/login
})->name('home');


// Rutas de Autenticación (Jetstream las maneja internamente, pero aquí está el acceso al dashboard)
// Laravel Jetstream/Fortify ya define las rutas de login, register, password reset, etc.
// Estas rutas generalmente se protegen con el middleware 'auth' o 'guest' según corresponda
// a través de Fortify.

// Dashboard general después del login (Jetstream lo suele redirigir a /dashboard)
// Puedes personalizar esta ruta si es necesario.
 Route::middleware([
    'auth:sanctum', // O solo 'auth' si no usas Sanctum para la web
     config('jetstream.auth_session'),
     'verified', // Si usas verificación de email
 ])->group(function () {
     Route::get('/dashboard', function () {
         if (auth()->user()->isAdmin()) {
             return redirect()->route('admin.dashboard');
         }         return redirect()->route('portal.dashboard');
     })->name('dashboard'); // Esta es la ruta a la que Jetstream redirige por defecto después del login
 });


// -------------------------------------------------------------------------
// RUTAS DEL PANEL DE ADMINISTRACIÓN (Protegidas por 'auth' y 'admin')
// -------------------------------------------------------------------------
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

     Route::get('/dashboard', AdminDashboard::class)->name('dashboard');

    // CRUD Trabajadores
    Route::get('/trabajadores', AdminTrabajadoresIndex::class)->name('trabajadores.index');
    Route::get('/trabajadores/crear', AdminTrabajadorCreate::class)->name('trabajadores.create');
    Route::get('/trabajadores/{trabajador}/editar', AdminTrabajadorEdit::class)->name('trabajadores.edit');
    Route::get('/trabajadores/{trabajador}/legajo', AdminTrabajadorLegajo::class)->name('trabajadores.legajo');
    // La ruta para ver legajo podría ser un modal en index, una sección en edit, o una ruta separada:
    // Route::get('/trabajadores/{trabajador}', AdminTrabajadorShow::class)->name('trabajadores.show');

    // CRUD Usuarios
    Route::get('/users', AdminUsersIndex::class)->name('users.index');
    Route::get('/users/crear', AdminUserCreate::class)->name('users.create');
    Route::get('/users/{user}/editar', AdminUserEdit::class)->name('users.edit');

    // Gestión de Solicitudes de Licencias
    Route::get('/solicitudes/licencias', AdminLicenciasRequests::class)->name('licencias.requests');
    // Podrías tener una ruta para ver/procesar una solicitud específica si es necesario
    // Route::get('/solicitudes/licencias/{licencia}', AdminLicenciaProcess::class)->name('licencias.process');

    // Gestión de Solicitudes de Vacaciones
    Route::get('/solicitudes/vacaciones', AdminVacacionesRequests::class)->name('vacaciones.requests');
    // Route::get('/solicitudes/vacaciones/{vacacion}', AdminVacacionProcess::class)->name('vacaciones.process');

    // Aquí podrías añadir más rutas para el administrador (configuraciones, reportes, etc.)
});


// -------------------------------------------------------------------------
// RUTAS DEL PORTAL DEL TRABAJADOR (Protegidas solo por 'auth')
// -------------------------------------------------------------------------
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])
    ->prefix('portal')
    ->name('portal.')
    ->group(function () {

    Route::get('/dashboard', PortalDashboard::class)->name('dashboard');

    // Gestión de Licencias Propias
    Route::get('/licencias', PortalLicenciasIndex::class)->name('licencias.index');
    Route::get('/licencias/solicitar', PortalLicenciasCreate::class)->name('licencias.solicitar');
    // Podrías tener rutas para ver detalle de una licencia propia o cancelarla (si se permite)
    // Route::get('/licencias/{licencia}', PortalLicenciaShow::class)->name('licencias.show');

    // Gestión de Vacaciones Propias
    Route::get('/vacaciones', PortalVacacionesIndex::class)->name('vacaciones.index');
    Route::get('/vacaciones/solicitar', PortalVacacionesCreate::class)->name('vacaciones.solicitar');
    // Route::get('/vacaciones/{vacacion}', PortalVacacionShow::class)->name('vacaciones.show');

    // Acceso al perfil del usuario (Jetstream ya lo provee en /user/profile)
    // No necesitas definirlo aquí si Jetstream está activo.
});

