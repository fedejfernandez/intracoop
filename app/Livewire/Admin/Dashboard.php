<?php

namespace App\Livewire\Admin;

use App\Models\Trabajador;
use App\Models\Licencia;
use App\Models\Vacacion;
use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Dashboard extends Component
{
    public $totalTrabajadores;
    public $trabajadoresActivos;
    public $trabajadoresInactivos;
    public $trabajadoresLicencia;
    public $trabajadoresVacaciones;
    
    public $licenciasPendientes;
    public $licenciasAprobadas;
    public $vacacionesPendientes;
    public $vacacionesAprobadas;
    
    public $trabajadoresPorSector;
    public $trabajadoresPorCCT;
    public $proximasVacaciones;
    public $antiguedadPromedio;
    
    public $mesActual;
    public $anioActual;

    public function mount()
    {
        $this->mesActual = Carbon::now()->month;
        $this->anioActual = Carbon::now()->year;
        
        $this->calcularEstadisticas();
    }

    public function calcularEstadisticas()
    {
        // Estadísticas básicas de trabajadores
        $this->totalTrabajadores = Trabajador::count();
        $this->trabajadoresActivos = Trabajador::where('Estado', 'Activo')->count();
        $this->trabajadoresInactivos = Trabajador::where('Estado', 'Inactivo')->count();
        $this->trabajadoresLicencia = Trabajador::where('Estado', 'Licencia')->count();
        $this->trabajadoresVacaciones = Trabajador::where('Estado', 'Vacaciones')->count();

        // Estadísticas de licencias usando el modelo Eloquent
        try {
            $this->licenciasPendientes = Licencia::where('Estado_Solicitud', 'Pendiente')->count();
            $this->licenciasAprobadas = Licencia::where('Estado_Solicitud', 'Aprobada')
                ->whereMonth('created_at', $this->mesActual)
                ->whereYear('created_at', $this->anioActual)
                ->count();
        } catch (\Exception $e) {
            $this->licenciasPendientes = 0;
            $this->licenciasAprobadas = 0;
        }

        // Estadísticas de vacaciones usando el modelo Eloquent
        try {
            $this->vacacionesPendientes = Vacacion::where('Estado_Solicitud', 'Pendiente')->count();
            $this->vacacionesAprobadas = Vacacion::where('Estado_Solicitud', 'Aprobada')
                ->whereMonth('created_at', $this->mesActual)
                ->whereYear('created_at', $this->anioActual)
                ->count();
        } catch (\Exception $e) {
            $this->vacacionesPendientes = 0;
            $this->vacacionesAprobadas = 0;
        }

        // Distribución por sector
        $this->trabajadoresPorSector = Trabajador::selectRaw('Sector, COUNT(*) as cantidad')
            ->whereNotNull('Sector')
            ->groupBy('Sector')
            ->orderBy('cantidad', 'desc')
            ->limit(10)
            ->get();

        // Distribución por CCT
        $this->trabajadoresPorCCT = Trabajador::selectRaw('CCT, COUNT(*) as cantidad')
            ->whereNotNull('CCT')
            ->groupBy('CCT')
            ->orderBy('cantidad', 'desc')
            ->get();

        // Próximas vacaciones aprobadas (ejemplo de funcionalidad adicional)
        try {
            $this->proximasVacaciones = Vacacion::where('Estado_Solicitud', 'Aprobada')
                ->where('Fecha_Inicio', '>=', Carbon::now())
                ->where('Fecha_Inicio', '<=', Carbon::now()->addDays(30))
                ->with('trabajador')
                ->orderBy('Fecha_Inicio')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            $this->proximasVacaciones = collect([]);
        }

        // Antigüedad promedio
        $this->calcularAntiguedadPromedio();
    }

    private function calcularAntiguedadPromedio()
    {
        $trabajadores = Trabajador::whereNotNull('FechaIngreso')->get();
        
        if ($trabajadores->count() > 0) {
            $totalAnios = $trabajadores->sum(function ($trabajador) {
                return Carbon::parse($trabajador->FechaIngreso)->diffInYears(Carbon::now());
            });
            
            $this->antiguedadPromedio = round($totalAnios / $trabajadores->count(), 1);
        } else {
            $this->antiguedadPromedio = 0;
        }
    }

    public function getUltimosTrabajadores()
    {
        return Trabajador::orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function getTrabajadoresPorMes()
    {
        return Trabajador::selectRaw("MONTH(FechaIngreso) as mes, COUNT(*) as cantidad")
            ->whereYear('FechaIngreso', $this->anioActual)
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.dashboard', [
            'ultimosTrabajadores' => $this->getUltimosTrabajadores(),
            'trabajadoresPorMes' => $this->getTrabajadoresPorMes(),
        ]);
    }
}
