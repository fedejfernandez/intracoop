<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Licencia;
use App\Models\Trabajador;

class NuevaSolicitudLicencia extends Notification implements ShouldQueue
{
    use Queueable;

    protected $licencia;
    protected $trabajador;

    /**
     * Create a new notification instance.
     */
    public function __construct(Licencia $licencia)
    {
        $this->licencia = $licencia;
        $this->trabajador = $licencia->trabajador;
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
            ->subject('Nueva Solicitud de Licencia - ' . $this->trabajador->NombreCompleto)
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line('Se ha recibido una nueva solicitud de licencia.')
            ->line('**Trabajador:** ' . $this->trabajador->NombreCompleto)
            ->line('**Tipo de licencia:** ' . $this->licencia->Tipo_Licencia)
            ->line('**Período:** ' . $this->licencia->Fecha_Inicio->format('d/m/Y') . ' - ' . $this->licencia->Fecha_Fin->format('d/m/Y'))
            ->line('**Días solicitados:** ' . $this->licencia->Dias_Solicitados)
            ->line('**Sector:** ' . ($this->trabajador->Sector ?? 'No especificado'))
            ->when($this->licencia->Motivo, function ($message) {
                return $message->line('**Motivo:** ' . $this->licencia->Motivo);
            })
            ->action('Ver Solicitudes de Licencias', route('admin.licencias.requests'))
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
            'tipo' => 'nueva_solicitud_licencia',
            'titulo' => 'Nueva Solicitud de Licencia',
            'mensaje' => $this->trabajador->NombreCompleto . ' ha solicitado una licencia (' . 
                        $this->licencia->Tipo_Licencia . ') del ' . 
                        $this->licencia->Fecha_Inicio->format('d/m/Y') . ' al ' . 
                        $this->licencia->Fecha_Fin->format('d/m/Y'),
            'datos' => [
                'licencia_id' => $this->licencia->ID_Licencias,
                'trabajador_id' => $this->trabajador->ID_Trabajador,
                'trabajador_nombre' => $this->trabajador->NombreCompleto,
                'tipo_licencia' => $this->licencia->Tipo_Licencia,
                'fecha_inicio' => $this->licencia->Fecha_Inicio->format('Y-m-d'),
                'fecha_fin' => $this->licencia->Fecha_Fin->format('Y-m-d'),
                'dias_solicitados' => $this->licencia->Dias_Solicitados,
                'motivo' => $this->licencia->Motivo,
                'sector' => $this->trabajador->Sector,
                'url' => route('admin.licencias.requests'),
            ],
            'icono' => 'document-text',
            'color' => 'purple',
        ];
    }
}
