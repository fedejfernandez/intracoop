<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Trabajador;

class RecalcularDiasVacaciones extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trabajadores:recalcular-vacaciones {--anio= : Año para el cálculo (por defecto año actual)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalcula los días de vacaciones anuales de todos los trabajadores según su antigüedad y CCT';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $anio = $this->option('anio') ?? date('Y');
        
        $this->info("Recalculando días de vacaciones para el año {$anio}...");
        
        $trabajadores = Trabajador::whereNotNull('FechaIngreso')->get();
        
        if ($trabajadores->isEmpty()) {
            $this->warn('No se encontraron trabajadores con fecha de ingreso.');
            return 0;
        }
        
        $actualizados = 0;
        $errores = 0;
        
        $progressBar = $this->output->createProgressBar($trabajadores->count());
        $progressBar->start();
        
        foreach ($trabajadores as $trabajador) {
            try {
                $diasAnteriores = $trabajador->DiasVacacionesAnuales;
                $diasCalculados = $trabajador->calcularDiasVacacionesAnuales($anio);
                
                // Solo actualizar si cambió el valor
                if ($diasAnteriores != $diasCalculados) {
                    $trabajador->DiasVacacionesAnuales = $diasCalculados;
                    $trabajador->saveQuietly(); // Usar saveQuietly para evitar el evento saving
                    
                    $this->newLine();
                    $this->line("✓ {$trabajador->NombreCompleto} ({$trabajador->NumeroLegajo}): {$diasAnteriores} → {$diasCalculados} días");
                }
                
                $actualizados++;
            } catch (\Exception $e) {
                $errores++;
                $this->newLine();
                $this->error("✗ Error en {$trabajador->NombreCompleto}: " . $e->getMessage());
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine(2);
        
        $this->info("Proceso completado:");
        $this->table(
            ['Estadística', 'Cantidad'],
            [
                ['Trabajadores procesados', $trabajadores->count()],
                ['Actualizados correctamente', $actualizados],
                ['Errores', $errores],
                ['Año de cálculo', $anio]
            ]
        );
        
        if ($errores === 0) {
            $this->info('🎉 Todos los días de vacaciones han sido recalculados exitosamente.');
        } else {
            $this->warn("⚠️  Se completó con {$errores} errores.");
        }
        
        return 0;
    }
}
