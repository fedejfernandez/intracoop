<?php

namespace App\Livewire\Admin\Trabajadores;

use App\Models\Trabajador;
use Livewire\Component;

class Edit extends Component
{
    public Trabajador $trabajador; // Route model binding

    public function mount(Trabajador $trabajador)
    {
        $this->trabajador = $trabajador;
    }

    public function render()
    {
        // Renderiza una vista que carga el formulario, pasando el ID del trabajador
        return view('livewire.admin.trabajadores.edit', [
            'trabajadorId' => $this->trabajador->ID_Trabajador,
        ]);
    }
} 