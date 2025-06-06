<?php

namespace App\Livewire\Admin\Permissions;

use Livewire\Component;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;

class RoleManager extends Component
{
    public $users;
    public $roles;
    public $permissions;
    public $selectedUser = null;
    public $selectedRole = null;
    public $selectedSector = null;
    public $userRoles = [];
    public $rolePermissions = [];
    public $showUserModal = false;
    public $showRoleModal = false;
    public $availableSectors = [];

    // Para crear nuevos roles
    public $newRoleName = '';
    public $newRoleDisplayName = '';
    public $newRoleDescription = '';
    public $newRolePermissions = [];

    protected $rules = [
        'newRoleName' => 'required|string|max:255|unique:roles,name',
        'newRoleDisplayName' => 'required|string|max:255',
        'newRoleDescription' => 'nullable|string',
    ];

    public function mount()
    {
        // Verificar que el usuario tenga permisos de administrador
        if (!Auth::user()->hasPermission('admin.roles.index')) {
            abort(403, 'No tienes permisos para gestionar roles.');
        }

        $this->loadData();
        $this->loadAvailableSectors();
    }

    public function loadData()
    {
        $this->users = User::with('roles')->get();
        $this->roles = Role::with('permissions')->get();
        $this->permissions = Permission::all()->groupBy('module')->toArray();
    }

    public function loadAvailableSectors()
    {
        // Obtener sectores únicos de los trabajadores
        $this->availableSectors = \App\Models\Trabajador::whereNotNull('Sector')
            ->distinct()
            ->pluck('Sector')
            ->filter()
            ->toArray();
    }

    public function openUserModal($userId)
    {
        $this->selectedUser = User::with('roles')->find($userId);
        $this->userRoles = $this->selectedUser->roles->pluck('id')->toArray();
        $this->selectedSector = $this->selectedUser->getSectorAsignado();
        $this->showUserModal = true;
    }

    public function openRoleModal($roleId = null)
    {
        if ($roleId) {
            $this->selectedRole = Role::with('permissions')->find($roleId);
            $this->newRoleName = $this->selectedRole->name;
            $this->newRoleDisplayName = $this->selectedRole->display_name;
            $this->newRoleDescription = $this->selectedRole->description;
            $this->rolePermissions = $this->selectedRole->permissions->pluck('id')->toArray();
        } else {
            $this->selectedRole = null;
            $this->resetRoleForm();
        }
        $this->showRoleModal = true;
    }

    public function closeUserModal()
    {
        $this->showUserModal = false;
        $this->selectedUser = null;
        $this->userRoles = [];
        $this->selectedSector = null;
    }

    public function closeRoleModal()
    {
        $this->showRoleModal = false;
        $this->selectedRole = null;
        $this->resetRoleForm();
    }

    public function resetRoleForm()
    {
        $this->newRoleName = '';
        $this->newRoleDisplayName = '';
        $this->newRoleDescription = '';
        $this->rolePermissions = [];
    }

    public function updateUserRoles()
    {
        if (!$this->selectedUser) return;

        $finalRolesToSync = [];
        $jefeAreaRole = Role::where('name', 'jefe_area')->first();
        $jefeAreaRoleId = $jefeAreaRole ? $jefeAreaRole->id : null;

        // $this->userRoles es el array de IDs de roles seleccionados en el formulario
        foreach ($this->userRoles as $roleId) {
            if ($roleId == $jefeAreaRoleId && !empty($this->selectedSector)) {
                // Si el rol es 'jefe_area' y se ha seleccionado un sector, añadir datos pivot
                $finalRolesToSync[$roleId] = ['sector' => $this->selectedSector];
            } else {
                // Para otros roles, o si no hay sector para jefe_area,
                // pasamos un array vacío para indicar que no hay datos pivot adicionales específicos.
                $finalRolesToSync[$roleId] = [];
            }
        }

        $this->selectedUser->syncRoles($finalRolesToSync);
        
        session()->flash('message', 'Roles actualizados correctamente.');
        $this->closeUserModal();
        $this->loadData();
    }

    public function saveRole()
    {
        $this->validate();

        if ($this->selectedRole) {
            // Actualizar rol existente
            $this->selectedRole->update([
                'name' => $this->newRoleName,
                'display_name' => $this->newRoleDisplayName,
                'description' => $this->newRoleDescription,
            ]);
            $role = $this->selectedRole;
        } else {
            // Crear nuevo rol
            $role = Role::create([
                'name' => $this->newRoleName,
                'display_name' => $this->newRoleDisplayName,
                'description' => $this->newRoleDescription,
            ]);
        }

        // Actualizar permisos del rol
        $role->permissions()->sync($this->rolePermissions);

        session()->flash('message', $this->selectedRole ? 'Rol actualizado correctamente.' : 'Rol creado correctamente.');
        $this->closeRoleModal();
        $this->loadData();
    }

    public function deleteRole($roleId)
    {
        $role = Role::find($roleId);
        
        if ($role && !in_array($role->name, ['administrador', 'rrhh', 'jefe_area', 'portal'])) {
            $role->delete();
            session()->flash('message', 'Rol eliminado correctamente.');
            $this->loadData();
        } else {
            session()->flash('error', 'No se puede eliminar un rol del sistema.');
        }
    }

    public function togglePermission($permissionId)
    {
        if (in_array($permissionId, $this->rolePermissions)) {
            $this->rolePermissions = array_diff($this->rolePermissions, [$permissionId]);
        } else {
            $this->rolePermissions[] = $permissionId;
        }
    }

    public function toggleModulePermissions($module)
    {
        $modulePermissions = $this->permissions[$module]->pluck('id')->toArray();
        $allSelected = !array_diff($modulePermissions, $this->rolePermissions);
        
        if ($allSelected) {
            // Deseleccionar todos los permisos del módulo
            $this->rolePermissions = array_diff($this->rolePermissions, $modulePermissions);
        } else {
            // Seleccionar todos los permisos del módulo
            $this->rolePermissions = array_unique(array_merge($this->rolePermissions, $modulePermissions));
        }
    }

    public function render()
    {
        return view('livewire.admin.permissions.role-manager')->layout('layouts.app');
    }
} 