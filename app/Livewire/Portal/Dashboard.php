<?php

namespace App\Livewire\Portal;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Trabajador; // Asegurarse de importar Trabajador

class Dashboard extends Component
{
    public ?Trabajador $trabajador;

    public function mount()
    {
        $user = Auth::user();
        if ($user) {
            // Cargar el trabajador asociado al usuario actual
            // Asumimos que la relación en el modelo User se llama 'trabajador'
            $this->trabajador = $user->trabajador; 
        } else {
            $this->trabajador = null;
        }
    }

    public function render()
    {
        return view('livewire.portal.dashboard', [
            'trabajador_info' => $this->trabajador // Pasar el trabajador a la vista
        ])->layout('layouts.app');
    }
}
