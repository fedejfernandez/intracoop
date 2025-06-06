<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistorialLaboral extends Model
{
    use HasFactory;

    protected $table = 'historial_laboral';

    protected $fillable = [
        'trabajador_id',
        'registrado_por_usuario_id',
        'fecha_evento',
        'tipo_evento',
        'descripcion',
        'puesto_anterior',
        'puesto_nuevo',
        'sector_anterior',
        'sector_nuevo',
        'categoria_anterior',
        'categoria_nueva',
        'salario_anterior',
        'salario_nuevo',
        'observaciones',
    ];

    protected $casts = [
        'fecha_evento' => 'date',
        'salario_anterior' => 'decimal:2',
        'salario_nuevo' => 'decimal:2',
    ];

    /**
     * Obtiene el trabajador asociado a este registro de historial.
     */
    public function trabajador()
    {
        return $this->belongsTo(Trabajador::class, 'trabajador_id', 'ID_Trabajador');
    }

    /**
     * Obtiene el usuario que registró este evento.
     */
    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por_usuario_id');
    }
}
