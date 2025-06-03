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
} 