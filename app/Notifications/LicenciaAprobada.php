<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Licencia;

class LicenciaAprobada extends Notification implements ShouldQueue
{
    use Queueable;

    protected $licencia;

    /**
     * Create a new notification instance.
     */
    public function __construct(Licencia $licencia)
    {
        $this->licencia = $licencia;
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
            ->subject('🎉 Tu solicitud de licencia ha sido aprobada')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('¡Excelentes noticias! Tu solicitud de licencia ha sido **aprobada**.')
            ->line('**Tipo de licencia:** ' . $this->licencia->Tipo_Licencia)
            ->line('**Período aprobado:** ' . $this->licencia->Fecha_Inicio->format('d/m/Y') . ' - ' . $this->licencia->Fecha_Fin->format('d/m/Y'))
            ->line('**Días de licencia:** ' . $this->licencia->Dias_Solicitados)
            ->line('**Fecha de aprobación:** ' . $this->licencia->Fecha_Aprobacion_Rechazo?->format('d/m/Y H:i'))
            ->when($this->licencia->Comentarios_Admin, function ($message) {
                return $message->line('**Comentarios del administrador:** ' . $this->licencia->Comentarios_Admin);
            })
            ->action('Ver Mis Licencias', route('portal.licencias.index'))
            ->line('Que tengas una buena licencia.')
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
            'tipo' => 'licencia_aprobada',
            'titulo' => '🎉 Licencia Aprobada',
            'mensaje' => 'Tu solicitud de licencia (' . $this->licencia->Tipo_Licencia . ') del ' . 
                        $this->licencia->Fecha_Inicio->format('d/m/Y') . ' al ' . 
                        $this->licencia->Fecha_Fin->format('d/m/Y') . ' ha sido aprobada',
            'datos' => [
                'licencia_id' => $this->licencia->ID_Licencias,
                'tipo_licencia' => $this->licencia->Tipo_Licencia,
                'fecha_inicio' => $this->licencia->Fecha_Inicio->format('Y-m-d'),
                'fecha_fin' => $this->licencia->Fecha_Fin->format('Y-m-d'),
                'dias_solicitados' => $this->licencia->Dias_Solicitados,
                'comentarios_admin' => $this->licencia->Comentarios_Admin,
                'fecha_aprobacion' => $this->licencia->Fecha_Aprobacion_Rechazo?->format('Y-m-d H:i:s'),
                'url' => route('portal.licencias.index'),
            ],
            'icono' => 'check-circle',
            'color' => 'green',
        ];
    }
}
