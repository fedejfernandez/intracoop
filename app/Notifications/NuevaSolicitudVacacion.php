<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Vacacion;
use App\Models\Trabajador;

class NuevaSolicitudVacacion extends Notification implements ShouldQueue
{
    use Queueable;

    protected $vacacion;
    protected $trabajador;

    /**
     * Create a new notification instance.
     */
    public function __construct(Vacacion $vacacion)
    {
        $this->vacacion = $vacacion;
        $this->trabajador = $vacacion->trabajador;
        
        // Para desarrollo, puedes deshabilitar las colas
        if (env('NOTIFICATIONS_SYNC', false)) {
            $this->connection = 'sync';
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Nueva Solicitud de Vacaciones - ' . $this->trabajador->NombreCompleto)
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line('Se ha recibido una nueva solicitud de vacaciones.')
            ->line('**Trabajador:** ' . $this->trabajador->NombreCompleto)
            ->line('**Período:** ' . $this->vacacion->Fecha_Inicio->format('d/m/Y') . ' - ' . $this->vacacion->Fecha_Fin->format('d/m/Y'))
            ->line('**Días solicitados:** ' . $this->vacacion->Dias_Solicitados)
            ->line('**Sector:** ' . ($this->trabajador->Sector ?? 'No especificado'))
            ->action('Ver Solicitudes de Vacaciones', route('admin.vacaciones.requests'))
            ->line('Por favor, revisa y procesa esta solicitud a la brevedad.')
            ->salutation('Saludos, Sistema de RRHH');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'tipo' => 'nueva_solicitud_vacacion',
            'titulo' => 'Nueva Solicitud de Vacaciones',
            'mensaje' => $this->trabajador->NombreCompleto . ' ha solicitado vacaciones del ' . 
                        $this->vacacion->Fecha_Inicio->format('d/m/Y') . ' al ' . 
                        $this->vacacion->Fecha_Fin->format('d/m/Y') . ' (' . 
                        $this->vacacion->Dias_Solicitados . ' días)',
            'datos' => [
                'vacacion_id' => $this->vacacion->ID_Vacaciones,
                'trabajador_id' => $this->trabajador->ID_Trabajador,
                'trabajador_nombre' => $this->trabajador->NombreCompleto,
                'fecha_inicio' => $this->vacacion->Fecha_Inicio->format('Y-m-d'),
                'fecha_fin' => $this->vacacion->Fecha_Fin->format('Y-m-d'),
                'dias_solicitados' => $this->vacacion->Dias_Solicitados,
                'sector' => $this->trabajador->Sector,
                'url' => route('admin.vacaciones.requests'),
            ],
            'icono' => 'calendar',
            'color' => 'blue',
        ];
    }
}
