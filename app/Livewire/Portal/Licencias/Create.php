<?php

namespace App\Livewire\Portal\Licencias;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Licencia;
use App\Models\Trabajador;
use Livewire\WithFileUploads; // Para subir el certificado
use Carbon\Carbon;

class Create extends Component
{
    use WithFileUploads;

    public ?Trabajador $trabajador;
    public $showNoTrabajadorMessage = false;

    // Campos del formulario
    public $tipo_licencia = '';
    public $fecha_inicio = '';
    public $fecha_fin = '';
    public $motivo = '';
    public $certificado = null; // Para el archivo

    // Tipos de licencia (podría venir de una tabla o config más adelante)
    public $tiposDeLicenciaPermitidos = [
        'Enfermedad Inculpable',
        'Examen',
        'Maternidad',
        'Paternidad',
        'Matrimonio',
        'Fallecimiento Familiar Directo',
        'Mudanza',
        'Donación de Sangre',
        'Licencia Gremial',
        'Cuidado de Familiar Enfermo',
        'Otro (especificar en motivo)'
    ];

    protected function rules()
    {
        return [
            'tipo_licencia' => 'required|string|in:' . implode(',', $this->tiposDeLicenciaPermitidos),
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'motivo' => 'required|string|min:10|max:1000',
            'certificado' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // Max 5MB
        ];
    }

    protected $messages = [
        'fecha_inicio.after_or_equal' => 'La fecha de inicio no puede ser anterior a hoy.',
        'fecha_fin.after_or_equal' => 'La fecha de fin no puede ser anterior a la fecha de inicio.',
        'tipo_licencia.in' => 'El tipo de licencia seleccionado no es válido.',
    ];

    public function mount()
    {
        $user = Auth::user();
        if ($user && $user->trabajador) {
            $this->trabajador = $user->trabajador;
        } else {
            $this->trabajador = null;
            $this->showNoTrabajadorMessage = true;
        }
        $this->fecha_inicio = today()->format('Y-m-d');
    }

    public function saveLicencia()
    {
        if (!$this->trabajador) {
            session()->flash('error', 'No tienes un perfil de trabajador para solicitar licencias.');
            return redirect()->route('portal.licencias.index');
        }

        $this->validate();

        $fechaInicio = Carbon::parse($this->fecha_inicio);
        $fechaFin = Carbon::parse($this->fecha_fin);
        $cantidadDias = $fechaInicio->diffInDaysFiltered(function(Carbon $date) {
            return !$date->isWeekend(); // No contar fines de semana, ajustar si es necesario
        }, $fechaFin) + 1; // +1 para incluir el día de inicio
        
        // Si la diferencia es 0 (mismo día), y no es fin de semana, es 1 día.
        if ($fechaInicio->isSameDay($fechaFin) && !$fechaInicio->isWeekend()) {
            $cantidadDias = 1;
        } elseif ($fechaInicio->isSameDay($fechaFin) && $fechaInicio->isWeekend()) {
             // Si es el mismo día y es fin de semana, no debería permitirse o contar como 0.
             // O ajustar la lógica de diffInDaysFiltered si se deben contar fines de semana para ciertos tipos de licencia.
             // Por ahora, si se elige un solo día de fin de semana, saldrá 0.
             // Para evitar esto, podríamos validar que si es un solo día, no sea fin de semana.
             // O simplemente dejar que $cantidadDias sea 0 y el admin lo revise.
        }


        $datosLicencia = [
            'ID_Empleado' => $this->trabajador->ID_Trabajador,
            'TipoLicencia' => $this->tipo_licencia,
            'FechaInicio' => $this->fecha_inicio,
            'FechaFin' => $this->fecha_fin,
            'CantidadDias' => $cantidadDias, // Calculado
            'Motivo' => $this->motivo,
            'Estado_Solicitud' => 'Pendiente',
        ];

        if ($this->certificado) {
            $datosLicencia['Certificado'] = $this->certificado->store('certificados_licencias', 'public');
        }

        Licencia::create($datosLicencia);

        session()->flash('message', 'Solicitud de licencia enviada correctamente.');
        return redirect()->route('portal.licencias.index');
    }

    public function render()
    {
        return view('livewire.portal.licencias.create')->layout('layouts.app');
    }
}
