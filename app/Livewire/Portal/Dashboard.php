<?php

namespace App\Livewire\Portal;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Trabajador;
use App\Models\Vacacion;
use App\Models\Licencia;

class Dashboard extends Component
{
    public ?Trabajador $trabajador;
    public $resumenVacaciones = [];
    public $proximasVacaciones = [];
    public $solicitudesPendientes = [];
    public $licenciasPendientes = [];

    public function mount()
    {
        $user = Auth::user();
        if ($user && $user->trabajador) {
            $this->trabajador = $user->trabajador;
            $this->cargarDatosVacaciones();
            $this->cargarSolicitudesPendientes();
        } else {
            $this->trabajador = null;
        }
    }

    private function cargarDatosVacaciones()
    {
        if (!$this->trabajador) return;

        // Cargar resumen de vacaciones del año actual
        $this->resumenVacaciones = $this->trabajador->resumenVacaciones();
        
        // Cargar próximas vacaciones aprobadas
        $this->proximasVacaciones = $this->trabajador->proximasVacaciones(3);
    }

    private function cargarSolicitudesPendientes()
    {
        if (!$this->trabajador) return;

        // Cargar solicitudes de vacaciones pendientes
        $this->solicitudesPendientes = $this->trabajador->vacaciones()
            ->where('Estado_Solicitud', 'Pendiente')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Cargar solicitudes de licencias pendientes
        $this->licenciasPendientes = $this->trabajador->licencias()
            ->where('Estado_Solicitud', 'Pendiente')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.portal.dashboard')->layout('layouts.app');
    }
}
