<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-blue-800 leading-tight">
            {{ __('Mi Portal') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if ($trabajador)
                <!-- Resumen de Vacaciones -->
                @if (!empty($resumenVacaciones))
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Mi Estado de Vacaciones {{ date('Y') }}</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            <!-- Días Asignados -->
                            <div class="bg-blue-50 overflow-hidden shadow rounded-lg">
                                <div class="p-5">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-5 w-0 flex-1">
                                            <dl>
                                                <dt class="text-sm font-medium text-gray-500 truncate">Días Asignados</dt>
                                                <dd class="text-lg font-medium text-gray-900">{{ $resumenVacaciones['dias_asignados'] ?? 0 }}</dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Días Utilizados -->
                            <div class="bg-green-50 overflow-hidden shadow rounded-lg">
                                <div class="p-5">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-5 w-0 flex-1">
                                            <dl>
                                                <dt class="text-sm font-medium text-gray-500 truncate">Días Utilizados</dt>
                                                <dd class="text-lg font-medium text-gray-900">{{ $resumenVacaciones['dias_tomados'] ?? 0 }}</dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Días Pendientes -->
                            <div class="bg-yellow-50 overflow-hidden shadow rounded-lg">
                                <div class="p-5">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-5 w-0 flex-1">
                                            <dl>
                                                <dt class="text-sm font-medium text-gray-500 truncate">Días Pendientes</dt>
                                                <dd class="text-lg font-medium text-gray-900">{{ $resumenVacaciones['dias_pendientes'] ?? 0 }}</dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Días Disponibles -->
                            <div class="bg-indigo-50 overflow-hidden shadow rounded-lg">
                                <div class="p-5">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-5 w-0 flex-1">
                                            <dl>
                                                <dt class="text-sm font-medium text-gray-500 truncate">Días Disponibles</dt>
                                                <dd class="text-lg font-medium text-gray-900">{{ $resumenVacaciones['dias_disponibles'] ?? 0 }}</dd>
                                            </dl>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Barra de Progreso -->
                        @if(isset($resumenVacaciones['porcentaje_utilizado']))
                        <div class="mt-6">
                            <div class="flex justify-between text-sm font-medium text-gray-900 mb-2">
                                <span>Progreso de Vacaciones</span>
                                <span>{{ $resumenVacaciones['porcentaje_utilizado'] }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-2">
                                <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min(100, $resumenVacaciones['porcentaje_utilizado']) }}%"></div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Grid de dos columnas -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    
                    <!-- Mis Datos Personales -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Mis Datos Personales</h3>
                        </div>
                        <div class="p-6">
                            <dl class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nombre Completo</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->NombreCompleto }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Legajo</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->NumeroLegajo ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">DNI/CUIL</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->DNI_CUIL }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->Email }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Puesto</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->Puesto ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Sector</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->Sector ?? 'N/A' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fecha de Ingreso</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->FechaIngreso ? $trabajador->FechaIngreso->format('d/m/Y') : 'N/A' }}</dd>
                                </div>
                            </dl>
                            
                            <!-- Botón Ver Legajo Completo -->
                            <div class="mt-6 pt-4 border-t border-gray-200">
                                <a href="{{ route('portal.legajo.show') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 transition ease-in-out duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    Ver Legajo Completo
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Próximas Vacaciones -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900">Mis Próximas Vacaciones</h3>
                        </div>
                        <div class="p-6">
                            @if(!empty($proximasVacaciones) && count($proximasVacaciones) > 0)
                                <div class="space-y-3">
                                    @foreach($proximasVacaciones as $vacacion)
                                        <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ \Carbon\Carbon::parse($vacacion['Fecha_Inicio'])->format('d/m/Y') }} - 
                                                        {{ \Carbon\Carbon::parse($vacacion['Fecha_Fin'])->format('d/m/Y') }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $vacacion['Dias_Solicitados'] }} días
                                                    </div>
                                                </div>
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    {{ $vacacion['Estado_Solicitud'] }}
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500">No tienes vacaciones próximas programadas.</p>
                            @endif
                            
                            <div class="mt-4">
                                <a href="{{ route('portal.vacaciones.solicitar') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Solicitar Vacaciones
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Solicitudes Pendientes -->
                @if(!empty($solicitudesPendientes) && count($solicitudesPendientes) > 0)
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Mis Solicitudes Pendientes</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @foreach($solicitudesPendientes as $solicitud)
                                <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                Vacaciones: {{ \Carbon\Carbon::parse($solicitud['Fecha_Inicio'])->format('d/m/Y') }} - 
                                                {{ \Carbon\Carbon::parse($solicitud['Fecha_Fin'])->format('d/m/Y') }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $solicitud['Dias_Solicitados'] }} días solicitados
                                            </div>
                                            <div class="text-xs text-gray-400 mt-1">
                                                Solicitado el {{ \Carbon\Carbon::parse($solicitud['created_at'])->format('d/m/Y') }}
                                            </div>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            {{ $solicitud['Estado_Solicitud'] }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Accesos Rápidos -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Accesos Rápidos</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <a href="{{ route('portal.vacaciones.index') }}" 
                               class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors">
                                <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-4 0V7"></path>
                                </svg>
                                <div>
                                    <div class="font-medium text-gray-900">Mis Vacaciones</div>
                                    <div class="text-sm text-gray-500">Ver historial completo</div>
                                </div>
                            </a>

                            <a href="{{ route('portal.licencias.index') }}" 
                               class="flex items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition-colors">
                                <svg class="w-8 h-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <div>
                                    <div class="font-medium text-gray-900">Mis Licencias</div>
                                    <div class="text-sm text-gray-500">Ver solicitudes</div>
                                </div>
                            </a>

                            <a href="{{ route('profile.show') }}" 
                               class="flex items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors">
                                <svg class="w-8 h-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <div>
                                    <div class="font-medium text-gray-900">Mi Perfil</div>
                                    <div class="text-sm text-gray-500">Configurar cuenta</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

            @else
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.965-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Datos no encontrados</h3>
                            <p class="mt-1 text-sm text-gray-500">No se encontró información del trabajador asociada a este usuario.</p>
                            <p class="mt-1 text-sm text-gray-500">Por favor, contacte al administrador si cree que esto es un error.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
