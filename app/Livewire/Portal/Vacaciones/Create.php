<?php

namespace App\Livewire\Portal\Vacaciones;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Vacacion;
use App\Models\Trabajador;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NuevaSolicitudVacacion;

class Create extends Component
{
    public ?Trabajador $trabajador;
    public $showNoTrabajadorMessage = false;
    public $diasDisponiblesInfo = ''; // Para mostrar info de días

    // Campos del formulario
    public $fecha_inicio = '';
    public $fecha_fin = '';
    public $comentarios = ''; // Opcional, por si el usuario quiere añadir algo

    protected function rules()
    {
        return [
            'fecha_inicio' => 'required|date|after_or_equal:today',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
            'comentarios' => 'nullable|string|max:500',
        ];
    }

    protected $messages = [
        'fecha_inicio.after_or_equal' => 'La fecha de inicio no puede ser anterior a hoy.',
        'fecha_fin.after_or_equal' => 'La fecha de fin no puede ser anterior a la fecha de inicio.',
    ];

    public function mount()
    {
        $user = Auth::user();
        if ($user && $user->trabajador) {
            $this->trabajador = $user->trabajador;
            $this->actualizarInformacionDiasDisponibles();
        } else {
            $this->trabajador = null;
            $this->showNoTrabajadorMessage = true;
        }
        $this->fecha_inicio = today()->format('Y-m-d');
    }

    public function updatedFechaInicio() { $this->actualizarInformacionDiasDisponibles(); }
    public function updatedFechaFin() { $this->actualizarInformacionDiasDisponibles(); }

    private function actualizarInformacionDiasDisponibles() {
        if (!$this->trabajador) return;

        $anoActual = Carbon::now()->year;
        $resumen = $this->trabajador->resumenVacaciones($anoActual);

        $diasSolicitadosAhora = 0;
        if ($this->fecha_inicio && $this->fecha_fin) {
            try {
                $diasSolicitadosAhora = $this->calcularDiasLaborables($this->fecha_inicio, $this->fecha_fin);
            } catch (\Exception $e) {
                $diasSolicitadosAhora = 0; // En caso de fechas inválidas
            }
        }

        $this->diasDisponiblesInfo = sprintf(
            "Año %d: %d días asignados | %d utilizados | %d pendientes | %d disponibles | Solicitando ahora: %d días",
            $resumen['anio'],
            $resumen['dias_asignados'],
            $resumen['dias_utilizados'],
            $resumen['dias_pendientes'],
            $resumen['dias_libres'],
            $diasSolicitadosAhora
        );
    }
    
    private function calcularDiasLaborables($fechaInicio, $fechaFin)
    {
        $inicio = Carbon::parse($fechaInicio);
        $fin = Carbon::parse($fechaFin);
        if ($inicio->gt($fin)) return 0;

        // Contar todos los días en el rango, incluyendo inicio y fin
        $diasTotales = $inicio->diffInDays($fin) + 1; 
        $diasLaborables = 0;

        for ($i = 0; $i < $diasTotales; $i++) {
            $diaActual = $inicio->copy()->addDays($i);
            // Contar solo si NO es sábado ni domingo
            if (!$diaActual->isWeekend()) {
                $diasLaborables++;
            }
        }
        return $diasLaborables;
    }

    public function saveVacaciones()
    {
        if (!$this->trabajador) {
            session()->flash('error', 'No tienes un perfil de trabajador para solicitar vacaciones.');
            return redirect()->route('portal.vacaciones.index');
        }

        $this->validate();

        $diasSolicitados = $this->calcularDiasLaborables($this->fecha_inicio, $this->fecha_fin);

        if ($diasSolicitados <= 0) {
            $this->addError('fecha_fin', 'El rango de fechas no resulta en días laborables válidos o la fecha de fin es anterior a la de inicio.');
            $this->actualizarInformacionDiasDisponibles(); // Actualizar info con error
            return;
        }
        
        // Nueva validación de días disponibles usando los métodos del modelo
        $anoActual = Carbon::parse($this->fecha_inicio)->year; // Año de inicio de la solicitud actual
        $verificacion = $this->trabajador->puedesolicitarVacaciones($diasSolicitados, $anoActual);

        if (!$verificacion['puede_solicitar']) {
            $this->addError('fecha_fin', $verificacion['mensaje']);
            $this->actualizarInformacionDiasDisponibles(); // Actualizar info con error
            return;
        }

        $datosVacacion = [
            'ID_Trabajador' => $this->trabajador->ID_Trabajador,
            'Fecha_Inicio' => $this->fecha_inicio,
            'Fecha_Fin' => $this->fecha_fin,
            'Dias_Solicitados' => $diasSolicitados, // Calculado
            'Estado_Solicitud' => 'Pendiente',
            // 'Comentarios_Admin' es para el admin, no para el usuario al solicitar
        ];
         // Si se añade un campo de comentarios para el usuario:
        if(!empty($this->comentarios)) {
            // Supongamos que la tabla 'vacaciones' tiene un campo 'Comentarios_Usuario' o similar
            // $datosVacacion['Comentarios_Usuario'] = $this->comentarios; 
            // Por ahora, el modelo Vacacion no tiene Comentarios_Usuario, así que no lo guardamos.
            // Podríamos añadirlo a Comentarios_Admin si se quiere unificar, pero no es ideal.
        }

        $vacacion = Vacacion::create($datosVacacion);

        // Enviar notificaciones a todos los administradores
        $administradores = User::where('role', 'admin')->get();
        Notification::send($administradores, new NuevaSolicitudVacacion($vacacion));

        session()->flash('message', 'Solicitud de vacaciones enviada correctamente.');
        return redirect()->route('portal.vacaciones.index');
    }

    public function render()
    {
        return view('livewire.portal.vacaciones.create')->layout('layouts.app');
    }
}
