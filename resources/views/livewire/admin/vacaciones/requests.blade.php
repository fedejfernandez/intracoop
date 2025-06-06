<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-blue-800 leading-tight">
            {{ __('Gestión de Solicitudes de Vacaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    @if (session()->has('message'))
                        <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif

                    {{-- Filtros --}}
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg shadow">
                        <h4 class="text-lg font-medium text-gray-700 mb-3">Filtrar Solicitudes</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label for="filtroEstadoVac" class="block text-sm font-medium text-gray-700">Estado</label>
                                <select wire:model.live="filtroEstado" id="filtroEstadoVac" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">Todos</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="Aprobada">Aprobada</option>
                                    <option value="Rechazada">Rechazada</option>
                                </select>
                            </div>
                            <div>
                                <label for="filtroTrabajadorVac" class="block text-sm font-medium text-gray-700">Trabajador</label>
                                <select wire:model.live="filtroTrabajador" id="filtroTrabajadorVac" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">Todos</option>
                                    @foreach($trabajadores as $trabajador)
                                        <option value="{{ $trabajador->ID_Trabajador }}">{{ $trabajador->NombreCompleto }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="filtroFechaDesdeVac" class="block text-sm font-medium text-gray-700">Desde Fecha Inicio</label>
                                <input type="date" wire:model.live="filtroFechaDesde" id="filtroFechaDesdeVac" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label for="filtroFechaHastaVac" class="block text-sm font-medium text-gray-700">Hasta Fecha Fin</label>
                                <input type="date" wire:model.live="filtroFechaHasta" id="filtroFechaHastaVac" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                    </div>

                    {{-- Tabla de Solicitudes --}}
                    @if ($solicitudes && $solicitudes->count() > 0)
                        <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th scope="col" class="py-3 px-6">Trabajador</th>
                                        <th scope="col" class="py-3 px-6">Fechas</th>
                                        <th scope="col" class="py-3 px-6">Días Solicitados</th>
                                        <th scope="col" class="py-3 px-6">Estado</th>
                                        <th scope="col" class="py-3 px-6">Solicitada</th>
                                        <th scope="col" class="py-3 px-6">Gestor</th>
                                        <th scope="col" class="py-3 px-6">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($solicitudes as $solicitud)
                                        <tr class="bg-white border-b hover:bg-gray-50">
                                            <td class="py-4 px-6">{{ $solicitud->trabajador->NombreCompleto ?? 'N/A' }}</td>
                                            <td class="py-4 px-6">{{ $solicitud->Fecha_Inicio->format('d/m/Y') }} - {{ $solicitud->Fecha_Fin->format('d/m/Y') }}</td>
                                            <td class="py-4 px-6">{{ $solicitud->Dias_Solicitados }}</td>
                                            <td class="py-4 px-6">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @switch($solicitud->Estado_Solicitud)
                                                        @case('Pendiente') bg-yellow-100 text-yellow-800 @break
                                                        @case('Aprobada') bg-green-100 text-green-800 @break
                                                        @case('Rechazada') bg-red-100 text-red-800 @break
                                                        @default bg-gray-100 text-gray-800
                                                    @endswitch
                                                ">
                                                    {{ $solicitud->Estado_Solicitud }}
                                                </span>
                                            </td>
                                            <td class="py-4 px-6">{{ $solicitud->created_at->format('d/m/Y H:i') }}</td>
                                            <td class="py-4 px-6">
                                                {{ $solicitud->aprobador->name ?? '-' }}<br>
                                                @if($solicitud->Fecha_Aprobacion_Rechazo)
                                                    <span class="text-xs text-gray-500">{{ $solicitud->Fecha_Aprobacion_Rechazo->format('d/m/Y H:i') }}</span>
                                                @endif
                                            </td>
                                            <td class="py-4 px-6 whitespace-nowrap">
                                                @if($solicitud->Estado_Solicitud == 'Pendiente')
                                                    <button wire:click="abrirModalGestion({{ $solicitud->ID_Vacaciones }}, 'aprobar')" class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-md hover:bg-green-200">Aprobar</button>
                                                    <button wire:click="abrirModalGestion({{ $solicitud->ID_Vacaciones }}, 'rechazar')" class="ml-2 px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-md hover:bg-red-200">Rechazar</button>
                                                @else
                                                    @php
                                                        $accionRevisar = ($solicitud->Estado_Solicitud == 'Aprobada') ? 'rechazar' : 'aprobar';
                                                    @endphp
                                                    <button wire:click="abrirModalGestion({{ $solicitud->ID_Vacaciones }}, '{{ $accionRevisar }}')" class="px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">Revisar</button>
                                                @endif
                                                <button wire:click="$dispatch('open-detail-modal-vacaciones', { id: {{ $solicitud->ID_Vacaciones }} })" class="ml-2 px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200">Detalles</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-4">
                            {{ $solicitudes->links() }}
                        </div>
                    @else
                        <p class="text-gray-600">No hay solicitudes de vacaciones que coincidan con los filtros aplicados.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para Aprobar/Rechazar --}}
    <div x-data="{ open: false }" @open-gestion-modal.window="open = true" @close-gestion-modal.window="open = false" x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title-vac" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title-vac">
                    Gestionar Solicitud de Vacaciones (<span x-text="$wire.accionModal === 'aprobar' ? 'Aprobar' : 'Rechazar'"></span>)
                </h3>
                <form wire:submit.prevent="gestionarSolicitud" class="mt-4 space-y-4">
                    <div>
                        <label for="comentariosAdminVac" class="block text-sm font-medium text-gray-700">
                            Comentarios del Administrador <span x-show="$wire.accionModal === 'rechazar'" class="text-red-500">* (requerido si rechaza)</span>
                        </label>
                        <textarea wire:model.defer="comentariosAdmin" id="comentariosAdminVac" rows="4" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        @error('comentariosAdmin') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancelar
                        </button>
                        <button type="submit" wire:loading.attr="disabled"
                                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white border border-transparent rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2"
                                :class="{ 'bg-green-600 hover:bg-green-700 focus:ring-green-500': $wire.accionModal === 'aprobar', 'bg-red-600 hover:bg-red-700 focus:ring-red-500': $wire.accionModal === 'rechazar' }">
                            <span wire:loading wire:target="gestionarSolicitud" class="mr-2">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </span>
                            <span x-text="$wire.accionModal === 'aprobar' ? 'Confirmar Aprobación' : 'Confirmar Rechazo'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal para Detalles de Vacaciones --}}
    <div x-data="{ 
            openDetailVacaciones: false, 
            vacacionDetail: null,
            abrirModal(data) {
                this.vacacionDetail = data;
                this.openDetailVacaciones = true;
                console.log('Modal abierto con datos:', data);
            }
         }" 
         @open-detail-modal-vacaciones.window="
            if ($event.detail.vacacionData) {
                abrirModal($event.detail.vacacionData);
            } else if ($event.detail.id) {
                console.log('Solicitando datos para ID:', $event.detail.id);
            }
         "
         @vacacion-details-loaded.window="abrirModal($event.detail)"
         @close-detail-modal-vacaciones.window="openDetailVacaciones = false; vacacionDetail = null"
         x-show="openDetailVacaciones" x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="detail-modal-title-vac" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="openDetailVacaciones" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="openDetailVacaciones" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white shadow-xl rounded-lg">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium leading-6 text-gray-900" id="detail-modal-title-vac">
                        Detalles de la Solicitud de Vacaciones
                    </h3>
                    <button @click="openDetailVacaciones = false" class="text-gray-400 hover:text-gray-600">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="mt-4" wire:ignore>
                    <div x-show="!vacacionDetail" class="text-center py-8">
                        <p class="text-gray-500">Cargando detalles...</p>
                    </div>
                    <div x-show="vacacionDetail">
                        <dl class="divide-y divide-gray-200">
                            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Trabajador</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2" x-text="vacacionDetail?.trabajador_nombre"></dd>
                            </div>
                             <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2" x-text="vacacionDetail?.trabajador_email"></dd>
                            </div>
                            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Fechas Solicitadas</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><span x-text="vacacionDetail?.fecha_inicio_formatted"></span> - <span x-text="vacacionDetail?.fecha_fin_formatted"></span></dd>
                            </div>
                            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Días Laborables Solicitados</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2" x-text="vacacionDetail?.dias_solicitados"></dd>
                            </div>
                            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Estado Solicitud</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                                          :class="{
                                            'bg-yellow-100 text-yellow-800': vacacionDetail?.estado_solicitud === 'Pendiente',
                                            'bg-green-100 text-green-800': vacacionDetail?.estado_solicitud === 'Aprobada',
                                            'bg-red-100 text-red-800': vacacionDetail?.estado_solicitud === 'Rechazada'
                                          }"
                                          x-text="vacacionDetail?.estado_solicitud">
                                    </span>
                                </dd>
                            </div>
                            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500">Fecha Solicitud</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2" x-text="vacacionDetail?.fecha_solicitud_formatted"></dd>
                            </div>
                            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4" x-show="vacacionDetail?.aprobado_por_nombre">
                                <dt class="text-sm font-medium text-gray-500">Gestionado por</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                    <span x-text="vacacionDetail?.aprobado_por_nombre"></span>
                                    <template x-if="vacacionDetail?.fecha_aprobacion_rechazo_formatted">
                                        <span class="text-xs text-gray-500" x-text="' (' + vacacionDetail.fecha_aprobacion_rechazo_formatted + ')'"></span>
                                    </template>
                                </dd>
                            </div>
                             <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4" x-show="vacacionDetail?.comentarios_admin">
                                <dt class="text-sm font-medium text-gray-500">Comentarios Admin</dt>
                                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 whitespace-pre-wrap" x-text="vacacionDetail?.comentarios_admin"></dd>
                            </div>
                        </dl>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="openDetailVacaciones = false" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('open-detail-modal-vacaciones', (event) => {
                console.log('Event recibido:', event);
                
                if (event.id) { 
                    console.log('Llamando getVacacionDetailsForModal con ID:', event.id);
                    
                    @this.call('getVacacionDetailsForModal', event.id).then(details => {
                        console.log('Detalles recibidos:', details);
                        
                        if (details) {
                            // Usar evento de ventana para comunicarse con Alpine.js
                            window.dispatchEvent(new CustomEvent('vacacion-details-loaded', {
                                detail: details
                            }));
                        } else {
                            console.error('No se encontraron detalles para la vacación con ID:', event.id);
                            alert('No se pudieron cargar los detalles de la solicitud.');
                        }
                    }).catch(error => {
                        console.error('Error al cargar detalles de vacación:', error);
                        alert('Error al cargar los detalles de la solicitud: ' + error.message);
                    });
                } else if (event.vacacionData) {
                    console.log('Datos directos recibidos:', event.vacacionData);
                    // Los datos directos ya se manejan en Alpine.js
                }
            });
        });
    </script>
</div>
