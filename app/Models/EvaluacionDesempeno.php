<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EvaluacionDesempeno extends Model
{
    use HasFactory;

    protected $table = 'evaluaciones_desempeno';

    protected $fillable = [
        'trabajador_id',
        'evaluador_id',
        'fecha_evaluacion',
        'periodo_evaluado_inicio',
        'periodo_evaluado_fin',
        'calificacion_general',
        'fortalezas',
        'areas_mejora',
        'comentarios_evaluador',
        'comentarios_trabajador',
        'estado',
    ];

    protected $casts = [
        'fecha_evaluacion' => 'date',
        'periodo_evaluado_inicio' => 'date',
        'periodo_evaluado_fin' => 'date',
    ];

    /**
     * Obtiene el trabajador asociado a la evaluación.
     */
    public function trabajador(): BelongsTo
    {
        return $this->belongsTo(Trabajador::class, 'trabajador_id', 'ID_Trabajador');
    }

    /**
     * Obtiene el usuario (evaluador) que realizó la evaluación.
     */
    public function evaluador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluador_id');
    }
}
