<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trabajador extends Model
{
    use HasFactory;

    protected $table = 'trabajadores'; // Especificar explícitamente el nombre de la tabla

    protected $primaryKey = 'ID_Trabajador'; // Si no usas 'id' como PK

    protected $fillable = [
        'user_id', 'NumeroLegajo', 'NombreCompleto', 'DNI_CUIL', 'TipoDocumento', 'FechaNacimiento',
        'Nacionalidad', 'EstadoCivil', 'Sexo',
        'FechaIngreso', 'FechaReconocida', 'Puesto', 'Categoria', 'Sector', 'CCT',
        'Email', 'Banco', 'NroCuentaBancaria', 'CBU', 'DatosAdicBco',
        'Telefono', 'Direccion', 'Localidad', 'Provincia', 'CodigoPostal',
        'Foto', 'Estado', 'DiasVacacionesAnuales'
    ];

    protected $casts = [
        'FechaNacimiento' => 'date',
        'FechaIngreso' => 'date',
        'FechaReconocida' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function licencias()
    {
        return $this->hasMany(Licencia::class, 'ID_Empleado');
    }

    public function vacaciones()
    {
        return $this->hasMany(Vacacion::class, 'ID_Trabajador');
    }
}
