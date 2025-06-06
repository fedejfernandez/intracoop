<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-blue-800 leading-tight">
            {{ __('Gestión de Roles y Permisos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session()->has('message'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('message') }}
                </div>
            @endif

            @if (session()->has('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Pestañas -->
            <div class="mb-6">
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8">
                        <button id="users-tab" class="whitespace-nowrap py-2 px-1 border-b-2 border-blue-500 font-medium text-sm text-blue-600">
                            Usuarios y Roles
                        </button>
                        <button id="roles-tab" class="whitespace-nowrap py-2 px-1 border-b-2 border-transparent font-medium text-sm text-gray-500 hover:text-gray-700 hover:border-gray-300">
                            Gestión de Roles
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Panel de Usuarios -->
            <div id="users-panel">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Asignar Roles a Usuarios</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Usuario</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roles Actuales</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sector</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($users as $user)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                {{ $user->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $user->email }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @forelse($user->roles as $role)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-1">
                                                        {{ $role->display_name }}
                                                    </span>
                                                @empty
                                                    <span class="text-gray-400">Sin roles</span>
                                                @endforelse
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $user->getSectorAsignado() ?? '-' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button wire:click="openUserModal({{ $user->id }})" 
                                                        class="text-blue-600 hover:text-blue-900">
                                                    Gestionar Roles
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Panel de Roles -->
            <div id="roles-panel" class="hidden">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Gestión de Roles</h3>
                            <button wire:click="openRoleModal()" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Nuevo Rol
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($roles as $role)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex justify-between items-start mb-2">
                                        <h4 class="text-lg font-semibold text-gray-900">{{ $role->display_name }}</h4>
                                        <div class="flex space-x-2">
                                            <button wire:click="openRoleModal({{ $role->id }})" 
                                                    class="text-blue-600 hover:text-blue-900 text-sm">
                                                Editar
                                            </button>
                                            @if(!in_array($role->name, ['administrador', 'rrhh', 'jefe_area', 'portal']))
                                                <button wire:click="deleteRole({{ $role->id }})" 
                                                        onclick="return confirm('¿Está seguro de eliminar este rol?')"
                                                        class="text-red-600 hover:text-red-900 text-sm">
                                                    Eliminar
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 mb-3">{{ $role->description }}</p>
                                    <p class="text-xs text-gray-500">
                                        {{ $role->permissions->count() }} permisos asignados
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $role->users->count() }} usuarios con este rol
                                    </p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para gestionar roles de usuario -->
    @if($showUserModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        Gestionar Roles - {{ $selectedUser->name ?? '' }}
                    </h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Roles</label>
                            @foreach($roles as $role)
                                <div class="flex items-center mb-2">
                                    <input type="checkbox" 
                                           wire:model="userRoles" 
                                           value="{{ $role->id }}"
                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <label class="ml-2 text-sm text-gray-900">{{ $role->display_name }}</label>
                                </div>
                            @endforeach
                        </div>

                        @if(in_array('jefe_area', collect($userRoles)->map(fn($id) => $roles->find($id)?->name)->toArray()))
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sector Asignado</label>
                                <select wire:model="selectedSector" 
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <option value="">Seleccionar sector</option>
                                    @foreach($availableSectors as $sector)
                                        <option value="{{ $sector }}">{{ $sector }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button wire:click="closeUserModal" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                            Cancelar
                        </button>
                        <button wire:click="updateUserRoles" 
                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal para gestionar roles -->
    @if($showRoleModal)
        <div class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
            <div class="relative top-10 mx-auto p-5 border w-3/4 max-w-4xl shadow-lg rounded-md bg-white">
                <div class="mt-3">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        {{ $selectedRole ? 'Editar Rol' : 'Nuevo Rol' }}
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Información del rol -->
                        <div>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Rol</label>
                                    <input type="text" 
                                           wire:model="newRoleName" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                           placeholder="ej: custom_role">
                                    @error('newRoleName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Nombre para Mostrar</label>
                                    <input type="text" 
                                           wire:model="newRoleDisplayName" 
                                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                           placeholder="ej: Rol Personalizado">
                                    @error('newRoleDisplayName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Descripción</label>
                                    <textarea wire:model="newRoleDescription" 
                                              rows="3"
                                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                                              placeholder="Descripción del rol..."></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Permisos -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">Permisos</label>
                            <div class="space-y-4 max-h-96 overflow-y-auto">
                                @foreach($permissions as $module => $modulePermissions)
                                    <div class="border border-gray-200 rounded p-3">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-medium text-gray-900 capitalize">{{ $module }}</h4>
                                            <button type="button" 
                                                    wire:click="toggleModulePermissions('{{ $module }}')"
                                                    class="text-xs text-blue-600 hover:text-blue-800">
                                                Toggle Todos
                                            </button>
                                        </div>
                                        <div class="space-y-1">
                                            @foreach($modulePermissions as $permission)
                                                <div class="flex items-center">
                                                    <input type="checkbox" 
                                                           wire:click="togglePermission({{ $permission['id'] }})"
                                                           @checked(in_array($permission['id'], $rolePermissions))
                                                           class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                                    <label class="ml-2 text-xs text-gray-700">{{ $permission['description'] }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button wire:click="closeRoleModal" 
                                class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400">
                            Cancelar
                        </button>
                        <button wire:click="saveRole" 
                                class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                            {{ $selectedRole ? 'Actualizar' : 'Crear' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script>
        document.getElementById('users-tab').addEventListener('click', function() {
            document.getElementById('users-panel').classList.remove('hidden');
            document.getElementById('roles-panel').classList.add('hidden');
            
            document.getElementById('users-tab').classList.add('border-blue-500', 'text-blue-600');
            document.getElementById('users-tab').classList.remove('border-transparent', 'text-gray-500');
            
            document.getElementById('roles-tab').classList.add('border-transparent', 'text-gray-500');
            document.getElementById('roles-tab').classList.remove('border-blue-500', 'text-blue-600');
        });

        document.getElementById('roles-tab').addEventListener('click', function() {
            document.getElementById('roles-panel').classList.remove('hidden');
            document.getElementById('users-panel').classList.add('hidden');
            
            document.getElementById('roles-tab').classList.add('border-blue-500', 'text-blue-600');
            document.getElementById('roles-tab').classList.remove('border-transparent', 'text-gray-500');
            
            document.getElementById('users-tab').classList.add('border-transparent', 'text-gray-500');
            document.getElementById('users-tab').classList.remove('border-blue-500', 'text-blue-600');
        });
    </script>
</div> 