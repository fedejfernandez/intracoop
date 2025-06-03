<?php

namespace App\Livewire\Admin\Trabajadores;

use App\Models\Trabajador;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public function render()
    {
        return view('livewire.admin.trabajadores.index', [
            'trabajadores' => Trabajador::paginate(10), // Obtener 10 trabajadores por página
        ]);
    }

    public function eliminarTrabajador($id)
    {
        $trabajador = Trabajador::findOrFail($id);
        $trabajador->delete();
        session()->flash('message', 'Trabajador eliminado correctamente.');
        // Opcional: podrías querer refrescar la lista o no hacer nada y dejar que Livewire lo maneje
    }
}
