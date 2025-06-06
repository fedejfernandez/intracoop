<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Vacaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
                    
                    @if (session()->has('message'))
                        <div class="mb-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif

                    @if ($showNoTrabajadorMessage)
                        <div class="p-4 mb-4 text-sm text-yellow-800 rounded-lg bg-yellow-50" role="alert">
                            <span class="font-medium">Información:</span> No tienes un perfil de trabajador asociado a tu cuenta. Por favor, contacta con administración.
                        </div>
                    @else
                        <div class="mb-5 flex justify-between items-center">
                            <h3 class="text-2xl font-semibold text-gray-700">Historial de Vacaciones</h3>
                            <a href="{{ route('portal.vacaciones.solicitar') }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 disabled:opacity-25 transition">
                                {{ __('Solicitar Nuevas Vacaciones') }}
                            </a>
                        </div>
                        
                        <div class="mb-6 p-4 border border-blue-300 rounded-lg bg-blue-50">
                            <p class="text-sm text-blue-700">
                                <span class="font-semibold">Días de vacaciones anuales disponibles:</span> {{ $trabajador->DiasVacacionesAnuales ?? 'N/A' }}
                                {{-- Aquí podríamos añadir lógica para mostrar días restantes o consumidos si lo calculamos --}}
                            </p>
                        </div>

                        @if ($vacaciones && $vacaciones->count() > 0)
                            <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
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
                                            <tr class="bg-white border-b hover:bg-gray-50">
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
                                                        <button wire:click="confirmarCancelacion({{ $vacacion->ID_Vacaciones }})" class="px-2 py-1 text-xs font-medium text-red-600 bg-red-100 rounded-md hover:bg-red-200">
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
                            <p class="text-gray-600">No tienes vacaciones registradas por el momento.</p>
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
