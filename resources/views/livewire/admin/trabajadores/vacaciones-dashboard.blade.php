<div>
    <!-- Header del Dashboard -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-semibold text-gray-900">
                        Dashboard de Vacaciones - {{ $trabajador->NombreCompleto }}
                    </h2>
                    <p class="text-sm text-gray-600">
                        Legajo: {{ $trabajador->NumeroLegajo }} | 
                        Ingreso: {{ $trabajador->FechaIngreso ? $trabajador->FechaIngreso->format('d/m/Y') : 'N/A' }}
                    </p>
                </div>
                <div class="flex gap-3">
                    <select wire:model.live="anioSeleccionado" class="border-gray-300 rounded-md text-sm">
                        @foreach($historialAnios as $anio)
                            <option value="{{ $anio['anio'] }}">{{ $anio['anio'] }}</option>
                        @endforeach
                    </select>
                    <button wire:click="abrirModalNuevaSolicitud" 
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Nueva Solicitud
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen de Días -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-6">
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
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Días Asignados
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $resumenVacaciones['dias_asignados'] ?? 0 }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

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
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Días Utilizados
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $resumenVacaciones['dias_utilizados'] ?? 0 }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Días Pendientes
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $resumenVacaciones['dias_pendientes'] ?? 0 }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-indigo-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                Días Disponibles
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $resumenVacaciones['dias_libres'] ?? 0 }}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v4a2 2 0 01-2 2h-2a2 2 0 00-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                % Utilizado
                            </dt>
                            <dd class="text-lg font-medium text-gray-900">
                                {{ $resumenVacaciones['porcentaje_utilizado'] ?? 0 }}%
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Barra de Progreso -->
    <div class="bg-white shadow rounded-lg mb-6 p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Progreso de Vacaciones {{ $anioSeleccionado }}</h3>
        @php
            $total = $resumenVacaciones['dias_asignados'] ?? 1;
            $utilizados = $resumenVacaciones['dias_utilizados'] ?? 0;
            $pendientes = $resumenVacaciones['dias_pendientes'] ?? 0;
            $porcentajeUtilizados = $total > 0 ? ($utilizados / $total) * 100 : 0;
            $porcentajePendientes = $total > 0 ? ($pendientes / $total) * 100 : 0;
        @endphp
        
        <div class="w-full bg-gray-200 rounded-full h-6 mb-4">
            <div class="flex h-6 rounded-full overflow-hidden">
                <div class="bg-green-500 transition-all duration-300" style="width: {{ $porcentajeUtilizados }}%"></div>
                <div class="bg-yellow-500 transition-all duration-300" style="width: {{ $porcentajePendientes }}%"></div>
            </div>
        </div>
        
        <div class="flex justify-between text-sm text-gray-600">
            <span>🟢 Utilizados: {{ $utilizados }} días</span>
            <span>🟡 Pendientes: {{ $pendientes }} días</span>
            <span>🔵 Disponibles: {{ $resumenVacaciones['dias_libres'] ?? 0 }} días</span>
        </div>
    </div>

    <!-- Tabla de Vacaciones del Año -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                Vacaciones {{ $anioSeleccionado }}
            </h3>
        </div>
        
        @if(count($vacacionesDelAnio) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Período
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Días
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha Solicitud
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Comentarios
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($vacacionesDelAnio as $vacacion)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $vacacion->Fecha_Inicio ? $vacacion->Fecha_Inicio->format('d/m/Y') : 'N/A' }} - 
                                    {{ $vacacion->Fecha_Fin ? $vacacion->Fecha_Fin->format('d/m/Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $vacacion->Dias_Solicitados }} días
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                        @if($vacacion->Estado_Solicitud === 'Aprobada') bg-green-100 text-green-800
                                        @elseif($vacacion->Estado_Solicitud === 'Pendiente') bg-yellow-100 text-yellow-800
                                        @elseif($vacacion->Estado_Solicitud === 'Rechazada') bg-red-100 text-red-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ $vacacion->Estado_Solicitud }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $vacacion->created_at ? $vacacion->created_at->format('d/m/Y') : 'N/A' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                    {{ $vacacion->Comentarios_Admin ?: 'Sin comentarios' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        @if($vacacion->Estado_Solicitud === 'Pendiente')
                                            <button wire:click="aprobarVacacion({{ $vacacion->ID_Vacaciones }})"
                                                    class="text-green-600 hover:text-green-900">
                                                Aprobar
                                            </button>
                                            <button wire:click="rechazarVacacion({{ $vacacion->ID_Vacaciones }})"
                                                    class="text-red-600 hover:text-red-900">
                                                Rechazar
                                            </button>
                                        @endif
                                        <button wire:click="eliminarVacacion({{ $vacacion->ID_Vacaciones }})"
                                                wire:confirm="¿Está seguro de eliminar esta vacación?"
                                                class="text-red-600 hover:text-red-900">
                                            Eliminar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-4 0V7"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No hay vacaciones registradas</h3>
                <p class="mt-1 text-sm text-gray-500">No se encontraron vacaciones para el año {{ $anioSeleccionado }}.</p>
            </div>
        @endif
    </div>

    <!-- Historial por Años -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                Historial de Vacaciones por Año
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Año
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Asignados
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Utilizados
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Pendientes
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Disponibles
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            % Utilizado
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($historialAnios as $anio)
                        <tr class="hover:bg-gray-50 {{ $anio['anio'] == $anioSeleccionado ? 'bg-blue-50' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $anio['anio'] }}
                                @if($anio['anio'] == $anioSeleccionado)
                                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Actual
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $anio['dias_asignados'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $anio['dias_utilizados'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $anio['dias_pendientes'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $anio['dias_libres'] }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="flex items-center">
                                    <span class="mr-2">{{ $anio['porcentaje_utilizado'] }}%</span>
                                    <div class="w-16 bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-500 h-2 rounded-full transition-all duration-300" 
                                             style="width: {{ min(100, $anio['porcentaje_utilizado']) }}%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Nueva Solicitud -->
    @if($showModalNuevaSolicitud)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit.prevent="crearSolicitudVacaciones">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Nueva Solicitud de Vacaciones
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Fecha de Inicio</label>
                                        <input type="date" wire:model.live="fechaInicio" 
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        @error('fechaInicio') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Fecha de Fin</label>
                                        <input type="date" wire:model.live="fechaFin" 
                                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                        @error('fechaFin') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    
                                    @if($diasCalculados > 0)
                                    <div class="bg-blue-50 border border-blue-200 rounded-md p-3">
                                        <p class="text-sm text-blue-800">
                                            <strong>Días laborables calculados:</strong> {{ $diasCalculados }}
                                        </p>
                                        <p class="text-xs text-blue-600 mt-1">
                                            Días disponibles: {{ $resumenVacaciones['dias_libres'] ?? 0 }}
                                        </p>
                                    </div>
                                    @endif
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Comentarios (Opcional)</label>
                                        <textarea wire:model="comentariosAdmin" rows="3"
                                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                  placeholder="Comentarios adicionales sobre la solicitud..."></textarea>
                                        @error('comentariosAdmin') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" 
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Crear Solicitud
                        </button>
                        <button type="button" wire:click="cerrarModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancelar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Mensajes de éxito -->
    @if (session()->has('message'))
        <div class="fixed bottom-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded shadow-lg" role="alert">
            <div class="flex">
                <div class="py-1">
                    <svg class="fill-current h-6 w-6 text-green-500 mr-4" viewBox="0 0 20 20">
                        <path d="M2.93 17.07A10 10 0 1 1 17.07 2.93 10 10 0 0 1 2.93 17.07zm12.73-1.41A8 8 0 1 0 4.34 4.34a8 8 0 0 0 11.32 11.32zM9 11V9h2v6H9v-4zm0-6h2v2H9V5z"/>
                    </svg>
                </div>
                <div>
                    <p class="font-bold">¡Éxito!</p>
                    <p class="text-sm">{{ session('message') }}</p>
                </div>
            </div>
        </div>
        
        <script>
            setTimeout(() => {
                const flashMessage = document.querySelector('[role="alert"]');
                if (flashMessage) {
                    flashMessage.style.display = 'none';
                }
            }, 5000);
        </script>
    @endif
</div> 