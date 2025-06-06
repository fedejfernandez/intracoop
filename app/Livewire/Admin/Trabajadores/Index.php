<?php

namespace App\Livewire\Admin\Trabajadores;

use App\Models\Trabajador;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Url;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TrabajadoresExport;
use Illuminate\Support\Facades\Response;

class Index extends Component
{
    use WithPagination;

    // Propiedades de búsqueda y filtros
    #[Url(as: 'buscar')] 
    public $search = '';
    
    #[Url(as: 'estado')] 
    public $filtroEstado = '';
    
    #[Url(as: 'sector')] 
    public $filtroSector = '';
    
    #[Url(as: 'cct')] 
    public $filtroCCT = '';
    
    #[Url(as: 'puesto')] 
    public $filtroPuesto = '';
    
    public $perPage = 10;
    public $sortField = 'NombreCompleto';
    public $sortDirection = 'asc';
    
    // Para mostrar/ocultar filtros avanzados
    public $showAdvancedFilters = false;

    // Propiedades calculadas para los filtros
    public $sectores = [];
    public $puestos = [];
    public $estados = ['Activo', 'Inactivo', 'Licencia', 'Vacaciones'];
    public $ccts = ['36/75', '459/06', '177/75', '107/75'];

    protected $queryString = [
        'search' => ['except' => ''],
        'filtroEstado' => ['except' => ''],
        'filtroSector' => ['except' => ''],
        'filtroCCT' => ['except' => ''],
        'filtroPuesto' => ['except' => ''],
        'perPage' => ['except' => 10],
        'sortField' => ['except' => 'NombreCompleto'],
        'sortDirection' => ['except' => 'asc'],
    ];

    public function mount()
    {
        // Cargar valores únicos para los filtros
        $this->sectores = Trabajador::whereNotNull('Sector')
            ->distinct()
            ->pluck('Sector')
            ->sort()
            ->values()
            ->toArray();
            
        $this->puestos = Trabajador::whereNotNull('Puesto')
            ->distinct()
            ->pluck('Puesto')
            ->sort()
            ->values()
            ->toArray();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedFiltroEstado()
    {
        $this->resetPage();
    }

    public function updatedFiltroSector()
    {
        $this->resetPage();
    }

    public function updatedFiltroCCT()
    {
        $this->resetPage();
    }

    public function updatedFiltroPuesto()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortDirection = 'asc';
        }
        $this->sortField = $field;
        $this->resetPage();
    }

    public function limpiarFiltros()
    {
        $this->search = '';
        $this->filtroEstado = '';
        $this->filtroSector = '';
        $this->filtroCCT = '';
        $this->filtroPuesto = '';
        $this->resetPage();
    }

    public function toggleAdvancedFilters()
    {
        $this->showAdvancedFilters = !$this->showAdvancedFilters;
    }

    public function exportarExcel()
    {
        $trabajadores = $this->getFilteredTrabajadores()->get();
        
        return Excel::download(new TrabajadoresExport($trabajadores), 'trabajadores_' . date('Y-m-d_H-i-s') . '.xlsx');
    }

    public function exportarPDF()
    {
        // Construir parámetros de filtros para la URL
        $params = [];
        if ($this->search) $params['search'] = $this->search;
        if ($this->filtroEstado) $params['estado'] = $this->filtroEstado;
        if ($this->filtroSector) $params['sector'] = $this->filtroSector;
        if ($this->filtroCCT) $params['cct'] = $this->filtroCCT;
        if ($this->filtroPuesto) $params['puesto'] = $this->filtroPuesto;
        
        // Redirigir a una nueva ventana/pestaña
        $url = route('admin.trabajadores.pdf', $params);
        
        $this->dispatch('openInNewTab', url: $url);
    }

    private function getFilteredTrabajadores()
    {
        $query = Trabajador::query();

        // Búsqueda general
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('NombreCompleto', 'like', '%' . $this->search . '%')
                  ->orWhere('DNI_CUIL', 'like', '%' . $this->search . '%')
                  ->orWhere('Email', 'like', '%' . $this->search . '%')
                  ->orWhere('NumeroLegajo', 'like', '%' . $this->search . '%');
            });
        }

        // Filtros específicos
        if ($this->filtroEstado) {
            $query->where('Estado', $this->filtroEstado);
        }

        if ($this->filtroSector) {
            $query->where('Sector', $this->filtroSector);
        }

        if ($this->filtroCCT) {
            $query->where('CCT', $this->filtroCCT);
        }

        if ($this->filtroPuesto) {
            $query->where('Puesto', $this->filtroPuesto);
        }

        // Ordenamiento
        $query->orderBy($this->sortField, $this->sortDirection);

        return $query;
    }

    public function render()
    {
        $trabajadores = $this->getFilteredTrabajadores()->paginate($this->perPage);
        
        return view('livewire.admin.trabajadores.index', [
            'trabajadores' => $trabajadores,
        ]);
    }

    public function eliminarTrabajador($id)
    {
        $trabajador = Trabajador::findOrFail($id);
        $trabajador->delete();
        
        session()->flash('message', 'Trabajador eliminado correctamente.');
        $this->dispatch('trabajador-eliminado');
    }
}
