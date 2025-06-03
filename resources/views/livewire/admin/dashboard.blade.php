<div>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">

            <div class="mb-8">
                <div class="px-4 py-5 sm:px-6">
                    <h1 class="text-3xl font-bold text-gray-900">
                        Panel de Administración
                    </h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Gestiona los recursos y solicitudes del sistema.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Tarjeta para Gestionar Trabajadores -->
                <div class="bg-white shadow-lg sm:rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-lg font-medium text-gray-900 truncate">Trabajadores</dt>
                                    <dd class="text-sm text-gray-500">Gestionar personal</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('admin.trabajadores.index') }}" wire:navigate class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                Ir a Trabajadores
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta para Gestionar Usuarios -->
                <div class="bg-white shadow-lg sm:rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-lg font-medium text-gray-900 truncate">Usuarios</dt>
                                    <dd class="text-sm text-gray-500">Gestionar accesos</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('admin.users.index') }}" wire:navigate class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                                Ir a Usuarios
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Tarjeta para Solicitudes de Licencia -->
                <div class="bg-white shadow-lg sm:rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-lg font-medium text-gray-900 truncate">Solicitudes Licencia</dt>
                                    <dd class="text-sm text-gray-500">Revisar licencias</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('admin.licencias.requests') }}" wire:navigate class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                Ir a Solicitudes
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Tarjeta para Solicitudes de Vacaciones -->
                <div class="bg-white shadow-lg sm:rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                               <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-lg font-medium text-gray-900 truncate">Solicitudes Vacaciones</dt>
                                    <dd class="text-sm text-gray-500">Revisar vacaciones</dd>
                                </dl>
                            </div>
                        </div>
                        <div class="mt-6">
                            <a href="{{ route('admin.vacaciones.requests') }}" wire:navigate class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                Ir a Solicitudes
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
