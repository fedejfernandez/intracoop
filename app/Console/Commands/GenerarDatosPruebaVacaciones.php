<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Trabajador;
use App\Models\Vacacion;
use Carbon\Carbon;

class GenerarDatosPruebaVacaciones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'datos:generar-vacaciones {--trabajadores=5 : Cantidad de trabajadores a usar} {--anio= : Año para las vacaciones (por defecto año actual)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera datos de prueba de vacaciones para trabajadores';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $cantidadTrabajadores = $this->option('trabajadores');
        $anio = $this->option('anio') ?? date('Y');
        
        $this->info("Generando datos de prueba de vacaciones para {$cantidadTrabajadores} trabajadores en el año {$anio}...");
        
        $trabajadores = Trabajador::take($cantidadTrabajadores)->get();
        
        if ($trabajadores->isEmpty()) {
            $this->error('No hay trabajadores en la base de datos.');
            return 1;
        }
        
        $estados = ['Pendiente', 'Aprobada', 'Rechazada'];
        $vacacionesCreadas = 0;
        
        $progressBar = $this->output->createProgressBar($trabajadores->count());
        $progressBar->start();
        
        foreach ($trabajadores as $trabajador) {
            // Generar entre 1 y 3 solicitudes de vacaciones por trabajador
            $cantidadSolicitudes = rand(1, 3);
            
            for ($i = 0; $i < $cantidadSolicitudes; $i++) {
                $fechaInicio = $this->generarFechaAleatoria($anio);
                $diasDuracion = rand(3, 15); // Entre 3 y 15 días de vacaciones
                $fechaFin = $fechaInicio->copy()->addDays($diasDuracion - 1);
                
                // Calcular días laborables
                $diasLaborables = $this->calcularDiasLaborables($fechaInicio, $fechaFin);
                
                // Estado aleatorio con mayor probabilidad de ser aprobada
                $estadoProbabilidades = [
                    'Aprobada' => 60,
                    'Pendiente' => 25, 
                    'Rechazada' => 15
                ];
                $estado = $this->seleccionarEstadoAleatorio($estadoProbabilidades);
                
                $vacacion = Vacacion::create([
                    'ID_Trabajador' => $trabajador->ID_Trabajador,
                    'Fecha_Inicio' => $fechaInicio,
                    'Fecha_Fin' => $fechaFin,
                    'Dias_Solicitados' => $diasLaborables,
                    'Estado_Solicitud' => $estado,
                    'Comentarios_Admin' => $this->generarComentarioAleatorio($estado),
                    'Aprobado_por' => $estado !== 'Pendiente' ? 1 : null, // Asumiendo que existe user con ID 1
                    'Fecha_Aprobacion_Rechazo' => $estado !== 'Pendiente' ? $fechaInicio->copy()->subDays(rand(1, 7)) : null,
                    'created_at' => $fechaInicio->copy()->subDays(rand(7, 30)),
                    'updated_at' => now()
                ]);
                
                $vacacionesCreadas++;
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine(2);
        
        $this->info("✅ Se crearon {$vacacionesCreadas} solicitudes de vacaciones para {$trabajadores->count()} trabajadores.");
        
        // Mostrar estadísticas por estado
        $estadisticas = Vacacion::selectRaw('Estado_Solicitud, COUNT(*) as cantidad')
            ->whereYear('Fecha_Inicio', $anio)
            ->groupBy('Estado_Solicitud')
            ->get();
            
        $this->table(
            ['Estado', 'Cantidad'],
            $estadisticas->map(function ($item) {
                return [$item->Estado_Solicitud, $item->cantidad];
            })->toArray()
        );
        
        return 0;
    }
    
    private function generarFechaAleatoria($anio)
    {
        $inicio = Carbon::createFromDate($anio, 1, 1);
        $fin = Carbon::createFromDate($anio, 12, 31);
        
        $diasEnAnio = $inicio->diffInDays($fin);
        $diaAleatorio = rand(0, $diasEnAnio);
        
        return $inicio->copy()->addDays($diaAleatorio);
    }
    
    private function calcularDiasLaborables($fechaInicio, $fechaFin)
    {
        $diasTotales = $fechaInicio->diffInDays($fechaFin) + 1;
        $diasLaborables = 0;

        for ($i = 0; $i < $diasTotales; $i++) {
            $diaActual = $fechaInicio->copy()->addDays($i);
            if (!$diaActual->isWeekend()) {
                $diasLaborables++;
            }
        }
        
        return $diasLaborables;
    }
    
    private function seleccionarEstadoAleatorio($probabilidades)
    {
        $rand = rand(1, 100);
        $acumulado = 0;
        
        foreach ($probabilidades as $estado => $probabilidad) {
            $acumulado += $probabilidad;
            if ($rand <= $acumulado) {
                return $estado;
            }
        }
        
        return 'Pendiente'; // Fallback
    }
    
    private function generarComentarioAleatorio($estado)
    {
        $comentarios = [
            'Aprobada' => [
                'Vacaciones aprobadas. Que disfrute su descanso.',
                'Solicitud aprobada sin observaciones.',
                'Aprobado. Buen descanso.',
                'Vacaciones autorizadas.',
                ''
            ],
            'Rechazada' => [
                'Rechazada por superposición con otros trabajadores.',
                'No disponible por alta demanda en esas fechas.',
                'Rechazada. Presentar nueva solicitud.',
                'Período no disponible por motivos operacionales.'
            ],
            'Pendiente' => [
                '',
                'En revisión.',
                'Pendiente de aprobación.'
            ]
        ];
        
        $opciones = $comentarios[$estado] ?? [''];
        return $opciones[array_rand($opciones)];
    }
} 