<?php

namespace App\Livewire\Admin\Trabajadores;

use Livewire\Component;

class Create extends Component
{
    public function render()
    {
        // Simplemente renderiza una vista que a su vez carga el componente del formulario
        // No es necesario pasar trabajadorId, ya que es para creación.
        return view('livewire.admin.trabajadores.create');
    }
} 