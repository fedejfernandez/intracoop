<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mis Vacaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                    
                    @if (session()->has('message'))
                        <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif

                    @if ($showNoTrabajadorMessage)
                        <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300" role="alert">
                            <span class="font-medium">Información:</span> No tienes un perfil de trabajador asociado a tu cuenta. Por favor, contacta con administración.
                        </div>
                    @else
                        <div class="mb-5 flex justify-between items-center">
                            <h3 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Historial de Vacaciones</h3>
                            <a href="{{ route('portal.vacaciones.solicitar') }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 disabled:opacity-25 transition">
                                {{ __('Solicitar Nuevas Vacaciones') }}
                            </a>
                        </div>
                        
                        <div class="mb-6 p-4 border border-blue-300 dark:border-blue-600 rounded-lg bg-blue-50 dark:bg-gray-700">
                            <p class="text-sm text-blue-700 dark:text-blue-300">
                                <span class="font-semibold">Días de vacaciones anuales disponibles:</span> {{ $trabajador->DiasVacacionesAnuales ?? 'N/A' }}
                                {{-- Aquí podríamos añadir lógica para mostrar días restantes o consumidos si lo calculamos --}}
                            </p>
                        </div>

                        @if ($vacaciones && $vacaciones->count() > 0)
                            <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                                <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                        <tr>
                                            <th scope="col" class="py-3 px-6">Fecha Inicio</th>
                                            <th scope="col" class="py-3 px-6">Fecha Fin</th>
                                            <th scope="col" class="py-3 px-6">Días Solicitados</th>
                                            <th scope="col" class="py-3 px-6">Estado</th>
                                            <th scope="col" class="py-3 px-6">Solicitadas</th>
                                            <th scope="col" class="py-3 px-6">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($vacaciones as $vacacion)
                                            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                                <td class="py-4 px-6">{{ $vacacion->Fecha_Inicio->format('d/m/Y') }}</td>
                                                <td class="py-4 px-6">{{ $vacacion->Fecha_Fin->format('d/m/Y') }}</td>
                                                <td class="py-4 px-6">{{ $vacacion->Dias_Solicitados }}</td>
                                                <td class="py-4 px-6">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        @switch($vacacion->Estado_Solicitud)
                                                            @case('Pendiente') bg-yellow-100 text-yellow-800 @break
                                                            @case('Aprobada') bg-green-100 text-green-800 @break
                                                            @case('Rechazada') bg-red-100 text-red-800 @break
                                                            @default bg-gray-100 text-gray-800
                                                        @endswitch
                                                    ">
                                                        {{ $vacacion->Estado_Solicitud }}
                                                    </span>
                                                </td>
                                                <td class="py-4 px-6">{{ $vacacion->created_at->format('d/m/Y H:i') }}</td>
                                                <td class="py-4 px-6 whitespace-nowrap">
                                                    @if($vacacion->Estado_Solicitud == 'Pendiente')
                                                        <button wire:click="confirmarCancelacion({{ $vacacion->ID_Vacaciones }})" class="px-2 py-1 text-xs font-medium text-red-600 bg-red-100 rounded-md hover:bg-red-200 dark:bg-red-700 dark:text-red-100 dark:hover:bg-red-600">
                                                            Cancelar
                                                        </button>
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="mt-4">
                                {{ $vacaciones->links() }}
                            </div>
                        @else
                            <p class="text-gray-600 dark:text-gray-400">No tienes vacaciones registradas por el momento.</p>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('confirm-cancelacion-vacacion', (event) => {
            if (confirm(event.message)) {
                @this.call('cancelarVacacion', event.id);
            }
        });
    });
</script>
@endpush
