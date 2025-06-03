<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-blue-800 dark:text-white-200 leading-tight">
            {{ __('Gestión de Solicitudes de Licencia') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                    
                    @if (session()->has('message'))
                        <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif

                    {{-- Filtros --}}
                    <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg shadow">
                        <h4 class="text-lg font-medium text-gray-700 dark:text-gray-200 mb-3">Filtrar Solicitudes</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label for="filtroEstado" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Estado</label>
                                <select wire:model.live="filtroEstado" id="filtroEstado" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">Todos</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="Aprobada">Aprobada</option>
                                    <option value="Rechazada">Rechazada</option>
                                </select>
                            </div>
                            <div>
                                <label for="filtroTrabajador" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Trabajador</label>
                                <select wire:model.live="filtroTrabajador" id="filtroTrabajador" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">Todos</option>
                                    @foreach($trabajadores as $trabajador)
                                        <option value="{{ $trabajador->ID_Trabajador }}">{{ $trabajador->NombreCompleto }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="filtroFechaDesde" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Desde Fecha Inicio</label>
                                <input type="date" wire:model.live="filtroFechaDesde" id="filtroFechaDesde" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200 rounded-md">
                            </div>
                            <div>
                                <label for="filtroFechaHasta" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Hasta Fecha Fin</label>
                                <input type="date" wire:model.live="filtroFechaHasta" id="filtroFechaHasta" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-gray-200 rounded-md">
                            </div>
                        </div>
                    </div>

                    {{-- Tabla de Solicitudes --}}
                    @if ($solicitudes && $solicitudes->count() > 0)
                        <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                    <tr>
                                        <th scope="col" class="py-3 px-6">Trabajador</th>
                                        <th scope="col" class="py-3 px-6">Tipo Licencia</th>
                                        <th scope="col" class="py-3 px-6">Fechas</th>
                                        <th scope="col" class="py-3 px-6">Días</th>
                                        <th scope="col" class="py-3 px-6">Motivo</th>
                                        <th scope="col" class="py-3 px-6">Certificado</th>
                                        <th scope="col" class="py-3 px-6">Estado</th>
                                        <th scope="col" class="py-3 px-6">Solicitada</th>
                                        <th scope="col" class="py-3 px-6">Gestor</th>
                                        <th scope="col" class="py-3 px-6">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($solicitudes as $solicitud)
                                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td class="py-4 px-6">{{ $solicitud->trabajador->NombreCompleto ?? 'N/A' }}</td>
                                            <td class="py-4 px-6">{{ $solicitud->TipoLicencia }}</td>
                                            <td class="py-4 px-6">{{ $solicitud->FechaInicio->format('d/m/Y') }} - {{ $solicitud->FechaFin->format('d/m/Y') }}</td>
                                            <td class="py-4 px-6">{{ $solicitud->CantidadDias }}</td>
                                            <td class="py-4 px-6">{{ Str::limit($solicitud->Motivo, 40) }}</td>
                                            <td class="py-4 px-6">
                                                @if($solicitud->Certificado)
                                                    <a href="{{ Storage::url($solicitud->Certificado) }}" target="_blank" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">Ver</a>
                                                @else
                                                    No
                                                @endif
                                            </td>
                                            <td class="py-4 px-6">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                    @switch($solicitud->Estado_Solicitud)
                                                        @case('Pendiente') bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100 @break
                                                        @case('Aprobada') bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100 @break
                                                        @case('Rechazada') bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100 @break
                                                        @default bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100
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
                                                    <button wire:click="abrirModalGestion({{ $solicitud->ID_Licencia }}, 'aprobar')" class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-md hover:bg-green-200 dark:bg-green-600 dark:text-green-100 dark:hover:bg-green-700">Aprobar</button>
                                                    <button wire:click="abrirModalGestion({{ $solicitud->ID_Licencia }}, 'rechazar')" class="ml-2 px-2 py-1 text-xs font-medium text-red-700 bg-red-100 rounded-md hover:bg-red-200 dark:bg-red-600 dark:text-red-100 dark:hover:bg-red-700">Rechazar</button>
                                                @else
                                                    @php
                                                        $accionRevisar = ($solicitud->Estado_Solicitud == 'Aprobada') ? 'rechazar' : 'aprobar';
                                                    @endphp
                                                    <button wire:click="abrirModalGestion({{ $solicitud->ID_Licencia }}, '{{ $accionRevisar }}')" class="px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 dark:bg-gray-600 dark:text-gray-100 dark:hover:bg-gray-500">Revisar</button>
                                                @endif
                                                 <button wire:click="$dispatch('open-detail-modal', { id: {{ $solicitud->ID_Licencia }} })" class="ml-2 px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 rounded-md hover:bg-blue-200 dark:bg-blue-600 dark:text-blue-100 dark:hover:bg-blue-700">Detalles</button>
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
                        <p class="text-gray-600 dark:text-gray-400">No hay solicitudes de licencia que coincidan con los filtros aplicados.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para Aprobar/Rechazar --}}
    <div x-data="{ open: false }" @open-gestion-modal.window="open = true" @close-gestion-modal.window="open = false" x-show="open" x-cloak class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-lg">
                <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100" id="modal-title">
                    Gestionar Solicitud de Licencia (<span x-text="$wire.accionModal === 'aprobar' ? 'Aprobar' : 'Rechazar'"></span>)
                </h3>
                <form wire:submit.prevent="gestionarSolicitud" class="mt-4 space-y-4">
                    <div>
                        <label for="comentariosAdmin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Comentarios del Administrador <span x-show="$wire.accionModal === 'rechazar'" class="text-red-500">* (requerido si rechaza)</span>
                        </label>
                        <textarea wire:model.defer="comentariosAdmin" id="comentariosAdmin" rows="4" class="mt-1 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 rounded-md focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                        @error('comentariosAdmin') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
    
    {{-- Modal para Detalles (se podría crear un componente aparte para esto) --}}
    <div x-data="{ openDetail: false, licenciaDetail: null }" 
         @open-detail-modal.window="openDetail = true; licenciaDetail = $event.detail.licenciaData; console.log($event.detail)" 
         @close-detail-modal.window="openDetail = false; licenciaDetail = null"
         x-show="openDetail" x-cloak 
         class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="detail-modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="openDetail" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div x-show="openDetail" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block w-full max-w-2xl p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white dark:bg-gray-800 shadow-xl rounded-lg">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100" id="detail-modal-title">
                        Detalles de la Solicitud
                    </h3>
                    <button @click="openDetail = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="mt-4" wire:ignore>
                    <div x-show="!licenciaDetail" class="text-center py-8">
                        <p class="text-gray-500 dark:text-gray-400">Cargando detalles...</p>
                    </div>
                    <div x-show="licenciaDetail">
                        <dl class="divide-y divide-gray-200 dark:divide-gray-700">
                            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Trabajador</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2" x-text="licenciaDetail?.trabajador_nombre"></dd>
                            </div>
                             <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2" x-text="licenciaDetail?.trabajador_email"></dd>
                            </div>
                            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tipo de Licencia</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2" x-text="licenciaDetail?.tipo_licencia"></dd>
                            </div>
                            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Fechas</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2"><span x-text="licenciaDetail?.fecha_inicio_formatted"></span> - <span x-text="licenciaDetail?.fecha_fin_formatted"></span> (<span x-text="licenciaDetail?.cantidad_dias"></span> días)</dd>
                            </div>
                            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Motivo</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2 whitespace-pre-wrap" x-text="licenciaDetail?.motivo"></dd>
                            </div>
                            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Certificado</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                                    <template x-if="licenciaDetail?.certificado_url">
                                        <a :href="licenciaDetail.certificado_url" target="_blank" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200">Ver Certificado</a>
                                    </template>
                                    <template x-if="!licenciaDetail?.certificado_url">
                                        <span>No adjuntado</span>
                                    </template>
                                </dd>
                            </div>
                            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Estado Solicitud</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" 
                                          :class="{
                                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100': licenciaDetail?.estado_solicitud === 'Pendiente',
                                            'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100': licenciaDetail?.estado_solicitud === 'Aprobada',
                                            'bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100': licenciaDetail?.estado_solicitud === 'Rechazada'
                                          }"
                                          x-text="licenciaDetail?.estado_solicitud">
                                    </span>
                                </dd>
                            </div>
                            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Fecha Solicitud</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2" x-text="licenciaDetail?.fecha_solicitud_formatted"></dd>
                            </div>
                            <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4" x-show="licenciaDetail?.aprobado_por_nombre">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Gestionado por</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2">
                                    <span x-text="licenciaDetail?.aprobado_por_nombre"></span>
                                    <template x-if="licenciaDetail?.fecha_aprobacion_rechazo_formatted">
                                        <span class="text-xs text-gray-500 dark:text-gray-400" x-text="' (' + licenciaDetail.fecha_aprobacion_rechazo_formatted + ')'"></span>
                                    </template>
                                </dd>
                            </div>
                             <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4" x-show="licenciaDetail?.comentarios_admin">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Comentarios Admin</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100 sm:mt-0 sm:col-span-2 whitespace-pre-wrap" x-text="licenciaDetail?.comentarios_admin"></dd>
                            </div>
                        </dl>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="openDetail = false" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('open-detail-modal', (event) => {
                // event.id proviene del $dispatch original en el botón de la tabla.
                // Ahora llamamos al método del componente Livewire para obtener los detalles.
                if (event.id) { // Asegurarse de que el id está presente en el evento inicial
                    @this.call('getLicenciaDetailsForModal', event.id).then(details => {
                        if (details) {
                            // Actualizamos la variable del componente Alpine y abrimos el modal.
                            // El componente Alpine x-data ya tiene 'openDetail' y 'licenciaDetail'
                            // Necesitamos que el componente Alpine escuche este evento también o pasar los datos directamente.
                            // Para simplificar, asumimos que el x-data ya está escuchando 'open-detail-modal'
                            // y que podemos actualizar sus datos directamente desde aquí si es necesario.
                            // Sin embargo, el x-data ya tiene la lógica para abrirse y setear licenciaDetail.
                            // Lo que haremos es disparar un nuevo evento con los datos completos.
                            let alpineComponent = document.querySelector('[x-data*="openDetail"]').__x;
                            alpineComponent.licenciaDetail = details;
                            alpineComponent.openDetail = true;
                        } else {
                            // Manejar caso donde no se encuentren detalles (e.g., alerta)
                            console.error('No se encontraron detalles para la licencia con ID:', event.id);
                            alert('No se pudieron cargar los detalles de la solicitud.');
                        }
                    }).catch(error => {
                        console.error('Error al cargar detalles de licencia:', error);
                        alert('Error al cargar los detalles de la solicitud.');
                    });
                } else if (event.licenciaData) {
                    // Esto es para el segundo dispatch que ya tiene los datos, manejado por Alpine.
                    // No es necesario hacer nada aquí si Alpine ya lo maneja.
                }
            });
        });
    </script>
</div>
