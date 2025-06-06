<div>
    <form wire:submit.prevent="save" class="space-y-6 p-6">
        @csrf

        <div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ $evaluacionId ? 'Editar Evaluación de Desempeño' : 'Nueva Evaluación de Desempeño' }}
            </h3>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Complete los detalles de la evaluación.
            </p>
        </div>

        {{-- Fila 1: Trabajador y Evaluador --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-label for="trabajador_id" value="{{ __('Trabajador Evaluado') }}" />
                <select id="trabajador_id" wire:model="trabajador_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                    <option value="">Seleccione un trabajador</option>
                    @foreach($trabajadores as $trabajador)
                        <option value="{{ $trabajador->ID_Trabajador }}">{{ $trabajador->NombreCompleto }}</option>
                    @endforeach
                </select>
                <x-input-error for="trabajador_id" class="mt-2" />
            </div>
            <div>
                <x-label for="evaluador_id" value="{{ __('Evaluador') }}" />
                <select id="evaluador_id" wire:model="evaluador_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                    <option value="">Seleccione un evaluador</option>
                    @foreach($evaluadores as $evaluador)
                        <option value="{{ $evaluador->id }}">{{ $evaluador->name }}</option>
                    @endforeach
                </select>
                <x-input-error for="evaluador_id" class="mt-2" />
            </div>
        </div>

        {{-- Fila 2: Fecha Evaluación y Período --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <x-label for="fecha_evaluacion" value="{{ __('Fecha de Evaluación') }}" />
                <x-input id="fecha_evaluacion" type="date" wire:model="fecha_evaluacion" class="block mt-1 w-full" />
                <x-input-error for="fecha_evaluacion" class="mt-2" />
            </div>
            <div>
                <x-label for="periodo_evaluado_inicio" value="{{ __('Inicio Período Evaluado') }}" />
                <x-input id="periodo_evaluado_inicio" type="date" wire:model="periodo_evaluado_inicio" class="block mt-1 w-full" />
                <x-input-error for="periodo_evaluado_inicio" class="mt-2" />
            </div>
            <div>
                <x-label for="periodo_evaluado_fin" value="{{ __('Fin Período Evaluado') }}" />
                <x-input id="periodo_evaluado_fin" type="date" wire:model="periodo_evaluado_fin" class="block mt-1 w-full" />
                <x-input-error for="periodo_evaluado_fin" class="mt-2" />
            </div>
        </div>

        {{-- Fila 3: Calificación y Estado --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-label for="calificacion_general" value="{{ __('Calificación General (0-10)') }}" />
                <x-input id="calificacion_general" type="number" step="0.1" wire:model="calificacion_general" class="block mt-1 w-full" />
                <x-input-error for="calificacion_general" class="mt-2" />
            </div>
            <div>
                <x-label for="estado" value="{{ __('Estado') }}" />
                <select id="estado" wire:model="estado" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                    <option value="Borrador">Borrador</option>
                    <option value="Publicada">Publicada</option>
                    <option value="Discutida">Discutida</option>
                    <option value="Cerrada">Cerrada</option>
                </select>
                <x-input-error for="estado" class="mt-2" />
            </div>
        </div>

        {{-- Fila 4: Fortalezas y Áreas de Mejora --}}
        <div>
            <x-label for="fortalezas" value="{{ __('Fortalezas') }}" />
            <textarea id="fortalezas" wire:model="fortalezas" class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1" rows="3"></textarea>
            <x-input-error for="fortalezas" class="mt-2" />
        </div>
        <div>
            <x-label for="areas_mejora" value="{{ __('Áreas de Mejora') }}" />
            <textarea id="areas_mejora" wire:model="areas_mejora" class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1" rows="3"></textarea>
            <x-input-error for="areas_mejora" class="mt-2" />
        </div>

        {{-- Fila 5: Comentarios --}}
        <div>
            <x-label for="comentarios_evaluador" value="{{ __('Comentarios del Evaluador') }}" />
            <textarea id="comentarios_evaluador" wire:model="comentarios_evaluador" class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1" rows="3"></textarea>
            <x-input-error for="comentarios_evaluador" class="mt-2" />
        </div>
        <div>
            <x-label for="comentarios_trabajador" value="{{ __('Comentarios del Trabajador') }}" />
            <textarea id="comentarios_trabajador" wire:model="comentarios_trabajador" class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1" rows="3"></textarea>
            <x-input-error for="comentarios_trabajador" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <a href="{{ route('admin.evaluaciones-desempeno.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 mr-4">
                Cancelar
            </a>

            <x-button>
                {{ $evaluacionId ? __('Actualizar Evaluación') : __('Guardar Evaluación') }}
            </x-button>
        </div>
    </form>
</div>
