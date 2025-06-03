<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Licencia extends Model
{

    use HasFactory;
    protected $primaryKey = 'ID_Licencia';

    protected $fillable = [
        'ID_Empleado', 'TipoLicencia', 'FechaInicio', 'FechaFin',
        'CantidadDias', 'Motivo', 'Certificado', 'Estado_Solicitud',
        'Aprobado_por', 'Fecha_Aprobacion_Rechazo', 'Comentarios_Admin'
    ];

    protected $casts = [
        'FechaInicio' => 'date',
        'FechaFin' => 'date',
        'Fecha_Aprobacion_Rechazo' => 'datetime',
    ];

    public function trabajador()
    {
        return $this->belongsTo(Trabajador::class, 'ID_Empleado', 'ID_Trabajador');
    }

    public function aprobador()
    {
        return $this->belongsTo(User::class, 'Aprobado_por');
    }
}

