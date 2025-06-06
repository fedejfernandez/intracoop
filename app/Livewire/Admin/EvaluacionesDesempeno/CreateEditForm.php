<?php

namespace App\Livewire\Admin\EvaluacionesDesempeno;

use App\Models\EvaluacionDesempeno;
use App\Models\Trabajador;
use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CreateEditForm extends Component
{
    public $evaluacionId;
    public EvaluacionDesempeno $evaluacion;

    public $trabajadores;
    public $evaluadores;

    // Propiedades del modelo
    public $trabajador_id;
    public $evaluador_id;
    public $fecha_evaluacion;
    public $periodo_evaluado_inicio;
    public $periodo_evaluado_fin;
    public $calificacion_general;
    public $fortalezas = '';
    public $areas_mejora = '';
    public $comentarios_evaluador = '';
    public $comentarios_trabajador = '';
    public $estado = 'Borrador';


    protected function rules()
    {
        return [
            'trabajador_id' => 'required|exists:trabajadores,ID_Trabajador',
            'evaluador_id' => 'required|exists:users,id',
            'fecha_evaluacion' => 'required|date',
            'periodo_evaluado_inicio' => 'required|date',
            'periodo_evaluado_fin' => 'required|date|after_or_equal:periodo_evaluado_inicio',
            'calificacion_general' => 'nullable|numeric|min:0|max:10',
            'fortalezas' => 'nullable|string',
            'areas_mejora' => 'nullable|string',
            'comentarios_evaluador' => 'nullable|string',
            'comentarios_trabajador' => 'nullable|string',
            'estado' => 'required|in:Borrador,Publicada,Discutida,Cerrada',
        ];
    }

    public function mount($evaluacionId = null)
    {
        $this->trabajadores = Trabajador::orderBy('NombreCompleto')->get();
        // Por ahora, todos los usuarios pueden ser evaluadores. Se podría filtrar por rol si es necesario.
        $this->evaluadores = User::orderBy('name')->get();
        $this->evaluacionId = $evaluacionId;

        if ($this->evaluacionId) {
            $evaluacionToLoad = EvaluacionDesempeno::findOrFail($this->evaluacionId);
            $this->trabajador_id = $evaluacionToLoad->trabajador_id;
            $this->evaluador_id = $evaluacionToLoad->evaluador_id;
            // Asegurarse de que las fechas se formateen como YYYY-MM-DD para los inputs de tipo date
            $this->fecha_evaluacion = $evaluacionToLoad->fecha_evaluacion ? $evaluacionToLoad->fecha_evaluacion->format('Y-m-d') : null;
            $this->periodo_evaluado_inicio = $evaluacionToLoad->periodo_evaluado_inicio ? $evaluacionToLoad->periodo_evaluado_inicio->format('Y-m-d') : null;
            $this->periodo_evaluado_fin = $evaluacionToLoad->periodo_evaluado_fin ? $evaluacionToLoad->periodo_evaluado_fin->format('Y-m-d') : null;
            $this->calificacion_general = $evaluacionToLoad->calificacion_general;
            $this->fortalezas = $evaluacionToLoad->fortalezas;
            $this->areas_mejora = $evaluacionToLoad->areas_mejora;
            $this->comentarios_evaluador = $evaluacionToLoad->comentarios_evaluador;
            $this->comentarios_trabajador = $evaluacionToLoad->comentarios_trabajador;
            $this->estado = $evaluacionToLoad->estado;
        } else {
            // Valores por defecto para nueva evaluación
            $this->fecha_evaluacion = now()->format('Y-m-d');
            $this->evaluador_id = Auth::id(); // Asignar al usuario actual como evaluador por defecto
            $this->estado = 'Borrador';
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'trabajador_id' => $this->trabajador_id,
            'evaluador_id' => $this->evaluador_id,
            'fecha_evaluacion' => $this->fecha_evaluacion,
            'periodo_evaluado_inicio' => $this->periodo_evaluado_inicio,
            'periodo_evaluado_fin' => $this->periodo_evaluado_fin,
            'calificacion_general' => $this->calificacion_general,
            'fortalezas' => $this->fortalezas,
            'areas_mejora' => $this->areas_mejora,
            'comentarios_evaluador' => $this->comentarios_evaluador,
            'comentarios_trabajador' => $this->comentarios_trabajador,
            'estado' => $this->estado,
        ];

        if ($this->evaluacionId) {
            $evaluacion = EvaluacionDesempeno::findOrFail($this->evaluacionId);
            $evaluacion->update($data);
            session()->flash('success', 'Evaluación actualizada correctamente.');
        } else {
            EvaluacionDesempeno::create($data);
            session()->flash('success', 'Evaluación creada correctamente.');
        }

        return redirect()->route('admin.evaluaciones-desempeno.index');
    }

    public function render()
    {
        return view('livewire.admin.evaluaciones-desempeno.create-edit-form');
    }
}
