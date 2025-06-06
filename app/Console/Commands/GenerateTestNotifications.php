<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Vacacion;
use App\Models\Licencia;
use App\Notifications\NuevaSolicitudVacacion;
use App\Notifications\VacacionAprobada;
use App\Notifications\VacacionRechazada;
use App\Notifications\NuevaSolicitudLicencia;
use App\Notifications\LicenciaAprobada;
use App\Notifications\LicenciaRechazada;
use Illuminate\Support\Facades\Notification;

class GenerateTestNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:test {user_id?} {--type=all}';

    /**
     * The console description of the command.
     *
     * @var string
     */
    protected $description = 'Genera notificaciones de prueba para testear el sistema';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');
        $type = $this->option('type');

        // Obtener usuario
        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("Usuario con ID {$userId} no encontrado.");
                return 1;
            }
        } else {
            $user = User::first();
            if (!$user) {
                $this->error("No hay usuarios en el sistema.");
                return 1;
            }
        }

        $this->info("Generando notificaciones de prueba para: {$user->name} (ID: {$user->id})");

        // Crear datos de prueba si no existen
        $vacacion = $this->getOrCreateTestVacacion();
        $licencia = $this->getOrCreateTestLicencia();

        // Generar notificaciones según el tipo
        $notifications = [];
        
        if (($type === 'all' || $type === 'vacaciones') && $vacacion) {
            $notifications[] = ['Nueva Solicitud Vacación', new NuevaSolicitudVacacion($vacacion)];
            $notifications[] = ['Vacación Aprobada', new VacacionAprobada($vacacion)];
            $notifications[] = ['Vacación Rechazada', new VacacionRechazada($vacacion)];
        }

        if (($type === 'all' || $type === 'licencias') && $licencia) {
            $notifications[] = ['Nueva Solicitud Licencia', new NuevaSolicitudLicencia($licencia)];
            $notifications[] = ['Licencia Aprobada', new LicenciaAprobada($licencia)];
            $notifications[] = ['Licencia Rechazada', new LicenciaRechazada($licencia)];
        }

        if (empty($notifications)) {
            $this->warn("No se pudieron generar notificaciones. Verifica que existan datos de prueba.");
            return 1;
        }

        // Enviar notificaciones
        foreach ($notifications as [$name, $notification]) {
            try {
                $user->notify($notification);
                $this->info("✓ Enviada: {$name}");
            } catch (\Exception $e) {
                $this->error("✗ Error enviando {$name}: " . $e->getMessage());
            }
        }

        $this->info("\n🎉 Notificaciones de prueba generadas exitosamente!");
        $this->info("Revisa el centro de notificaciones en la aplicación.");
        
        return 0;
    }

    private function getOrCreateTestVacacion()
    {
        // Buscar una vacación existente o crear una de prueba
        $vacacion = Vacacion::with('trabajador')->first();
        
        if (!$vacacion) {
            $this->warn("No hay vacaciones en el sistema. Necesitas crear datos de prueba primero.");
            return null;
        }

        return $vacacion;
    }

    private function getOrCreateTestLicencia()
    {
        // Buscar una licencia existente o crear una de prueba
        $licencia = Licencia::with('trabajador')->first();
        
        if (!$licencia) {
            $this->warn("No hay licencias en el sistema. Necesitas crear datos de prueba primero.");
            return null;
        }

        return $licencia;
    }
}
