<?php

namespace App\Livewire\Admin\Vacaciones;

use Livewire\Component;
use App\Models\Vacacion;
use App\Models\Trabajador;
use Carbon\Carbon;

class Calendario extends Component
{
    public $mesActual;
    public $anioActual;
    public $vacacionesDelMes = [];
    public $diasDelMes = [];
    public $trabajadorSeleccionado = '';
    public $trabajadores = [];
    
    // Modal para detalles del día
    public $showModalDia = false;
    public $diaSeleccionado;
    public $vacacionesDelDia = [];

    public function mount()
    {
        $this->mesActual = date('n');
        $this->anioActual = date('Y');
        $this->trabajadores = Trabajador::orderBy('NombreCompleto')->get();
        $this->cargarCalendario();
    }

    public function updatedMesActual()
    {
        $this->cargarCalendario();
    }

    public function updatedAnioActual()
    {
        $this->cargarCalendario();
    }

    public function updatedTrabajadorSeleccionado()
    {
        $this->cargarCalendario();
    }

    public function mesAnterior()
    {
        $fecha = Carbon::createFromDate($this->anioActual, $this->mesActual, 1)->subMonth();
        $this->mesActual = $fecha->month;
        $this->anioActual = $fecha->year;
        $this->cargarCalendario();
    }

    public function mesSiguiente()
    {
        $fecha = Carbon::createFromDate($this->anioActual, $this->mesActual, 1)->addMonth();
        $this->mesActual = $fecha->month;
        $this->anioActual = $fecha->year;
        $this->cargarCalendario();
    }

    private function cargarCalendario()
    {
        $this->cargarVacacionesDelMes();
        $this->generarDiasDelMes();
    }

    private function cargarVacacionesDelMes()
    {
        $inicioMes = Carbon::createFromDate($this->anioActual, $this->mesActual, 1)->startOfMonth();
        $finMes = Carbon::createFromDate($this->anioActual, $this->mesActual, 1)->endOfMonth();

        $query = Vacacion::with(['trabajador:ID_Trabajador,NombreCompleto,Sector'])
            ->where('Estado_Solicitud', 'Aprobada')
            ->where(function ($q) use ($inicioMes, $finMes) {
                $q->whereBetween('Fecha_Inicio', [$inicioMes, $finMes])
                  ->orWhereBetween('Fecha_Fin', [$inicioMes, $finMes])
                  ->orWhere(function ($q2) use ($inicioMes, $finMes) {
                      $q2->where('Fecha_Inicio', '<=', $inicioMes)
                         ->where('Fecha_Fin', '>=', $finMes);
                  });
            });

        if ($this->trabajadorSeleccionado) {
            $query->where('ID_Trabajador', $this->trabajadorSeleccionado);
        }

        $this->vacacionesDelMes = $query->get();
    }

    private function generarDiasDelMes()
    {
        $inicioMes = Carbon::createFromDate($this->anioActual, $this->mesActual, 1);
        $finMes = $inicioMes->copy()->endOfMonth();
        
        // Empezar desde el lunes de la semana del primer día
        $inicioPeriodo = $inicioMes->copy()->startOfWeek(Carbon::MONDAY);
        $finPeriodo = $finMes->copy()->endOfWeek(Carbon::SUNDAY);

        $this->diasDelMes = [];
        $current = $inicioPeriodo->copy();

        while ($current <= $finPeriodo) {
            $esDelMesActual = $current->month == $this->mesActual;
            $vacacionesDelDia = $this->obtenerVacacionesDelDia($current);

            $this->diasDelMes[] = [
                'fecha' => $current->copy(),
                'dia' => $current->day,
                'es_del_mes' => $esDelMesActual,
                'es_hoy' => $current->isToday(),
                'es_fin_de_semana' => $current->isWeekend(),
                'vacaciones' => $vacacionesDelDia,
                'cantidad_vacaciones' => count($vacacionesDelDia),
            ];

            $current->addDay();
        }
    }

    private function obtenerVacacionesDelDia($fecha)
    {
        return $this->vacacionesDelMes->filter(function ($vacacion) use ($fecha) {
            return $fecha->between($vacacion->Fecha_Inicio, $vacacion->Fecha_Fin);
        })->values()->toArray();
    }

    public function abrirModalDia($fecha, $vacaciones)
    {
        $this->diaSeleccionado = $fecha;
        $this->vacacionesDelDia = $vacaciones;
        $this->showModalDia = true;
    }

    public function cerrarModalDia()
    {
        $this->showModalDia = false;
        $this->diaSeleccionado = null;
        $this->vacacionesDelDia = [];
    }

    public function obtenerNombreMes()
    {
        $meses = [
            1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
            5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
            9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
        ];
        return $meses[$this->mesActual];
    }

    public function render()
    {
        return view('livewire.admin.vacaciones.calendario')->layout('layouts.app');
    }
} 