<div class="container px-4 py-8 mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-semibold text-gray-800">Historial Laboral de: <span class="text-indigo-600">{{ $trabajador->NombreCompleto ?? 'N/A' }}</span></h1>
            <p class="text-sm text-gray-600">Legajo: {{ $trabajador->NumeroLegajo ?? 'N/A' }}</p>
        </div>
        <button wire:click="abrirModalDeHistorial" class="px-4 py-2 font-medium tracking-wide text-white capitalize transition-colors duration-200 transform bg-indigo-600 rounded-md hover:bg-indigo-500 focus:outline-none focus:ring focus:ring-indigo-300 focus:ring-opacity-80">
            <i class="mr-2 fas fa-plus"></i> Agregar Evento
        </button>
    </div>

    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
            {{ session('error') }}
        </div>
    @endif

    {{-- El resto del contenido (tabla y modal) permanece comentado --}}
    {{--
    <!-- Tabla de Historial Laboral -->
    <div class="mt-8 flex flex-col">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    @if ($historial && $historial->count() > 0)
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">Fecha Evento</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Tipo Evento</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Descripción Sencilla</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Registrado Por</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach ($historial as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                            {{ $item->fecha_evento ? $item->fecha_evento->format('d/m/Y') : 'N/A' }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">{{ $item->tipo_evento }}</td>
                                        <td class="px-3 py-4 text-sm text-gray-700">
                                            {{ Str::limit($item->descripcion, 50) }}
                                        </td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-700">
                                            {{ $item->registradoPor->name ?? 'Sistema' }}
                                            <br><span class="text-xs text-gray-500">{{ $item->created_at ? $item->created_at->format('d/m/Y H:i') : '' }}</span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if ($historial->hasPages())
                            <div class="px-4 py-3">
                                {{ $historial->links() }}
                            </div>
                        @endif
                    @else
                        <p class="p-4 text-center text-gray-500">No hay eventos en el historial laboral para mostrar.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal para Agregar Evento --}}
    @if($showAddModal)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" wire:click="closeAddModal"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6">
                    <form wire:submit.prevent="saveHistorial">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg font-medium leading-6 text-gray-900" id="modal-title">
                                    Agregar Nuevo Evento al Historial Laboral
                                </h3>
                                <div class="mt-4 space-y-4">
                                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                        <div>
                                            <label for="fecha_evento" class="block text-sm font-medium text-gray-700">Fecha del Evento</label>
                                            <input type="date" wire:model.defer="fecha_evento" id="fecha_evento" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            @error('fecha_evento') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label for="tipo_evento" class="block text-sm font-medium text-gray-700">Tipo de Evento</label>
                                            <select wire:model.defer="tipo_evento" id="tipo_evento" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                <option value="">Seleccione un tipo</option>
                                                @foreach($tiposEventoComunes as $tipo)
                                                    <option value="{{ $tipo }}">{{ $tipo }}</option>
                                                @endforeach
                                            </select>
                                            @error('tipo_evento') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label for="descripcion" class="block text-sm font-medium text-gray-700">Descripción (Opcional)</label>
                                        <textarea wire:model.defer="descripcion" id="descripcion" rows="2" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                                        @error('descripcion') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                    </div>

                                    <h4 class="text-md font-medium text-gray-800 pt-2 border-t">Cambios (Opcional)</h4>
                                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                        <div>
                                            <label for="puesto_anterior" class="block text-sm font-medium text-gray-700">Puesto Anterior</label>
                                            <input type="text" wire:model.defer="puesto_anterior" id="puesto_anterior" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            @error('puesto_anterior') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label for="puesto_nuevo" class="block text-sm font-medium text-gray-700">Puesto Nuevo</label>
                                            <input type="text" wire:model.defer="puesto_nuevo" id="puesto_nuevo" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            @error('puesto_nuevo') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label for="sector_anterior" class="block text-sm font-medium text-gray-700">Sector Anterior</label>
                                            <input type="text" wire:model.defer="sector_anterior" id="sector_anterior" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            @error('sector_anterior') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label for="sector_nuevo" class="block text-sm font-medium text-gray-700">Sector Nuevo</label>
                                            <input type="text" wire:model.defer="sector_nuevo" id="sector_nuevo" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            @error('sector_nuevo') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label for="categoria_anterior" class="block text-sm font-medium text-gray-700">Categoría Anterior</label>
                                            <input type="text" wire:model.defer="categoria_anterior" id="categoria_anterior" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            @error('categoria_anterior') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label for="categoria_nueva" class="block text-sm font-medium text-gray-700">Categoría Nueva</label>
                                            <input type="text" wire:model.defer="categoria_nueva" id="categoria_nueva" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            @error('categoria_nueva') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label for="salario_anterior" class="block text-sm font-medium text-gray-700">Salario Anterior</label>
                                            <input type="number" step="0.01" wire:model.defer="salario_anterior" id="salario_anterior" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            @error('salario_anterior') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label for="salario_nuevo" class="block text-sm font-medium text-gray-700">Salario Nuevo</label>
                                            <input type="number" step="0.01" wire:model.defer="salario_nuevo" id="salario_nuevo" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            @error('salario_nuevo') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div>
                                        <label for="observaciones" class="block text-sm font-medium text-gray-700">Observaciones (Opcional)</label>
                                        <textarea wire:model.defer="observaciones" id="observaciones" rows="3" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                                        @error('observaciones') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                            <button type="submit" class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Guardar Evento
                            </button>
                            <button type="button" wire:click="closeAddModal" class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
    {{-- --}} {{-- Este cierre de comentario será eliminado si existía solo, o la línea se ajustará si había más contenido --}}
</div>
