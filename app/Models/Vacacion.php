<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vacacion extends Model
{
    use HasFactory;

    protected $table = 'vacaciones'; // Especificar el nombre de la tabla ya que es plural
    protected $primaryKey = 'ID_Vacaciones'; // Especificar la PK correcta

    protected $fillable = [
        'ID_Trabajador',
        'Fecha_Inicio',
        'Fecha_Fin',
        'Dias_Solicitados',
        'Estado_Solicitud',
        'Aprobado_por',
        'Fecha_Aprobacion_Rechazo',
        'Comentarios_Admin'
    ];

    protected $casts = [
        'Fecha_Inicio' => 'date',
        'Fecha_Fin' => 'date',
        'Fecha_Aprobacion_Rechazo' => 'datetime',
    ];

    public function trabajador()
    {
        return $this->belongsTo(Trabajador::class, 'ID_Trabajador', 'ID_Trabajador');
    }

    public function aprobador()
    {
        return $this->belongsTo(User::class, 'Aprobado_por');
    }

    /**
     * Valida si hay conflictos con otras vacaciones para el mismo trabajador
     * 
     * @param int $trabajadorId
     * @param string $fechaInicio
     * @param string $fechaFin
     * @param int|null $vacacionId ID de la vacación actual (para excluir en ediciones)
     * @return array
     */
    public static function validarConflictosTrabajador($trabajadorId, $fechaInicio, $fechaFin, $vacacionId = null)
    {
        $conflictos = self::where('ID_Trabajador', $trabajadorId)
            ->where('Estado_Solicitud', '!=', 'Rechazada')
            ->where(function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('Fecha_Inicio', [$fechaInicio, $fechaFin])
                      ->orWhereBetween('Fecha_Fin', [$fechaInicio, $fechaFin])
                      ->orWhere(function ($q) use ($fechaInicio, $fechaFin) {
                          $q->where('Fecha_Inicio', '<=', $fechaInicio)
                            ->where('Fecha_Fin', '>=', $fechaFin);
                      });
            });

        if ($vacacionId) {
            $conflictos->where('ID_Vacaciones', '!=', $vacacionId);
        }

        $conflictosEncontrados = $conflictos->get();

        return [
            'hay_conflictos' => $conflictosEncontrados->count() > 0,
            'cantidad' => $conflictosEncontrados->count(),
            'conflictos' => $conflictosEncontrados,
            'mensaje' => $conflictosEncontrados->count() > 0 
                ? "El trabajador ya tiene {$conflictosEncontrados->count()} vacaciones programadas que se superponen con estas fechas."
                : 'No hay conflictos de fechas para este trabajador.'
        ];
    }

    /**
     * Valida si hay demasiadas vacaciones simultáneas en el mismo período
     * 
     * @param string $fechaInicio
     * @param string $fechaFin
     * @param float $porcentajeMaximo Porcentaje máximo de trabajadores en vacaciones (0.2 = 20%)
     * @param int|null $vacacionId
     * @return array
     */
    public static function validarConflictosCapacidad($fechaInicio, $fechaFin, $porcentajeMaximo = 0.2, $vacacionId = null)
    {
        $totalTrabajadores = \App\Models\Trabajador::where('Estado', 'Activo')->count();
        $limiteVacaciones = max(1, round($totalTrabajadores * $porcentajeMaximo));

        $vacacionesEnPeriodo = self::where('Estado_Solicitud', '!=', 'Rechazada')
            ->where(function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('Fecha_Inicio', [$fechaInicio, $fechaFin])
                      ->orWhereBetween('Fecha_Fin', [$fechaInicio, $fechaFin])
                      ->orWhere(function ($q) use ($fechaInicio, $fechaFin) {
                          $q->where('Fecha_Inicio', '<=', $fechaInicio)
                            ->where('Fecha_Fin', '>=', $fechaFin);
                      });
            });

        if ($vacacionId) {
            $vacacionesEnPeriodo->where('ID_Vacaciones', '!=', $vacacionId);
        }

        $cantidad = $vacacionesEnPeriodo->count();
        $supraCapacidad = $cantidad >= $limiteVacaciones;

        return [
            'supera_capacidad' => $supraCapacidad,
            'cantidad_actual' => $cantidad,
            'limite_recomendado' => $limiteVacaciones,
            'porcentaje_actual' => $totalTrabajadores > 0 ? round(($cantidad / $totalTrabajadores) * 100, 1) : 0,
            'porcentaje_limite' => $porcentajeMaximo * 100,
            'mensaje' => $supraCapacidad 
                ? "Advertencia: {$cantidad} trabajadores ya tienen vacaciones en este período ({$cantidad}/{$limiteVacaciones} recomendado)."
                : "Capacidad disponible: {$cantidad}/{$limiteVacaciones} trabajadores en vacaciones."
        ];
    }

    /**
     * Validación completa de una solicitud de vacaciones
     * 
     * @param int $trabajadorId
     * @param string $fechaInicio
     * @param string $fechaFin
     * @param int|null $vacacionId
     * @return array
     */
    public static function validarSolicitudCompleta($trabajadorId, $fechaInicio, $fechaFin, $vacacionId = null)
    {
        $conflictosTrabajador = self::validarConflictosTrabajador($trabajadorId, $fechaInicio, $fechaFin, $vacacionId);
        $conflictosCapacidad = self::validarConflictosCapacidad($fechaInicio, $fechaFin, 0.2, $vacacionId);

        $tieneErrores = $conflictosTrabajador['hay_conflictos'];
        $tieneAdvertencias = $conflictosCapacidad['supera_capacidad'];

        return [
            'es_valida' => !$tieneErrores,
            'tiene_advertencias' => $tieneAdvertencias,
            'conflictos_trabajador' => $conflictosTrabajador,
            'conflictos_capacidad' => $conflictosCapacidad,
            'resumen' => [
                'errores' => $tieneErrores ? [$conflictosTrabajador['mensaje']] : [],
                'advertencias' => $tieneAdvertencias ? [$conflictosCapacidad['mensaje']] : [],
            ]
        ];
    }
} 