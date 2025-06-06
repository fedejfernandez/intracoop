<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Mis Licencias') }}
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
                            <h3 class="text-2xl font-semibold text-gray-700">Historial de Licencias</h3>
                            <a href="{{ route('portal.licencias.solicitar') }}" wire:navigate class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:border-indigo-700 focus:ring focus:ring-indigo-200 disabled:opacity-25 transition">
                                {{ __('Solicitar Nueva Licencia') }}
                            </a>
                        </div>

                        @if ($licencias && $licencias->count() > 0)
                            <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
                                <table class="w-full text-sm text-left text-gray-500">
                                    <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                        <tr>
                                            <th scope="col" class="py-3 px-6">Tipo</th>
                                            <th scope="col" class="py-3 px-6">Fecha Inicio</th>
                                            <th scope="col" class="py-3 px-6">Fecha Fin</th>
                                            <th scope="col" class="py-3 px-6">Días</th>
                                            <th scope="col" class="py-3 px-6">Estado</th>
                                            <th scope="col" class="py-3 px-6">Motivo</th>
                                            <th scope="col" class="py-3 px-6">Solicitada</th>
                                            <th scope="col" class="py-3 px-6">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($licencias as $licencia)
                                            <tr class="bg-white border-b hover:bg-gray-50">
                                                <td class="py-4 px-6">{{ $licencia->TipoLicencia }}</td>
                                                <td class="py-4 px-6">{{ $licencia->FechaInicio->format('d/m/Y') }}</td>
                                                <td class="py-4 px-6">{{ $licencia->FechaFin->format('d/m/Y') }}</td>
                                                <td class="py-4 px-6">{{ $licencia->CantidadDias }}</td>
                                                <td class="py-4 px-6">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        @switch($licencia->Estado_Solicitud)
                                                            @case('Pendiente') bg-yellow-100 text-yellow-800 @break
                                                            @case('Aprobada') bg-green-100 text-green-800 @break
                                                            @case('Rechazada') bg-red-100 text-red-800 @break
                                                            @default bg-gray-100 text-gray-800
                                                        @endswitch
                                                    ">
                                                        {{ $licencia->Estado_Solicitud }}
                                                    </span>
                                                </td>
                                                <td class="py-4 px-6">{{ Str::limit($licencia->Motivo, 50) }}</td>
                                                <td class="py-4 px-6">{{ $licencia->created_at->format('d/m/Y H:i') }}</td>
                                                <td class="py-4 px-6 whitespace-nowrap">
                                                    @if($licencia->Estado_Solicitud == 'Pendiente')
                                                        <button wire:click="confirmarCancelacion({{ $licencia->ID_Licencia }})" class="px-2 py-1 text-xs font-medium text-red-600 bg-red-100 rounded-md hover:bg-red-200">
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
                                {{ $licencias->links() }}
                            </div>
                        @else
                            <p class="text-gray-600">No tienes licencias registradas por el momento.</p>
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
        Livewire.on('confirm-cancelacion-licencia', (event) => {
            // Aquí usarías tu librería de modales preferida, ej. SweetAlert2
            // Por simplicidad, usaré window.confirm
            if (confirm(event.message)) {
                @this.call('cancelarLicencia', event.id);
            }
        });
    });
</script>
@endpush
