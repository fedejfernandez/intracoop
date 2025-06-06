<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-blue-800 leading-tight">
            {{ __('Calendario de Vacaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                
                <!-- Header del Calendario -->
                <div class="p-6 border-b border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <div class="flex items-center space-x-4">
                            <button wire:click="mesAnterior" class="p-2 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </button>
                            <h3 class="text-2xl font-semibold text-gray-900">
                                {{ $this->obtenerNombreMes() }} {{ $anioActual }}
                            </h3>
                            <button wire:click="mesSiguiente" class="p-2 text-gray-400 hover:text-gray-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </button>
                        </div>
                        
                        <div class="flex items-center space-x-4">
                            <select wire:model.live="trabajadorSeleccionado" class="border-gray-300 rounded-md shadow-sm">
                                <option value="">Todos los trabajadores</option>
                                @foreach($trabajadores as $trabajador)
                                    <option value="{{ $trabajador->ID_Trabajador }}">{{ $trabajador->NombreCompleto }}</option>
                                @endforeach
                            </select>
                            
                            <button wire:click="$set('mesActual', {{ date('n') }})" wire:click="$set('anioActual', {{ date('Y') }})" 
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Hoy
                            </button>
                        </div>
                    </div>

                    <!-- Leyenda -->
                    <div class="flex items-center space-x-6 text-sm">
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-green-500 rounded mr-2"></div>
                            <span>Vacaciones Aprobadas</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-blue-500 rounded mr-2"></div>
                            <span>Hoy</span>
                        </div>
                        <div class="flex items-center">
                            <div class="w-3 h-3 bg-gray-200 rounded mr-2"></div>
                            <span>Fin de Semana</span>
                        </div>
                    </div>
                </div>

                <!-- Calendario -->
                <div class="p-6">
                    <!-- Días de la semana -->
                    <div class="grid grid-cols-7 gap-1 mb-2">
                        @foreach(['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $dia)
                            <div class="p-3 text-center text-sm font-medium text-gray-700 bg-gray-50">
                                {{ $dia }}
                            </div>
                        @endforeach
                    </div>

                    <!-- Días del mes -->
                    <div class="grid grid-cols-7 gap-1">
                        @foreach($diasDelMes as $dia)
                            @php
                                $clases = 'min-h-[80px] p-2 border border-gray-200 cursor-pointer transition-colors ';
                                
                                if (!$dia['es_del_mes']) {
                                    $clases .= 'bg-gray-50 text-gray-400 ';
                                } elseif ($dia['es_hoy']) {
                                    $clases .= 'bg-blue-100 border-blue-500 ';
                                } elseif ($dia['es_fin_de_semana']) {
                                    $clases .= 'bg-gray-100 ';
                                } else {
                                    $clases .= 'bg-white hover:bg-gray-50 ';
                                }
                            @endphp
                            
                            <div class="{{ $clases }}" 
                                 wire:click="abrirModalDia('{{ $dia['fecha']->format('Y-m-d') }}', {{ json_encode($dia['vacaciones']) }})">
                                
                                <!-- Número del día -->
                                <div class="text-sm font-medium {{ $dia['es_hoy'] ? 'text-blue-800' : 'text-gray-900' }}">
                                    {{ $dia['dia'] }}
                                </div>
                                
                                <!-- Vacaciones del día -->
                                @if($dia['cantidad_vacaciones'] > 0)
                                    <div class="mt-1 space-y-1">
                                        @foreach(array_slice($dia['vacaciones'], 0, 2) as $vacacion)
                                            <div class="text-xs bg-green-100 text-green-800 px-1 py-0.5 rounded truncate">
                                                {{ $vacacion['trabajador']['NombreCompleto'] ?? 'N/A' }}
                                            </div>
                                        @endforeach
                                        
                                        @if($dia['cantidad_vacaciones'] > 2)
                                            <div class="text-xs text-gray-500">
                                                +{{ $dia['cantidad_vacaciones'] - 2 }} más
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para detalles del día -->
    @if($showModalDia)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Vacaciones del {{ $diaSeleccionado ? \Carbon\Carbon::parse($diaSeleccionado)->format('d/m/Y') : '' }}
                        </h3>
                        <button wire:click="cerrarModalDia" class="text-gray-400 hover:text-gray-600">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    @if(count($vacacionesDelDia) > 0)
                        <div class="space-y-3">
                            @foreach($vacacionesDelDia as $vacacion)
                                <div class="p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="font-medium text-gray-900">
                                                {{ $vacacion['trabajador']['NombreCompleto'] ?? 'N/A' }}
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                {{ $vacacion['trabajador']['Sector'] ?? 'Sin sector' }}
                                            </div>
                                            <div class="text-sm text-gray-500 mt-1">
                                                Período: {{ \Carbon\Carbon::parse($vacacion['Fecha_Inicio'])->format('d/m/Y') }} - 
                                                         {{ \Carbon\Carbon::parse($vacacion['Fecha_Fin'])->format('d/m/Y') }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                Días: {{ $vacacion['Dias_Solicitados'] }}
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
                        <p class="text-gray-500">No hay vacaciones programadas para este día.</p>
                    @endif
                </div>
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button wire:click="cerrarModalDia" type="button" 
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div> 