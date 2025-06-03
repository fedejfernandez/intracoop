<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class CreateEditForm extends Component
{
    public User $userObject; // Para type hinting y facilidad

    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $role = 'portal'; // Rol por defecto
    public $userId = null;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $this->userId,
            'password' => [$this->userId ? 'nullable' : 'required', 'sometimes', 'string', Password::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'],
            'role' => 'required|string|in:admin,portal',
        ];
    }

    protected $messages = [
        'email.unique' => 'Este email ya está registrado.',
        'password.required' => 'La contraseña es obligatoria para nuevos usuarios.',
        'role.in' => 'El rol seleccionado no es válido.',
    ];

    public function mount($userId = null)
    {
        $this->userId = $userId;
        if ($this->userId) {
            $user = User::findOrFail($this->userId);
            $this->userObject = $user;
            $this->name = $user->name;
            $this->email = $user->email;
            $this->role = $user->role;
            // No cargamos la contraseña para editar por seguridad
        }
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
        ];

        if (!empty($this->password)) {
            $data['password'] = Hash::make($this->password);
        }

        if ($this->userId) {
            User::find($this->userId)->update($data);
            session()->flash('message', 'Usuario actualizado correctamente.');
        } else {
            User::create($data);
            session()->flash('message', 'Usuario creado correctamente.');
        }

        return redirect()->route('admin.users.index');
    }

    public function render()
    {
        return view('livewire.admin.users.create-edit-form');
    }
}
