<?php

namespace App\Livewire\Admin\Trabajadores;

use Livewire\Component;
use App\Models\Trabajador;
use App\Models\Vacacion;
use Carbon\Carbon;

class VacacionesDashboard extends Component
{
    public Trabajador $trabajador;
    public $anioSeleccionado;
    public $resumenVacaciones = [];
    public $historialAnios = [];
    public $proximasVacaciones = [];
    public $vacacionesDelAnio = [];

    // Modal para nueva solicitud
    public $showModalNuevaSolicitud = false;
    public $fechaInicio = '';
    public $fechaFin = '';
    public $comentariosAdmin = '';
    public $diasCalculados = 0;

    public function mount(Trabajador $trabajador)
    {
        $this->trabajador = $trabajador;
        $this->anioSeleccionado = date('Y');
        $this->cargarDatos();
    }

    public function updatedAnioSeleccionado()
    {
        $this->cargarDatos();
    }

    public function updatedFechaInicio()
    {
        $this->calcularDias();
    }

    public function updatedFechaFin()
    {
        $this->calcularDias();
    }

    private function cargarDatos()
    {
        // Cargar resumen del año seleccionado
        $this->resumenVacaciones = $this->trabajador->resumenVacaciones($this->anioSeleccionado);
        
        // Cargar vacaciones del año
        $this->vacacionesDelAnio = $this->trabajador->vacacionesDelAnio($this->anioSeleccionado);
        
        // Cargar próximas vacaciones
        $this->proximasVacaciones = $this->trabajador->proximasVacaciones();
        
        // Cargar historial de años disponibles
        $this->cargarHistorialAnios();
    }

    private function cargarHistorialAnios()
    {
        $anioIngreso = $this->trabajador->FechaIngreso ? $this->trabajador->FechaIngreso->year : date('Y');
        $anioActual = date('Y');
        
        $this->historialAnios = [];
        
        for ($anio = $anioActual; $anio >= $anioIngreso; $anio--) {
            $resumen = $this->trabajador->resumenVacaciones($anio);
            $this->historialAnios[] = $resumen;
        }
    }

    private function calcularDias()
    {
        if ($this->fechaInicio && $this->fechaFin) {
            try {
                $inicio = Carbon::parse($this->fechaInicio);
                $fin = Carbon::parse($this->fechaFin);
                
                if ($inicio->lte($fin)) {
                    $this->diasCalculados = $this->calcularDiasLaborables($inicio, $fin);
                } else {
                    $this->diasCalculados = 0;
                }
            } catch (\Exception $e) {
                $this->diasCalculados = 0;
            }
        } else {
            $this->diasCalculados = 0;
        }
    }

    private function calcularDiasLaborables($fechaInicio, $fechaFin)
    {
        $diasTotales = $fechaInicio->diffInDays($fechaFin) + 1;
        $diasLaborables = 0;

        for ($i = 0; $i < $diasTotales; $i++) {
            $diaActual = $fechaInicio->copy()->addDays($i);
            if (!$diaActual->isWeekend()) {
                $diasLaborables++;
            }
        }
        
        return $diasLaborables;
    }

    public function abrirModalNuevaSolicitud()
    {
        $this->resetModalFields();
        $this->showModalNuevaSolicitud = true;
    }

    public function cerrarModal()
    {
        $this->showModalNuevaSolicitud = false;
        $this->resetModalFields();
    }

    private function resetModalFields()
    {
        $this->fechaInicio = '';
        $this->fechaFin = '';
        $this->comentariosAdmin = '';
        $this->diasCalculados = 0;
        $this->resetValidation();
    }

    public function crearSolicitudVacaciones()
    {
        $this->validate([
            'fechaInicio' => 'required|date|after_or_equal:today',
            'fechaFin' => 'required|date|after_or_equal:fechaInicio',
            'comentariosAdmin' => 'nullable|string|max:500'
        ], [
            'fechaInicio.after_or_equal' => 'La fecha de inicio debe ser hoy o posterior.',
            'fechaFin.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.'
        ]);

        if ($this->diasCalculados <= 0) {
            $this->addError('fechaFin', 'El rango de fechas debe incluir al menos un día laborable.');
            return;
        }

        // Verificar disponibilidad
        $verificacion = $this->trabajador->puedesolicitarVacaciones($this->diasCalculados, $this->anioSeleccionado);
        
        if (!$verificacion['puede_solicitar']) {
            $this->addError('fechaFin', $verificacion['mensaje']);
            return;
        }

        // Crear la solicitud directamente como admin
        Vacacion::create([
            'ID_Trabajador' => $this->trabajador->ID_Trabajador,
            'Fecha_Inicio' => $this->fechaInicio,
            'Fecha_Fin' => $this->fechaFin,
            'Dias_Solicitados' => $this->diasCalculados,
            'Estado_Solicitud' => 'Pendiente', // Se puede cambiar a 'Aprobada' si se quiere
            'Comentarios_Admin' => $this->comentariosAdmin,
            'Aprobado_por' => auth()->id()
        ]);

        $this->cerrarModal();
        $this->cargarDatos();
        $this->dispatch('solicitud-creada');
        session()->flash('message', 'Solicitud de vacaciones creada correctamente.');
    }

    public function aprobarVacacion($vacacionId)
    {
        $vacacion = Vacacion::findOrFail($vacacionId);
        $vacacion->update([
            'Estado_Solicitud' => 'Aprobada',
            'Aprobado_por' => auth()->id(),
            'Fecha_Aprobacion_Rechazo' => now()
        ]);

        $this->cargarDatos();
        session()->flash('message', 'Vacación aprobada correctamente.');
    }

    public function rechazarVacacion($vacacionId)
    {
        $vacacion = Vacacion::findOrFail($vacacionId);
        $vacacion->update([
            'Estado_Solicitud' => 'Rechazada',
            'Aprobado_por' => auth()->id(),
            'Fecha_Aprobacion_Rechazo' => now()
        ]);

        $this->cargarDatos();
        session()->flash('message', 'Vacación rechazada correctamente.');
    }

    public function eliminarVacacion($vacacionId)
    {
        Vacacion::findOrFail($vacacionId)->delete();
        
        $this->cargarDatos();
        session()->flash('message', 'Vacación eliminada correctamente.');
    }

    public function render()
    {
        return view('livewire.admin.trabajadores.vacaciones-dashboard');
    }
} 