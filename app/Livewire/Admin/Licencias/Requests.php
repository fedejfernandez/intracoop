<?php

namespace App\Livewire\Admin\Licencias;

use Livewire\Component;
use App\Models\Licencia;
use App\Models\Trabajador;
use App\Models\User;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Requests extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';

    public $licenciaIdParaGestionar;
    public $comentariosAdmin;
    public $accionModal; // 'aprobar' o 'rechazar'

    // Filtros
    public $filtroEstado = ''; // Todos, Pendiente, Aprobada, Rechazada
    public $filtroTrabajador = ''; // ID del trabajador
    public $filtroFechaDesde = '';
    public $filtroFechaHasta = '';

    public $trabajadores;

    public function mount()
    {
        $this->trabajadores = Trabajador::orderBy('NombreCompleto')->get();
        $this->filtroEstado = 'Pendiente'; // Por defecto mostrar pendientes
    }

    public function abrirModalGestion($licenciaId, $accion)
    {
        $this->licenciaIdParaGestionar = $licenciaId;
        $this->accionModal = $accion;
        $this->comentariosAdmin = Licencia::find($licenciaId)->Comentarios_Admin ?? '';
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

        $licencia = Licencia::findOrFail($this->licenciaIdParaGestionar);

        if ($this->accionModal === 'aprobar') {
            $licencia->Estado_Solicitud = 'Aprobada';
        } elseif ($this->accionModal === 'rechazar') {
            $licencia->Estado_Solicitud = 'Rechazada';
        }

        $licencia->Aprobado_por = Auth::id();
        $licencia->Fecha_Aprobacion_Rechazo = Carbon::now();
        $licencia->Comentarios_Admin = $this->comentariosAdmin;
        $licencia->save();

        session()->flash('message', 'Solicitud de licencia ' . ($this->accionModal === 'aprobar' ? 'aprobada' : 'rechazada') . ' correctamente.');
        $this->dispatch('close-gestion-modal');
        $this->reset(['licenciaIdParaGestionar', 'comentariosAdmin', 'accionModal']);
    }
    
    public function getLicenciaDetailsForModal($id)
    {
        $licencia = Licencia::with(['trabajador:ID_Trabajador,NombreCompleto,Email', 'aprobador:id,name'])->find($id);
        if (!$licencia) {
            \Illuminate\Support\Facades\Log::warning("getLicenciaDetailsForModal: No se encontró Licencia con ID: " . $id);
            return null;
        }

        try {
            return [
                'id' => $licencia->ID_Licencia,
                'trabajador_nombre' => $licencia->trabajador ? $licencia->trabajador->NombreCompleto : 'N/A',
                'trabajador_email' => $licencia->trabajador ? $licencia->trabajador->Email : 'N/A',
                'tipo_licencia' => $licencia->TipoLicencia,
                'fecha_inicio_formatted' => $licencia->FechaInicio ? $licencia->FechaInicio->format('d/m/Y') : 'N/A',
                'fecha_fin_formatted' => $licencia->FechaFin ? $licencia->FechaFin->format('d/m/Y') : 'N/A',
                'cantidad_dias' => $licencia->CantidadDias,
                'motivo' => $licencia->Motivo,
                'certificado_url' => $licencia->Certificado ? Storage::url($licencia->Certificado) : null,
                'estado_solicitud' => $licencia->Estado_Solicitud,
                'fecha_solicitud_formatted' => $licencia->created_at ? $licencia->created_at->format('d/m/Y H:i') : 'N/A',
                'aprobado_por_nombre' => $licencia->aprobador ? $licencia->aprobador->name : null,
                'fecha_aprobacion_rechazo_formatted' => $licencia->Fecha_Aprobacion_Rechazo ? $licencia->Fecha_Aprobacion_Rechazo->format('d/m/Y H:i') : null,
                'comentarios_admin' => $licencia->Comentarios_Admin,
            ];
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error("Error en getLicenciaDetailsForModal para Licencia ID {$id}: " . $e->getMessage(), ['exception' => $e]);
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
        $query = Licencia::with('trabajador.user', 'aprobador'); // Cargar relaciones

        if ($this->filtroEstado) {
            $query->where('Estado_Solicitud', $this->filtroEstado);
        }

        if ($this->filtroTrabajador) {
            $query->where('ID_Empleado', $this->filtroTrabajador);
        }

        if ($this->filtroFechaDesde) {
            $query->whereDate('FechaInicio', '>=', $this->filtroFechaDesde);
        }

        if ($this->filtroFechaHasta) {
            $query->whereDate('FechaFin', '<=', $this->filtroFechaHasta);
        }

        $solicitudes = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('livewire.admin.licencias.requests', [
            'solicitudes' => $solicitudes,
        ])->layout('layouts.app');
    }
}
