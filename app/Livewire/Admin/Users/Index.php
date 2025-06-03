<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    protected $paginationTheme = 'tailwind'; // Usar Tailwind para la paginación
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function eliminarUser(User $user)
    {
        // Aquí podrías añadir una validación para no permitir eliminar el usuario actualmente logueado
        // o el último administrador, si es necesario.
        // if (auth()->id() === $user->id) {
        //     session()->flash('error', 'No puedes eliminar tu propia cuenta.');
        //     return;
        // }

        // Opcional: Verificar si es el único admin
        // if ($user->isAdmin() && User::where('role', 'admin')->count() === 1) {
        //     session()->flash('error', 'No se puede eliminar el único administrador.');
        //     return;
        // }

        $user->delete();
        session()->flash('message', 'Usuario eliminado correctamente.');
    }

    public function render()
    {
        $users = User::where('name', 'like', '%'.$this->search.'%')
                     ->orWhere('email', 'like', '%'.$this->search.'%')
                     ->orderBy('name')
                     ->paginate(10);
                     
        return view('livewire.admin.users.index', [
            'users' => $users,
        ])->layout('layouts.app');
    }
}
