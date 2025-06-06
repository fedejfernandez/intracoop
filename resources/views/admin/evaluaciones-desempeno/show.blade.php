<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detalles de Evaluación de Desempeño') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg p-6 md:p-8">

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                        Evaluación de: {{ $evaluacionDesempeno->trabajador->NombreCompleto ?? 'N/A' }}
                    </h3>
                    <a href="{{ route('admin.evaluaciones-desempeno.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-400 dark:hover:bg-gray-600 transition">
                        Volver al listado
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Evaluador</p>
                        <p class="text-lg text-gray-900 dark:text-gray-100">{{ $evaluacionDesempeno->evaluador->name ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Fecha de Evaluación</p>
                        <p class="text-lg text-gray-900 dark:text-gray-100">{{ $evaluacionDesempeno->fecha_evaluacion ? $evaluacionDesempeno->fecha_evaluacion->format('d/m/Y') : 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Período Evaluado</p>
                        <p class="text-lg text-gray-900 dark:text-gray-100">
                            {{ $evaluacionDesempeno->periodo_evaluado_inicio ? $evaluacionDesempeno->periodo_evaluado_inicio->format('d/m/Y') : 'N/A' }} - 
                            {{ $evaluacionDesempeno->periodo_evaluado_fin ? $evaluacionDesempeno->periodo_evaluado_fin->format('d/m/Y') : 'N/A' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Calificación General</p>
                        <p class="text-lg text-gray-900 dark:text-gray-100">{{ $evaluacionDesempeno->calificacion_general ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Estado</p>
                        <span @class([
                            'px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full',
                            'bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100' => $evaluacionDesempeno->estado === 'Borrador',
                            'bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-100' => $evaluacionDesempeno->estado === 'Publicada',
                            'bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100' => $evaluacionDesempeno->estado === 'Discutida' || $evaluacionDesempeno->estado === 'Cerrada',
                            'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100' => !in_array($evaluacionDesempeno->estado, ['Borrador', 'Publicada', 'Discutida', 'Cerrada'])
                        ])>
                            {{ $evaluacionDesempeno->estado }}
                        </span>
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Fortalezas</p>
                        <p class="mt-1 text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $evaluacionDesempeno->fortalezas ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Áreas de Mejora</p>
                        <p class="mt-1 text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $evaluacionDesempeno->areas_mejora ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Comentarios del Evaluador</p>
                        <p class="mt-1 text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $evaluacionDesempeno->comentarios_evaluador ?: '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Comentarios del Trabajador</p>
                        <p class="mt-1 text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $evaluacionDesempeno->comentarios_trabajador ?: '-' }}</p>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('admin.evaluaciones-desempeno.edit', $evaluacionDesempeno) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 hover:bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Editar
                    </a>
                    <form action="{{ route('admin.evaluaciones-desempeno.destroy', $evaluacionDesempeno) }}" method="POST" onsubmit="return confirm('¿Está seguro de que desea eliminar esta evaluación?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Eliminar
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout> 