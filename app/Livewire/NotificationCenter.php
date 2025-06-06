<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;
use Carbon\Carbon;

class NotificationCenter extends Component
{
    public $showDropdown = false;
    public $notifications = [];
    public $unreadCount = 0;

    protected $listeners = ['notificationReceived' => 'loadNotifications'];

    public function mount()
    {
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        if (!Auth::check()) {
            return;
        }

        $user = Auth::user();
        
        // Cargar las últimas 10 notificaciones
        $this->notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($notification) {
                $data = $notification->data;
                return [
                    'id' => $notification->id,
                    'tipo' => $data['tipo'] ?? 'general',
                    'titulo' => $data['titulo'] ?? 'Notificación',
                    'mensaje' => $data['mensaje'] ?? '',
                    'icono' => $data['icono'] ?? 'bell',
                    'color' => $data['color'] ?? 'gray',
                    'url' => $data['datos']['url'] ?? '#',
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at,
                    'tiempo_relativo' => $this->getRelativeTime($notification->created_at),
                ];
            });

        // Contar notificaciones no leídas
        $this->unreadCount = $user->unreadNotifications()->count();
    }

    public function markAsRead($notificationId)
    {
        $user = Auth::user();
        $notification = $user->notifications()->find($notificationId);
        
        if ($notification) {
            $notification->markAsRead();
            $this->loadNotifications();
        }
    }

    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->unreadNotifications->markAsRead();
        $this->loadNotifications();
    }

    public function deleteNotification($notificationId)
    {
        $user = Auth::user();
        $notification = $user->notifications()->find($notificationId);
        
        if ($notification) {
            $notification->delete();
            $this->loadNotifications();
        }
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
    }

    public function closeDropdown()
    {
        $this->showDropdown = false;
    }

    public function goToNotification($notificationId, $url = null)
    {
        $this->markAsRead($notificationId);
        
        if ($url && $url !== '#') {
            return redirect()->to($url);
        }
    }

    private function getRelativeTime($timestamp)
    {
        $carbon = Carbon::parse($timestamp);
        
        if ($carbon->isToday()) {
            return $carbon->format('H:i');
        } elseif ($carbon->isYesterday()) {
            return 'Ayer';
        } elseif ($carbon->diffInDays() < 7) {
            return $carbon->diffInDays() . ' días';
        } else {
            return $carbon->format('d/m/Y');
        }
    }

    public function render()
    {
        return view('livewire.notification-center');
    }
}
