<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    /**
     * Eventos del modelo para actualizar automáticamente los días de vacaciones
     */
    protected static function booted()
    {
        static::saving(function ($trabajador) {
            // Actualizar días de vacaciones automáticamente al guardar
            if ($trabajador->FechaIngreso) {
                $trabajador->DiasVacacionesAnuales = $trabajador->calcularDiasVacacionesAnuales();
            }
        });
    }

    /**
     * Calcula los días de vacaciones anuales según la antigüedad y CCT
     * 
     * @param int|null $anio Año para el cual calcular (por defecto año actual)
     * @return int Días de vacaciones que corresponden
     */
    public function calcularDiasVacacionesAnuales($anio = null)
    {
        if (!$this->FechaIngreso) {
            return 14; // Valor por defecto
        }

        $anio = $anio ?? date('Y');
        $fechaReferencia = Carbon::createFromDate($anio, 12, 31); // 31 de diciembre del año
        
        // Calcular antigüedad en años hasta el 31/12 del año
        $antiguedadAnios = $this->FechaIngreso->diffInYears($fechaReferencia);

        return $this->obtenerDiasVacacionesPorAntiguedad($antiguedadAnios);
    }

    /**
     * Calcula los días de vacaciones según la antigüedad y el CCT
     * 
     * @param int $antiguedadAnios
     * @return int
     */
    private function obtenerDiasVacacionesPorAntiguedad($antiguedadAnios)
    {
        // Escalas según el CCT (actualmente todos usan la escala estándar)
        $escalasVacaciones = $this->obtenerEscalaVacacionesPorCCT();

        foreach ($escalasVacaciones as $rango) {
            if ($antiguedadAnios >= $rango['desde'] && 
                ($rango['hasta'] === null || $antiguedadAnios <= $rango['hasta'])) {
                return $rango['dias'];
            }
        }

        // Por defecto, si no encuentra el rango
        return 14;
    }

    /**
     * Obtiene la escala de vacaciones según el CCT del trabajador
     * 
     * @return array
     */
    private function obtenerEscalaVacacionesPorCCT()
    {
        // Escalas por CCT - Actualmente todos usan la escala estándar
        $escalasEstandar = [
            ['desde' => 0, 'hasta' => 5, 'dias' => 14],    // Hasta 5 años
            ['desde' => 6, 'hasta' => 10, 'dias' => 21],   // Más de 5 y hasta 10 años  
            ['desde' => 11, 'hasta' => 20, 'dias' => 28],  // Más de 10 y hasta 20 años
            ['desde' => 21, 'hasta' => null, 'dias' => 35] // Más de 20 años
        ];

        // Escalas específicas por CCT (expandir en el futuro si es necesario)
        $escalasPorCCT = [
            '36/75' => $escalasEstandar,
            '459/06' => $escalasEstandar,
            '177/75' => $escalasEstandar,
            '107/75' => $escalasEstandar,
        ];

        // Devolver escala específica del CCT o la estándar por defecto
        return $escalasPorCCT[$this->CCT] ?? $escalasEstandar;
    }

    /**
     * Método público para obtener los días de vacaciones calculados dinámicamente
     * 
     * @param int|null $anio
     * @return int
     */
    public function diasVacacionesCalculados($anio = null)
    {
        return $this->calcularDiasVacacionesAnuales($anio);
    }

    /**
     * Obtiene la antigüedad en años al 31 de diciembre del año especificado
     * 
     * @param int|null $anio
     * @return int
     */
    public function obtenerAntiguedadEnAnios($anio = null)
    {
        if (!$this->FechaIngreso) {
            return 0;
        }

        $anio = $anio ?? date('Y');
        $fechaReferencia = Carbon::createFromDate($anio, 12, 31);
        
        return $this->FechaIngreso->diffInYears($fechaReferencia);
    }

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

    /**
     * Calcula los días de vacaciones utilizados en un año específico
     * 
     * @param int|null $anio
     * @return int
     */
    public function diasVacacionesUtilizados($anio = null)
    {
        $anio = $anio ?? date('Y');
        
        return $this->vacaciones()
            ->where('Estado_Solicitud', 'Aprobada')
            ->whereYear('Fecha_Inicio', $anio)
            ->sum('Dias_Solicitados');
    }

    /**
     * Calcula los días de vacaciones pendientes de aprobación en un año específico
     * 
     * @param int|null $anio
     * @return int
     */
    public function diasVacacionesPendientes($anio = null)
    {
        $anio = $anio ?? date('Y');
        
        return $this->vacaciones()
            ->where('Estado_Solicitud', 'Pendiente')
            ->whereYear('Fecha_Inicio', $anio)
            ->sum('Dias_Solicitados');
    }

    /**
     * Calcula los días de vacaciones disponibles (sin usar) en un año específico
     * 
     * @param int|null $anio
     * @return int
     */
    public function diasVacacionesDisponibles($anio = null)
    {
        $anio = $anio ?? date('Y');
        $diasAsignados = $this->calcularDiasVacacionesAnuales($anio);
        $diasUtilizados = $this->diasVacacionesUtilizados($anio);
        
        return max(0, $diasAsignados - $diasUtilizados);
    }

    /**
     * Calcula los días de vacaciones realmente disponibles considerando pendientes
     * 
     * @param int|null $anio
     * @return int
     */
    public function diasVacacionesLibres($anio = null)
    {
        $anio = $anio ?? date('Y');
        $diasDisponibles = $this->diasVacacionesDisponibles($anio);
        $diasPendientes = $this->diasVacacionesPendientes($anio);
        
        return max(0, $diasDisponibles - $diasPendientes);
    }

    /**
     * Obtiene un resumen completo del estado de vacaciones para un año
     * 
     * @param int|null $anio
     * @return array
     */
    public function resumenVacaciones($anio = null)
    {
        $anio = $anio ?? date('Y');
        
        return [
            'anio' => $anio,
            'dias_asignados' => $this->calcularDiasVacacionesAnuales($anio),
            'dias_utilizados' => $this->diasVacacionesUtilizados($anio),
            'dias_pendientes' => $this->diasVacacionesPendientes($anio),
            'dias_disponibles' => $this->diasVacacionesDisponibles($anio),
            'dias_libres' => $this->diasVacacionesLibres($anio),
            'porcentaje_utilizado' => $this->calcularPorcentajeUtilizado($anio),
        ];
    }

    /**
     * Calcula el porcentaje de días de vacaciones utilizados
     * 
     * @param int|null $anio
     * @return float
     */
    public function calcularPorcentajeUtilizado($anio = null)
    {
        $anio = $anio ?? date('Y');
        $diasAsignados = $this->calcularDiasVacacionesAnuales($anio);
        $diasUtilizados = $this->diasVacacionesUtilizados($anio);
        
        if ($diasAsignados == 0) {
            return 0;
        }
        
        return round(($diasUtilizados / $diasAsignados) * 100, 1);
    }

    /**
     * Verifica si puede solicitar una cantidad específica de días
     * 
     * @param int $diasSolicitados
     * @param int|null $anio
     * @return array ['puede_solicitar' => bool, 'mensaje' => string]
     */
    public function puedesolicitarVacaciones($diasSolicitados, $anio = null)
    {
        $anio = $anio ?? date('Y');
        $diasLibres = $this->diasVacacionesLibres($anio);
        
        if ($diasSolicitados <= 0) {
            return [
                'puede_solicitar' => false,
                'mensaje' => 'La cantidad de días debe ser mayor a 0.'
            ];
        }
        
        if ($diasSolicitados > $diasLibres) {
            $resumen = $this->resumenVacaciones($anio);
            return [
                'puede_solicitar' => false,
                'mensaje' => "No puedes solicitar {$diasSolicitados} días. Solo tienes {$diasLibres} días disponibles. (Asignados: {$resumen['dias_asignados']}, Utilizados: {$resumen['dias_utilizados']}, Pendientes: {$resumen['dias_pendientes']})"
            ];
        }
        
        return [
            'puede_solicitar' => true,
            'mensaje' => "Puedes solicitar {$diasSolicitados} días. Te quedarán " . ($diasLibres - $diasSolicitados) . " días disponibles."
        ];
    }

    /**
     * Obtiene las vacaciones del año actual ordenadas por fecha
     * 
     * @param int|null $anio
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function vacacionesDelAnio($anio = null)
    {
        $anio = $anio ?? date('Y');
        
        return $this->vacaciones()
            ->whereYear('Fecha_Inicio', $anio)
            ->orderBy('Fecha_Inicio', 'asc')
            ->get();
    }

    /**
     * Obtiene las próximas vacaciones aprobadas
     * 
     * @param int $limite
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function proximasVacaciones($limite = 5)
    {
        return $this->vacaciones()
            ->where('Estado_Solicitud', 'Aprobada')
            ->where('Fecha_Inicio', '>=', now())
            ->orderBy('Fecha_Inicio', 'asc')
            ->limit($limite)
            ->get();
    }

    /**
     * Define la relación con el historial laboral del trabajador.
     */
    public function historialLaboral()
    {
        return $this->hasMany(HistorialLaboral::class, 'trabajador_id', 'ID_Trabajador');
    }

    /**
     * Obtiene todas las evaluaciones de desempeño asociadas a este trabajador.
     */
    public function evaluacionesDesempeno()
    {
        return $this->hasMany(EvaluacionDesempeno::class, 'trabajador_id', 'ID_Trabajador');
    }
}
