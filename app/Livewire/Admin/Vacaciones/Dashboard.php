<?php

namespace App\Livewire\Admin\Vacaciones;

use Livewire\Component;
use App\Models\Vacacion;
use App\Models\Trabajador;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $anioSeleccionado;
    public $estadisticasGenerales = [];
    public $estadisticasPorMes = [];
    public $estadisticasPorSector = [];
    public $trabajadoresConMasVacaciones = [];
    public $solicitudesPendientes = [];
    public $proximasVacaciones = [];
    public $conflictosPotenciales = [];

    public function mount()
    {
        $this->anioSeleccionado = date('Y');
        $this->cargarDatos();
    }

    public function updatedAnioSeleccionado()
    {
        $this->cargarDatos();
    }

    private function cargarDatos()
    {
        $this->cargarEstadisticasGenerales();
        $this->cargarEstadisticasPorMes();
        $this->cargarEstadisticasPorSector();
        $this->cargarTrabajadoresConMasVacaciones();
        $this->cargarSolicitudesPendientes();
        $this->cargarProximasVacaciones();
        $this->cargarConflictosPotenciales();
    }

    private function cargarEstadisticasGenerales()
    {
        $totalSolicitudes = Vacacion::whereYear('Fecha_Inicio', $this->anioSeleccionado)->count();
        $aprobadas = Vacacion::whereYear('Fecha_Inicio', $this->anioSeleccionado)->where('Estado_Solicitud', 'Aprobada')->count();
        $pendientes = Vacacion::whereYear('Fecha_Inicio', $this->anioSeleccionado)->where('Estado_Solicitud', 'Pendiente')->count();
        $rechazadas = Vacacion::whereYear('Fecha_Inicio', $this->anioSeleccionado)->where('Estado_Solicitud', 'Rechazada')->count();
        
        $totalDiasSolicitados = Vacacion::whereYear('Fecha_Inicio', $this->anioSeleccionado)->sum('Dias_Solicitados');
        $diasAprobados = Vacacion::whereYear('Fecha_Inicio', $this->anioSeleccionado)->where('Estado_Solicitud', 'Aprobada')->sum('Dias_Solicitados');
        
        $trabajadoresActivos = Trabajador::where('Estado', 'Activo')->count();
        $trabajadoresConVacaciones = Vacacion::whereYear('Fecha_Inicio', $this->anioSeleccionado)
            ->where('Estado_Solicitud', 'Aprobada')
            ->distinct('ID_Trabajador')
            ->count();

        $this->estadisticasGenerales = [
            'total_solicitudes' => $totalSolicitudes,
            'aprobadas' => $aprobadas,
            'pendientes' => $pendientes,
            'rechazadas' => $rechazadas,
            'porcentaje_aprobacion' => $totalSolicitudes > 0 ? round(($aprobadas / $totalSolicitudes) * 100, 1) : 0,
            'total_dias_solicitados' => $totalDiasSolicitados,
            'dias_aprobados' => $diasAprobados,
            'trabajadores_activos' => $trabajadoresActivos,
            'trabajadores_con_vacaciones' => $trabajadoresConVacaciones,
            'porcentaje_trabajadores_con_vacaciones' => $trabajadoresActivos > 0 ? round(($trabajadoresConVacaciones / $trabajadoresActivos) * 100, 1) : 0,
            'promedio_dias_por_solicitud' => $totalSolicitudes > 0 ? round($totalDiasSolicitados / $totalSolicitudes, 1) : 0,
        ];
    }

    private function cargarEstadisticasPorMes()
    {
        $estadisticas = Vacacion::selectRaw('
                MONTH(Fecha_Inicio) as mes,
                COUNT(*) as total_solicitudes,
                SUM(Dias_Solicitados) as total_dias,
                SUM(CASE WHEN Estado_Solicitud = "Aprobada" THEN 1 ELSE 0 END) as aprobadas,
                SUM(CASE WHEN Estado_Solicitud = "Pendiente" THEN 1 ELSE 0 END) as pendientes
            ')
            ->whereYear('Fecha_Inicio', $this->anioSeleccionado)
            ->groupBy(DB::raw('MONTH(Fecha_Inicio)'))
            ->orderBy(DB::raw('MONTH(Fecha_Inicio)'))
            ->get();

        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];

        $this->estadisticasPorMes = [];
        for ($i = 1; $i <= 12; $i++) {
            $estadistica = $estadisticas->firstWhere('mes', $i);
            $this->estadisticasPorMes[] = [
                'mes' => $meses[$i],
                'mes_numero' => $i,
                'total_solicitudes' => $estadistica->total_solicitudes ?? 0,
                'total_dias' => $estadistica->total_dias ?? 0,
                'aprobadas' => $estadistica->aprobadas ?? 0,
                'pendientes' => $estadistica->pendientes ?? 0,
            ];
        }
    }

    private function cargarEstadisticasPorSector()
    {
        $this->estadisticasPorSector = Vacacion::join('trabajadores', 'vacaciones.ID_Trabajador', '=', 'trabajadores.ID_Trabajador')
            ->selectRaw('
                trabajadores.Sector,
                COUNT(*) as total_solicitudes,
                SUM(vacaciones.Dias_Solicitados) as total_dias,
                SUM(CASE WHEN vacaciones.Estado_Solicitud = "Aprobada" THEN 1 ELSE 0 END) as aprobadas
            ')
            ->whereYear('vacaciones.Fecha_Inicio', $this->anioSeleccionado)
            ->whereNotNull('trabajadores.Sector')
            ->groupBy('trabajadores.Sector')
            ->orderByDesc('total_dias')
            ->get()
            ->toArray();
    }

    private function cargarTrabajadoresConMasVacaciones()
    {
        $this->trabajadoresConMasVacaciones = Vacacion::join('trabajadores', 'vacaciones.ID_Trabajador', '=', 'trabajadores.ID_Trabajador')
            ->selectRaw('
                trabajadores.NombreCompleto,
                trabajadores.Sector,
                COUNT(*) as total_solicitudes,
                SUM(vacaciones.Dias_Solicitados) as total_dias_solicitados,
                SUM(CASE WHEN vacaciones.Estado_Solicitud = "Aprobada" THEN vacaciones.Dias_Solicitados ELSE 0 END) as dias_aprobados
            ')
            ->whereYear('vacaciones.Fecha_Inicio', $this->anioSeleccionado)
            ->groupBy('trabajadores.ID_Trabajador', 'trabajadores.NombreCompleto', 'trabajadores.Sector')
            ->orderByDesc('dias_aprobados')
            ->limit(10)
            ->get()
            ->toArray();
    }

    private function cargarSolicitudesPendientes()
    {
        $this->solicitudesPendientes = Vacacion::with(['trabajador:ID_Trabajador,NombreCompleto,Sector'])
            ->where('Estado_Solicitud', 'Pendiente')
            ->orderBy('created_at', 'asc')
            ->limit(10)
            ->get()
            ->toArray();
    }

    private function cargarProximasVacaciones()
    {
        $this->proximasVacaciones = Vacacion::with(['trabajador:ID_Trabajador,NombreCompleto,Sector'])
            ->where('Estado_Solicitud', 'Aprobada')
            ->where('Fecha_Inicio', '>=', now())
            ->orderBy('Fecha_Inicio', 'asc')
            ->limit(10)
            ->get()
            ->toArray();
    }

    private function cargarConflictosPotenciales()
    {
        // Buscar fechas con muchas vacaciones (más del 20% del personal)
        $totalTrabajadores = Trabajador::where('Estado', 'Activo')->count();
        $limiteConflicto = max(1, round($totalTrabajadores * 0.2));

        $this->conflictosPotenciales = Vacacion::selectRaw('
                Fecha_Inicio,
                Fecha_Fin,
                COUNT(*) as cantidad_trabajadores,
                GROUP_CONCAT(trabajadores.NombreCompleto SEPARATOR ", ") as trabajadores_nombres
            ')
            ->join('trabajadores', 'vacaciones.ID_Trabajador', '=', 'trabajadores.ID_Trabajador')
            ->where('vacaciones.Estado_Solicitud', 'Aprobada')
            ->where('vacaciones.Fecha_Inicio', '>=', now())
            ->groupBy('vacaciones.Fecha_Inicio', 'vacaciones.Fecha_Fin')
            ->havingRaw('COUNT(*) >= ?', [$limiteConflicto])
            ->orderBy('vacaciones.Fecha_Inicio')
            ->limit(5)
            ->get()
            ->toArray();
    }

    public function exportarReporte()
    {
        // Implementar lógica de exportación
        $this->dispatch('exportar-reporte', anio: $this->anioSeleccionado);
    }

    public function render()
    {
        return view('livewire.admin.vacaciones.dashboard')->layout('layouts.app');
    }
} 