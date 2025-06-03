<?php

namespace App\Livewire\Portal\Licencias;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Trabajador;
use Livewire\WithPagination; // Para paginar resultados
use App\Models\Licencia;
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
            $this->showNoTrabajadorMessage = true; // Mostrar mensaje si no hay trabajador asociado
        }
    }

    public function render()
    {
        $licencias = collect(); // Colección vacía por defecto

        if ($this->trabajador) {
            // Cargar licencias con orden descendente por fecha de inicio
            $licencias = $this->trabajador->licencias()->orderBy('FechaInicio', 'desc')->paginate(10);
        }

        return view('livewire.portal.licencias.index', [
            'licencias' => $licencias,
        ])->layout('layouts.app');
    }

    public function confirmarCancelacion($licenciaId)
    {
        $this->dispatch('confirm-cancelacion-licencia', 
            id: $licenciaId, 
            title: 'Confirmar Cancelación',
            message: '¿Estás seguro de que deseas cancelar esta solicitud de licencia?',
            confirmButtonText: 'Sí, Cancelar',
            cancelButtonText: 'No'
        );
    }

    #[On('licenciaCancelada')]
    public function cancelarLicencia($licenciaId)
    {
        if (!$this->trabajador) return;

        $licencia = Licencia::where('ID_Licencia', $licenciaId)
                            ->where('ID_Empleado', $this->trabajador->ID_Trabajador)
                            ->where('Estado_Solicitud', 'Pendiente')
                            ->first();

        if ($licencia) {
            // Opción 1: Eliminar la solicitud
            // $licencia->delete();
            // session()->flash('message', 'Solicitud de licencia cancelada y eliminada.');

            // Opción 2: Cambiar estado a "Cancelada" (necesitaríamos añadir este estado al enum)
            // Por ahora, si no existe el estado "Cancelada", la eliminaremos.
            // Si el enum 'Estado_Solicitud' en la migración licencias no incluye 'Cancelada',
            // la opción más segura es eliminarla o no hacer nada hasta actualizar el enum.
            // Asumimos que queremos eliminarla si es Pendiente.
            $licencia->delete();
            session()->flash('message', 'Solicitud de licencia pendiente cancelada y eliminada.');
            
            // Si tuviéramos el estado "Cancelada":
            // $licencia->Estado_Solicitud = 'Cancelada';
            // $licencia->save();
            // session()->flash('message', 'Solicitud de licencia cancelada.');
        } else {
            session()->flash('error', 'No se pudo cancelar la solicitud o ya no está pendiente.');
        }
        // $this->render(); // No es necesario llamar a render explícitamente, Livewire lo hace.
    }
}
