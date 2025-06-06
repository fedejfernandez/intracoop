<?php

namespace App\Livewire\Admin\EvaluacionesDesempeno;

use App\Models\EvaluacionDesempeno;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind';
    public $search = '';
    public $perPage = 10;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = EvaluacionDesempeno::with(['trabajador', 'evaluador'])
            ->orderBy('fecha_evaluacion', 'desc');

        if ($this->search) {
            $query->whereHas('trabajador', function ($q) {
                $q->where('NombreCompleto', 'like', '%'.$this->search.'%');
            })
            ->orWhereHas('evaluador', function ($q) {
                $q->where('name', 'like', '%'.$this->search.'%');
            })
            ->orWhere('calificacion_general', 'like', '%'.$this->search.'%')
            ->orWhere('estado', 'like', '%'.$this->search.'%');
        }

        $evaluaciones = $query->paginate($this->perPage);

        return view('livewire.admin.evaluaciones-desempeno.index', [
            'evaluaciones' => $evaluaciones,
        ]);
    }
}
