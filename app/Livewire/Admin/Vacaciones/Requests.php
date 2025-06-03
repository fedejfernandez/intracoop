<?php

namespace App\Livewire\Admin\Vacaciones;

use Livewire\Component;
use App\Models\Vacacion;
use App\Models\Trabajador;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class Requests extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $vacacionIdParaGestionar;
    public $comentariosAdmin;
    public $accionModal;

    public $filtroEstado = '';
    public $filtroTrabajador = '';
    public $filtroFechaDesde = '';
    public $filtroFechaHasta = '';

    public $trabajadores;

    public function mount()
    {
        $this->trabajadores = Trabajador::orderBy('NombreCompleto')->get();
        $this->filtroEstado = 'Pendiente';
    }

    public function abrirModalGestion($vacacionId, $accion)
    {
        $this->vacacionIdParaGestionar = $vacacionId;
        $this->accionModal = $accion;
        $this->comentariosAdmin = Vacacion::find($vacacionId)->Comentarios_Admin ?? '';
        $this->dispatch('open-gestion-modal');
    }

    public function gestionarSolicitud()
    {
        $this->validate([
            'comentariosAdmin' => $this->accionModal === 'rechazar' ? 'required|string|min:5' : 'nullable|string',
        ], [
            'comentariosAdmin.required' => 'Debe ingresar un motivo de rechazo.',
            'comentariosAdmin.min' => 'El motivo de rechazo debe tener al menos 5 caracteres.'
        ]);

        $vacacion = Vacacion::findOrFail($this->vacacionIdParaGestionar);

        if ($this->accionModal === 'aprobar') {
            $vacacion->Estado_Solicitud = 'Aprobada';
        } elseif ($this->accionModal === 'rechazar') {
            $vacacion->Estado_Solicitud = 'Rechazada';
        }

        $vacacion->Aprobado_por = Auth::id();
        $vacacion->Fecha_Aprobacion_Rechazo = Carbon::now();
        $vacacion->Comentarios_Admin = $this->comentariosAdmin;
        $vacacion->save();

        session()->flash('message', 'Solicitud de vacaciones ' . ($this->accionModal === 'aprobar' ? 'aprobada' : 'rechazada') . ' correctamente.');
        $this->dispatch('close-gestion-modal');
        $this->reset(['vacacionIdParaGestionar', 'comentariosAdmin', 'accionModal']);
    }
    
    public function getVacacionDetailsForModal($id)
    {
        $vacacion = Vacacion::with(['trabajador:ID_Trabajador,NombreCompleto,Email', 'aprobador:id,name'])->find($id);
        if (!$vacacion) {
            \Illuminate\Support\Facades\Log::warning("getVacacionDetailsForModal: No se encontró Vacacion con ID: " . $id);
            return null;
        }

        try {
            return [
                'id' => $vacacion->ID_Vacaciones,
                'trabajador_nombre' => $vacacion->trabajador ? $vacacion->trabajador->NombreCompleto : 'N/A',
                'trabajador_email' => $vacacion->trabajador ? $vacacion->trabajador->Email : 'N/A',
                'fecha_inicio_formatted' => $vacacion->Fecha_Inicio ? $vacacion->Fecha_Inicio->format('d/m/Y') : 'N/A',
                'fecha_fin_formatted' => $vacacion->Fecha_Fin ? $vacacion->Fecha_Fin->format('d/m/Y') : 'N/A',
                'dias_solicitados' => $vacacion->Dias_Solicitados,
                'estado_solicitud' => $vacacion->Estado_Solicitud,
                'fecha_solicitud_formatted' => $vacacion->created_at ? $vacacion->created_at->format('d/m/Y H:i') : 'N/A',
                'aprobado_por_nombre' => $vacacion->aprobador ? $vacacion->aprobador->name : null,
                'fecha_aprobacion_rechazo_formatted' => $vacacion->Fecha_Aprobacion_Rechazo ? $vacacion->Fecha_Aprobacion_Rechazo->format('d/m/Y H:i') : null,
                'comentarios_admin' => $vacacion->Comentarios_Admin,
            ];
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Error en getVacacionDetailsForModal para Vacacion ID {$id}: " . $e->getMessage(), ['exception' => $e]);
            return null; // Devuelve null para que el frontend muestre el error genérico.
        }
    }

    public function updatedFiltroFechaDesde()
    {
        $this->resetPage();
    }
    public function updatedFiltroFechaHasta()
    {
        $this->resetPage();
    }
    public function updatedFiltroEstado()
    {
        $this->resetPage();
    }
    public function updatedFiltroTrabajador()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Vacacion::with('trabajador.user', 'aprobador');

        if ($this->filtroEstado) {
            $query->where('Estado_Solicitud', $this->filtroEstado);
        }

        if ($this->filtroTrabajador) {
            $query->where('ID_Trabajador', $this->filtroTrabajador);
        }

        if ($this->filtroFechaDesde) {
            $query->whereDate('Fecha_Inicio', '>=', $this->filtroFechaDesde);
        }

        if ($this->filtroFechaHasta) {
            $query->whereDate('Fecha_Fin', '<=', $this->filtroFechaHasta);
        }

        $solicitudes = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('livewire.admin.vacaciones.requests', [
            'solicitudes' => $solicitudes,
        ])->layout('layouts.app');
    }
}
