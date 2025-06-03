<?php

namespace App\Livewire\Portal\Vacaciones;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Trabajador;
use Livewire\WithPagination;
use App\Models\Vacacion;
use Livewire\Attributes\On;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';
    public ?Trabajador $trabajador;
    public $showNoTrabajadorMessage = false;

    public function mount()
    {
        $user = Auth::user();
        if ($user && $user->trabajador) {
            $this->trabajador = $user->trabajador;
        } else {
            $this->trabajador = null;
            $this->showNoTrabajadorMessage = true;
        }
    }

    public function render()
    {
        $vacaciones = collect();

        if ($this->trabajador) {
            $vacaciones = $this->trabajador->vacaciones()->orderBy('Fecha_Inicio', 'desc')->paginate(10);
        }

        return view('livewire.portal.vacaciones.index', [
            'vacaciones' => $vacaciones,
        ])->layout('layouts.app');
    }

    public function confirmarCancelacion($vacacionId)
    {
        $this->dispatch('confirm-cancelacion-vacacion', 
            id: $vacacionId, 
            title: 'Confirmar Cancelación',
            message: '¿Estás seguro de que deseas cancelar esta solicitud de vacaciones?',
            confirmButtonText: 'Sí, Cancelar',
            cancelButtonText: 'No'
        );
    }

    #[On('vacacionCancelada')]
    public function cancelarVacacion($vacacionId)
    {
        if (!$this->trabajador) return;

        $vacacion = Vacacion::where('ID_Vacaciones', $vacacionId)
                            ->where('ID_Trabajador', $this->trabajador->ID_Trabajador)
                            ->where('Estado_Solicitud', 'Pendiente')
                            ->first();

        if ($vacacion) {
            $vacacion->delete();
            session()->flash('message', 'Solicitud de vacaciones pendiente cancelada y eliminada.');
        } else {
            session()->flash('error', 'No se pudo cancelar la solicitud o ya no está pendiente.');
        }
    }
}
