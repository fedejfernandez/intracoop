<?php

namespace App\Livewire\Portal;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Trabajador;

class Legajo extends Component
{
    public ?Trabajador $trabajador;

    public function mount()
    {
        $user = Auth::user();
        if ($user && $user->trabajador) {
            $this->trabajador = $user->trabajador;
        } else {
            $this->trabajador = null;
            // Opcional: redirigir o mostrar error si no hay trabajador asociado
        }
    }

    public function render()
    {
        return view('livewire.portal.legajo')->layout('layouts.app');
    }
} 