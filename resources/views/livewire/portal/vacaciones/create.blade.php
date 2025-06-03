<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Solicitar Nuevas Vacaciones') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 lg:p-8 bg-white dark:bg-gray-800 dark:bg-gradient-to-bl dark:from-gray-700/50 dark:via-transparent border-b border-gray-200 dark:border-gray-700">
                    
                    @if ($showNoTrabajadorMessage)
                        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
                            <span class="font-medium">Error:</span> No tienes un perfil de trabajador asociado para realizar esta acción. Por favor, contacta con administración.
                            <div class="mt-4">
                                <a href="{{ route('portal.vacaciones.index') }}" wire:navigate class="font-semibold text-indigo-600 hover:text-indigo-500">
                                    &larr; Volver al listado de vacaciones
                                </a>
                            </div>
                        </div>
                    @else
                        <form wire:submit.prevent="saveVacaciones" class="space-y-6">
                            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-1">Detalles de la Solicitud de Vacaciones</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Selecciona las fechas para tu período de vacaciones. Se calcularán solo los días laborables (lunes a viernes).</p>

                            @if($diasDisponiblesInfo)
                            <div class="p-3 mb-4 text-sm text-blue-700 bg-blue-100 rounded-lg dark:bg-blue-900 dark:text-blue-300" role="alert">
                                {{ $diasDisponiblesInfo }}
                            </div>
                            @endif

                            <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                                {{-- Fecha Inicio --}}
                                <div>
                                    <label for="fecha_inicio_vac" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de Inicio</label>
                                    <input type="date" wire:model.lazy="fecha_inicio" id="fecha_inicio_vac" class="mt-1 block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-200" min="{{ today()->format('Y-m-d') }}">
                                    @error('fecha_inicio') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>

                                {{-- Fecha Fin --}}
                                <div>
                                    <label for="fecha_fin_vac" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Fecha de Fin</label>
                                    <input type="date" wire:model.lazy="fecha_fin" id="fecha_fin_vac" class="mt-1 block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-200" min="{{ $fecha_inicio ?? today()->format('Y-m-d') }}">
                                    @error('fecha_fin') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            
                            {{-- Comentarios Opcionales --}}
                            {{--
                            <div>
                                <label for="comentarios_vac" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Comentarios (Opcional)</label>
                                <textarea wire:model.lazy="comentarios" id="comentarios_vac" rows="3" class="mt-1 block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-gray-200"></textarea>
                                @error('comentarios') <p class="mt-1 text-xs text-red-500">{{ $message }}</p> @enderror
                            </div>
                            --}}

                            <div class="flex justify-end pt-4 space-x-3">
                                <a href="{{ route('portal.vacaciones.index') }}" wire:navigate class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-500 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cancelar
                                </a>
                                <button type="submit" wire:loading.attr="disabled" wire:target="saveVacaciones" class="inline-flex justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                                    <span wire:loading wire:target="saveVacaciones" class="mr-2">
                                        <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                    Enviar Solicitud de Vacaciones
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
