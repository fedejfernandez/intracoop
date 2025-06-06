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
use App\Livewire\Admin\Trabajadores\HistorialLaboral as AdminHistorialLaboral; // <-- IMPORTACIÓN RESTAURADA

// Componentes para el Portal del Trabajador
use App\Livewire\Portal\Dashboard as PortalDashboard;
use App\Livewire\Portal\Licencias\Index as PortalLicenciasIndex;
use App\Livewire\Portal\Licencias\Create as PortalLicenciasCreate; // O Solicitar
use App\Livewire\Portal\Vacaciones\Index as PortalVacacionesIndex;
use App\Livewire\Portal\Vacaciones\Create as PortalVacacionesCreate; // O Solicitar
use App\Livewire\Portal\Legajo as PortalLegajo;

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
        if (auth()->user()->hasRole('administrador')) { // Asumiendo que tienes el método isAdmin() en tu modelo User
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
         if (auth()->user()->hasRole('administrador')) {
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
    Route::get('/trabajadores/{trabajador}/vacaciones', \App\Livewire\Admin\Trabajadores\VacacionesDashboard::class)->name('trabajadores.vacaciones');
    Route::get('/trabajadores/{trabajadorId}/historial-laboral', AdminHistorialLaboral::class)->name('trabajadores.historial-laboral'); // <-- RUTA RESTAURADA Y PARÁMETRO CAMBIADO
    Route::get('/trabajadores/pdf', function () {
        $search = request('search');
        $estado = request('estado');
        $sector = request('sector');
        $cct = request('cct');
        $puesto = request('puesto');
        
        $query = \App\Models\Trabajador::query();
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('NombreCompleto', 'like', "%{$search}%")
                  ->orWhere('DNI_CUIL', 'like', "%{$search}%")
                  ->orWhere('Email', 'like', "%{$search}%")
                  ->orWhere('NumeroLegajo', 'like', "%{$search}%");
            });
        }
        
        if ($estado) {
            $query->where('Estado', $estado);
        }
        
        if ($sector) {
            $query->where('Sector', $sector);
        }
        
        if ($cct) {
            $query->where('CCT', $cct);
        }
        
        if ($puesto) {
            $query->where('Puesto', $puesto);
        }
        
        $trabajadores = $query->orderBy('NombreCompleto')->get();
        
        return view('exports.trabajadores-pdf', compact('trabajadores'));
    })->name('trabajadores.pdf');

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
    Route::get('/vacaciones/dashboard', \App\Livewire\Admin\Vacaciones\Dashboard::class)->name('vacaciones.dashboard');
    Route::get('/vacaciones/calendario', \App\Livewire\Admin\Vacaciones\Calendario::class)->name('vacaciones.calendario');
    // Route::get('/solicitudes/vacaciones/{vacacion}', AdminVacacionProcess::class)->name('vacaciones.process');

    // Gestión de Roles y Permisos
    Route::get('/roles', \App\Livewire\Admin\Permissions\RoleManager::class)->name('roles.index');

    // Gestión de Evaluaciones de Desempeño
    Route::resource('evaluaciones-desempeno', App\Http\Controllers\Admin\EvaluacionDesempenoController::class);

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

    // Ver Legajo Completo
    Route::get('/legajo', PortalLegajo::class)->name('legajo.show');

    // Acceso al perfil del usuario (Jetstream ya lo provee en /user/profile)
    // No necesitas definirlo aquí si Jetstream está activo.
});

