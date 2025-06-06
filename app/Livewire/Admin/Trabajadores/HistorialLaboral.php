<?php

namespace App\Livewire\Admin\Trabajadores;

use App\Models\Trabajador as EloquentTrabajador;
use Illuminate\Support\Facades\Log;
// use App\Models\HistorialLaboral as HistorialLaboralModel; // No necesario en esta versión simplificada
// use App\Models\User; // No necesario en esta versión simplificada
// use Illuminate\Support\Facades\Auth; // No necesario en esta versión simplificada
use Livewire\Component;
// use Livewire\WithPagination; // No necesario en esta versión simplificada

class HistorialLaboral extends Component
{
    // use WithPagination; // No necesario en esta versión simplificada

    // public Trabajador $trabajador; // Comentado temporalmente
    public ?EloquentTrabajador $trabajadorInstance = null; // Usar un nombre diferente para la propiedad
    public $historialItems; 
    public $tiposEventoComunes = [];

    // Propiedades para el modal de agregar/editar
    public $showAddModal = false;
    public $historialId = null;
    public $tipo_evento = '';
    public $fecha_evento;
    public $descripcion = '';
    public $documento_adjunto; // Para carga de archivos, manejo pendiente
    public $salario_anterior;
    public $salario_nuevo;
    public $puesto_anterior = '';
    public $puesto_nuevo = '';
    public $sector_anterior = '';
    public $sector_nuevo = '';
    public $categoria_anterior = '';
    public $categoria_nueva = '';
    public $observaciones = '';
    
    // Propiedades para controlar la visibilidad de campos específicos del formulario
    public $showSalarioFields = false;
    public $showPuestoFields = false;
    public $showSectorFields = false;
    public $showCategoriaFields = false;

    // Rules no son necesarias si no hay formulario
    // protected $rules = [ ... ];

    // public function mount(Trabajador $trabajador) // Comentado temporalmente
    // {
    //     $this->trabajador = $trabajador;
    //     // $this->loadHistorial(); // Comentado para depuración
    //     $this->historialItems = collect(); // Inicializar como colección vacía para evitar errores en la vista
    //     $this->fecha_evento = now()->format('Y-m-d'); // Pre-llenar con fecha actual
    // }

    // Método mount alternativo para pruebas sin inyección de modelo
    public function mount($trabajadorId = null)
    {
        Log::info('[HistorialLaboral] Mount - ID de trabajador recibido:', [$trabajadorId]);

        if ($trabajadorId) {
            $this->trabajadorInstance = EloquentTrabajador::find($trabajadorId);
            Log::info('[HistorialLaboral] Mount - Resultado de EloquentTrabajador::find():', [$this->trabajadorInstance ? $this->trabajadorInstance->toArray() : null]);
        } else {
            Log::warning('[HistorialLaboral] Mount - No se recibió ID de trabajador.');
        }
        $this->historialItems = collect(); // Inicializar como colección vacía
        $this->tiposEventoComunes = $this->getTiposEventoComunesPredeterminados();
        $this->fecha_evento = now()->format('Y-m-d'); // Inicializar fecha
    }

    public function abrirModalDeHistorial()
    {
        $this->resetForm();
        if ($this->trabajadorInstance) {
            $this->puesto_anterior = $this->trabajadorInstance->Puesto;
            $this->sector_anterior = $this->trabajadorInstance->Sector;
            $this->categoria_anterior = $this->trabajadorInstance->Categoria;
            // Podrías añadir aquí el salario actual si tienes un campo para ello en el trabajador
            // $this->salario_anterior = $this->trabajadorInstance->SalarioActual; 
        }
        $this->showAddModal = true;
    }

    public function closeAddModal()
    {
        $this->showAddModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->historialId = null;
        $this->tipo_evento = '';
        $this->fecha_evento = now()->format('Y-m-d');
        $this->descripcion = '';
        $this->documento_adjunto = null;
        $this->salario_anterior = null;
        $this->salario_nuevo = null;
        $this->puesto_anterior = '';
        $this->puesto_nuevo = '';
        $this->sector_anterior = '';
        $this->sector_nuevo = '';
        $this->categoria_anterior = '';
        $this->categoria_nueva = '';
        $this->observaciones = '';
        $this->updatedTipoEvento(''); // Para resetear la visibilidad de campos
    }
    
    public function updatedTipoEvento($value)
    {
        $this->showSalarioFields = $value === 'Cambio de Salario';
        $this->showPuestoFields = $value === 'Cambio de Puesto';
        $this->showSectorFields = $value === 'Cambio de Sector';
        $this->showCategoriaFields = $value === 'Cambio de Categoría';
    }

    // Todos los métodos excepto mount y render comentados
    // public function loadHistorial() { ... }
    // public function openAddModal() { ... }
    // public function closeAddModal() { ... }
    // public function resetForm() { ... }
    // public function saveHistorial() { ... }
    // public function getTiposEventoComunes() { ... }

    // Método auxiliar para tipos de evento comunes (para evitar errores si se descomenta el modal)
    private function getTiposEventoComunesPredeterminados(): array
    {
        return [
            'Alta de Trabajador',
            'Baja de Trabajador',
            'Cambio de Puesto',
            'Cambio de Sector',
            'Cambio de Salario',
            'Cambio de Categoría',
            'Inicio de Licencia',
            'Fin de Licencia',
            'Inicio de Vacaciones',
            'Fin de Vacaciones',
            'Evaluación de Desempeño',
            'Capacitación Realizada',
            'Sanción Disciplinaria',
            'Reconocimiento/Premio',
            'Otros Eventos Contractuales',
            'Actualización de Datos Personales',
            'Actualización Domicilio',
            'Accidente de Trabajo',
            'Otro',
        ];
    }

    public function render()
    {
        return view('livewire.admin.trabajadores.historial-laboral', [
            'trabajador' => $this->trabajadorInstance, // Asegurarse de pasar esto con el nombre que espera la vista
            'historial' => $this->historialItems,      // Aunque esté vacío, la vista podría esperarlo
            'tiposEventoComunes' => $this->tiposEventoComunes, // Pasar los tipos de evento
        ])->layout('layouts.app');
    }
} 