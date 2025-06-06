<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-blue-800 leading-tight">
            {{ __('Dashboard de Vacaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Header con selector de año -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Dashboard de Vacaciones</h3>
                            <p class="text-sm text-gray-600">Analíticas y estadísticas del sistema de vacaciones</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <select wire:model.live="anioSeleccionado" class="border-gray-300 rounded-md shadow-sm">
                                @for($year = date('Y') + 1; $year >= 2020; $year--)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                            <button wire:click="exportarReporte" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Exportar Reporte
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Estadísticas Generales -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Solicitudes -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Total Solicitudes</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $estadisticasGenerales['total_solicitudes'] ?? 0 }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Porcentaje Aprobación -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">% Aprobación</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $estadisticasGenerales['porcentaje_aprobacion'] ?? 0 }}%</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Días Aprobados -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-4 0V7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Días Aprobados</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $estadisticasGenerales['dias_aprobados'] ?? 0 }}</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trabajadores con Vacaciones -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="p-5">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-indigo-500 rounded-md flex items-center justify-center">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">% Trabajadores</dt>
                                    <dd class="text-lg font-medium text-gray-900">{{ $estadisticasGenerales['porcentaje_trabajadores_con_vacaciones'] ?? 0 }}%</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfico de Vacaciones por Mes -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Vacaciones por Mes ({{ $anioSeleccionado }})</h3>
                    <div class="overflow-x-auto">
                        <div class="min-w-full">
                            <div class="flex items-end space-x-2 h-64">
                                @foreach($estadisticasPorMes as $mes)
                                    <div class="flex-1 flex flex-col items-center">
                                        <div class="flex flex-col items-center space-y-1 w-full">
                                            @php
                                                $maxDias = collect($estadisticasPorMes)->max('total_dias');
                                                $altura = $maxDias > 0 ? ($mes['total_dias'] / $maxDias) * 200 : 0;
                                            @endphp
                                            <div class="bg-blue-500 rounded-t" style="height: {{ $altura }}px; width: 100%;" title="Días: {{ $mes['total_dias'] }}"></div>
                                            <div class="text-xs text-center text-gray-600 mt-2">
                                                <div class="font-medium">{{ substr($mes['mes'], 0, 3) }}</div>
                                                <div class="text-gray-500">{{ $mes['total_dias'] }}d</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grid de dos columnas -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                
                <!-- Solicitudes Pendientes -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Solicitudes Pendientes</h3>
                        @if(count($solicitudesPendientes) > 0)
                            <div class="space-y-3">
                                @foreach($solicitudesPendientes as $solicitud)
                                    <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $solicitud['trabajador']['NombreCompleto'] ?? 'N/A' }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ $solicitud['Dias_Solicitados'] }} días | 
                                                {{ \Carbon\Carbon::parse($solicitud['Fecha_Inicio'])->format('d/m/Y') }} - 
                                                {{ \Carbon\Carbon::parse($solicitud['Fecha_Fin'])->format('d/m/Y') }}
                                            </div>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pendiente
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No hay solicitudes pendientes.</p>
                        @endif
                    </div>
                </div>

                <!-- Próximas Vacaciones -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Próximas Vacaciones</h3>
                        @if(count($proximasVacaciones) > 0)
                            <div class="space-y-3">
                                @foreach($proximasVacaciones as $vacacion)
                                    <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $vacacion['trabajador']['NombreCompleto'] ?? 'N/A' }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ $vacacion['Dias_Solicitados'] }} días | 
                                                {{ \Carbon\Carbon::parse($vacacion['Fecha_Inicio'])->format('d/m/Y') }} - 
                                                {{ \Carbon\Carbon::parse($vacacion['Fecha_Fin'])->format('d/m/Y') }}
                                            </div>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Aprobada
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">No hay vacaciones próximas programadas.</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Estadísticas por Sector -->
            @if(count($estadisticasPorSector) > 0)
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Vacaciones por Sector</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sector</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Solicitudes</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Días</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aprobadas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">% Aprobación</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($estadisticasPorSector as $sector)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $sector['Sector'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $sector['total_solicitudes'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $sector['total_dias'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $sector['aprobadas'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $sector['total_solicitudes'] > 0 ? round(($sector['aprobadas'] / $sector['total_solicitudes']) * 100, 1) : 0 }}%
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            <!-- Conflictos Potenciales -->
            @if(count($conflictosPotenciales) > 0)
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">⚠️ Conflictos Potenciales</h3>
                    <div class="space-y-3">
                        @foreach($conflictosPotenciales as $conflicto)
                            <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h4 class="text-sm font-medium text-red-800">
                                            {{ $conflicto['cantidad_trabajadores'] }} trabajadores con vacaciones simultáneas
                                        </h4>
                                        <div class="mt-1 text-sm text-red-700">
                                            <strong>Fechas:</strong> {{ \Carbon\Carbon::parse($conflicto['Fecha_Inicio'])->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($conflicto['Fecha_Fin'])->format('d/m/Y') }}
                                        </div>
                                        <div class="mt-1 text-sm text-red-700">
                                            <strong>Trabajadores:</strong> {{ $conflicto['trabajadores_nombres'] }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>
</div> 