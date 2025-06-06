<div>
    <div class="py-6 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <div class="px-4 py-5 sm:px-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Panel de Administración</h1>
                            <p class="mt-1 text-sm text-gray-600">Sistema de Gestión de Recursos Humanos</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-sm text-gray-500">
                                <span class="font-medium">{{ now()->format('d/m/Y') }}</span>
                            </div>
                            <button wire:click="calcularEstadisticas" 
                                    class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                Actualizar
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas principales -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
                <!-- Total de Trabajadores -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 bg-blue-500 rounded-md flex items-center justify-center">
                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Trabajadores</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ number_format($totalTrabajadores) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <a href="{{ route('admin.trabajadores.index') }}" wire:navigate class="font-medium text-blue-600 hover:text-blue-500">Ver todos</a>
                        </div>
                    </div>
                </div>

                <!-- Trabajadores Activos -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 bg-green-500 rounded-md flex items-center justify-center">
                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Activos</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ number_format($trabajadoresActivos) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <span class="text-green-600 font-medium">
                                {{ $totalTrabajadores > 0 ? round(($trabajadoresActivos / $totalTrabajadores) * 100, 1) : 0 }}%
                            </span>
                            <span class="text-gray-500"> del total</span>
                        </div>
                    </div>
                </div>

                <!-- Licencias Pendientes -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Licencias Pendientes</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ number_format($licenciasPendientes) }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm">
                            <a href="{{ route('admin.licencias.requests') }}" wire:navigate class="font-medium text-yellow-600 hover:text-yellow-500">Revisar</a>
                        </div>
                    </div>
                </div>

                <!-- Antigüedad Promedio -->
                <div class="bg-white shadow rounded-lg overflow-hidden">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="h-8 w-8 bg-purple-500 rounded-md flex items-center justify-center">
                                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Antigüedad Promedio</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $antiguedadPromedio }} años</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-5 py-3">
                        <div class="text-sm text-gray-500">
                            <span>Experiencia del equipo</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Segunda fila de estadísticas -->
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
                <div class="bg-white shadow rounded-lg p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">En Licencia</p>
                            <p class="text-2xl font-semibold text-orange-600">{{ $trabajadoresLicencia }}</p>
                        </div>
                        <div class="h-12 w-12 bg-orange-100 rounded-full flex items-center justify-center">
                            <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">En Vacaciones</p>
                            <p class="text-2xl font-semibold text-blue-600">{{ $trabajadoresVacaciones }}</p>
                        </div>
                        <div class="h-12 w-12 bg-blue-100 rounded-full flex items-center justify-center">
                            <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Vacaciones Pendientes</p>
                            <p class="text-2xl font-semibold text-indigo-600">{{ $vacacionesPendientes }}</p>
                        </div>
                        <div class="h-12 w-12 bg-indigo-100 rounded-full flex items-center justify-center">
                            <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white shadow rounded-lg p-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500">Inactivos</p>
                            <p class="text-2xl font-semibold text-red-600">{{ $trabajadoresInactivos }}</p>
                        </div>
                        <div class="h-12 w-12 bg-red-100 rounded-full flex items-center justify-center">
                            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráficos y datos adicionales -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
                <!-- Distribución por Sector -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Distribución por Sector</h3>
                    </div>
                    <div class="p-6">
                        @if($trabajadoresPorSector->count() > 0)
                            <div class="space-y-4">
                                @foreach($trabajadoresPorSector as $sector)
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                                            <span class="text-sm font-medium text-gray-900">{{ $sector->Sector }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="text-sm text-gray-500 mr-2">{{ $sector->cantidad }}</span>
                                            <div class="w-20 bg-gray-200 rounded-full h-2">
                                                <div class="bg-blue-500 h-2 rounded-full" style="width: {{ ($sector->cantidad / $totalTrabajadores) * 100 }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No hay datos disponibles</p>
                        @endif
                    </div>
                </div>

                <!-- Distribución por CCT -->
                <div class="bg-white shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Distribución por CCT</h3>
                    </div>
                    <div class="p-6">
                        @if($trabajadoresPorCCT->count() > 0)
                            <div class="space-y-4">
                                @foreach($trabajadoresPorCCT as $index => $cct)
                                    @php
                                        $colors = ['bg-green-500', 'bg-yellow-500', 'bg-purple-500', 'bg-red-500'];
                                        $color = $colors[$index % count($colors)];
                                    @endphp
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-3 h-3 {{ $color }} rounded-full mr-3"></div>
                                            <span class="text-sm font-medium text-gray-900">CCT {{ $cct->CCT }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="text-sm text-gray-500 mr-2">{{ $cct->cantidad }}</span>
                                            <div class="w-20 bg-gray-200 rounded-full h-2">
                                                <div class="{{ $color }} h-2 rounded-full" style="width: {{ ($cct->cantidad / $totalTrabajadores) * 100 }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No hay datos disponibles</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Últimos trabajadores agregados -->
            <div class="bg-white shadow rounded-lg mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Últimos Trabajadores Agregados</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @forelse($ultimosTrabajadores as $trabajador)
                        <div class="px-6 py-4 flex items-center justify-between">
                            <div class="flex items-center">
                                @if($trabajador->Foto)
                                    <img src="{{ Storage::url($trabajador->Foto) }}" alt="{{ $trabajador->NombreCompleto }}" class="h-10 w-10 rounded-full object-cover mr-4">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center mr-4">
                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $trabajador->NombreCompleto }}</p>
                                    <p class="text-sm text-gray-500">{{ $trabajador->Puesto ?? 'Sin puesto' }} - {{ $trabajador->DNI_CUIL }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">{{ $trabajador->created_at->format('d/m/Y') }}</p>
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if($trabajador->Estado === 'Activo') bg-green-100 text-green-800
                                    @elseif($trabajador->Estado === 'Inactivo') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ $trabajador->Estado }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center">
                            <p class="text-gray-500">No hay trabajadores registrados</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Próximas vacaciones -->
            @if($proximasVacaciones->count() > 0)
            <div class="bg-white shadow rounded-lg mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-gray-900">Próximas Vacaciones (30 días)</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $proximasVacaciones->count() }} programadas
                        </span>
                    </div>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($proximasVacaciones as $vacacion)
                        <div class="px-6 py-4 flex items-center justify-between">
                            <div class="flex items-center">
                                @if($vacacion->trabajador->Foto)
                                    <img src="{{ Storage::url($vacacion->trabajador->Foto) }}" alt="{{ $vacacion->trabajador->NombreCompleto }}" class="h-10 w-10 rounded-full object-cover mr-4">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center mr-4">
                                        <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $vacacion->trabajador->NombreCompleto }}</p>
                                    <p class="text-sm text-gray-500">{{ $vacacion->trabajador->Puesto ?? 'Sin puesto' }} - {{ $vacacion->Dias_Solicitados }} días</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ $vacacion->Fecha_Inicio->format('d/m/Y') }} - {{ $vacacion->Fecha_Fin->format('d/m/Y') }}</p>
                                <p class="text-sm text-gray-500">
                                    @if($vacacion->Fecha_Inicio->isToday())
                                        Comienza hoy
                                    @elseif($vacacion->Fecha_Inicio->isTomorrow())
                                        Comienza mañana
                                    @else
                                        En {{ $vacacion->Fecha_Inicio->diffInDays(now()) }} días
                                    @endif
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="px-6 py-3 bg-gray-50">
                    <div class="text-sm">
                        <a href="{{ route('admin.vacaciones.requests') }}" wire:navigate class="font-medium text-blue-600 hover:text-blue-500">
                            Ver todas las solicitudes de vacaciones →
                        </a>
                    </div>
                </div>
            </div>
            @endif

            <!-- Acciones rápidas -->
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                <!-- Gestionar Trabajadores -->
                <div class="bg-white shadow-lg sm:rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
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

                <!-- Gestionar Usuarios -->
                <div class="bg-white shadow-lg sm:rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
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
                
                <!-- Solicitudes de Licencia -->
                <div class="bg-white shadow-lg sm:rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-lg font-medium text-gray-900 truncate">Licencias</dt>
                                    <dd class="text-sm text-gray-500">Revisar solicitudes</dd>
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

                <!-- Solicitudes de Vacaciones -->
                <div class="bg-white shadow-lg sm:rounded-lg overflow-hidden">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                               <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-lg font-medium text-gray-900 truncate">Vacaciones</dt>
                                    <dd class="text-sm text-gray-500">Revisar solicitudes</dd>
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
