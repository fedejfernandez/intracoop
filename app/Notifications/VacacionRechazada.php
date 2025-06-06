<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Vacacion;

class VacacionRechazada extends Notification implements ShouldQueue
{
    use Queueable;

    protected $vacacion;

    /**
     * Create a new notification instance.
     */
    public function __construct(Vacacion $vacacion)
    {
        $this->vacacion = $vacacion;
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
            ->subject('Tu solicitud de vacaciones no fue aprobada')
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line('Lamentamos informarte que tu solicitud de vacaciones no ha sido aprobada.')
            ->line('**Período solicitado:** ' . $this->vacacion->Fecha_Inicio->format('d/m/Y') . ' - ' . $this->vacacion->Fecha_Fin->format('d/m/Y'))
            ->line('**Días solicitados:** ' . $this->vacacion->Dias_Solicitados)
            ->line('**Fecha de decisión:** ' . $this->vacacion->Fecha_Aprobacion_Rechazo?->format('d/m/Y H:i'))
            ->when($this->vacacion->Comentarios_Admin, function ($message) {
                return $message->line('**Motivo del rechazo:** ' . $this->vacacion->Comentarios_Admin);
            })
            ->line('Te recomendamos contactar con el área de RRHH para más información o para solicitar fechas alternativas.')
            ->action('Ver Mis Vacaciones', route('portal.vacaciones.index'))
            ->line('Gracias por tu comprensión.')
            ->salutation('Saludos, Equipo de RRHH');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'tipo' => 'vacacion_rechazada',
            'titulo' => 'Solicitud de Vacaciones Rechazada',
            'mensaje' => 'Tu solicitud de vacaciones del ' . 
                        $this->vacacion->Fecha_Inicio->format('d/m/Y') . ' al ' . 
                        $this->vacacion->Fecha_Fin->format('d/m/Y') . ' no fue aprobada',
            'datos' => [
                'vacacion_id' => $this->vacacion->ID_Vacaciones,
                'fecha_inicio' => $this->vacacion->Fecha_Inicio->format('Y-m-d'),
                'fecha_fin' => $this->vacacion->Fecha_Fin->format('Y-m-d'),
                'dias_solicitados' => $this->vacacion->Dias_Solicitados,
                'comentarios_admin' => $this->vacacion->Comentarios_Admin,
                'fecha_rechazo' => $this->vacacion->Fecha_Aprobacion_Rechazo?->format('Y-m-d H:i:s'),
                'url' => route('portal.vacaciones.index'),
            ],
            'icono' => 'x-circle',
            'color' => 'red',
        ];
    }
}
