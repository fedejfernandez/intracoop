<?php

namespace App\Livewire\Admin\Trabajadores;

use App\Models\Trabajador;
use Livewire\Component;

class Legajo extends Component
{
    public Trabajador $trabajador;

    public function mount(Trabajador $trabajador)
    {
        $this->trabajador = $trabajador->load(['user', 'licencias', 'vacaciones']); // Cargar relaciones necesarias
    }

    public function render()
    {
        return view('livewire.admin.trabajadores.legajo');
    }
}
