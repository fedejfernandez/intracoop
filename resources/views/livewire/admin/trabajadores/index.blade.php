<div>
    <div class="p-4 sm:p-6 lg:p-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-xl font-semibold text-gray-900">{{ __('Trabajadores') }}</h1>
                <p class="mt-2 text-sm text-gray-700">{{ __('Lista de todos los trabajadores registrados.') }}</p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <a href="{{ route('admin.trabajadores.create') }}" wire:navigate class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                    {{ __('Nuevo Trabajador') }}
                </a>
            </div>
        </div>

        <div class="mt-8 flex flex-col">
            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">{{ __('Foto') }}</th>
                                    <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">{{ __('Nombre Completo') }}</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ __('DNI/CUIL') }}</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ __('Email') }}</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ __('Puesto') }}</th>
                                    <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">{{ __('Estado') }}</th>
                                    <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                                        <span class="sr-only">{{ __('Acciones') }}</span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse ($trabajadores as $trabajador)
                                    <tr>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                            @if ($trabajador->Foto)
                                                <img src="{{ Storage::url($trabajador->Foto) }}" alt="{{ __('Foto de') }} {{ $trabajador->NombreCompleto }}" class="h-10 w-10 rounded-full">
                                            @else
                                                <span class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-xs text-gray-500">{{ __('Sin foto') }}</span>
                                            @endif
                                        </td>
                                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">{{ $trabajador->NombreCompleto }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $trabajador->DNI_CUIL }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $trabajador->Email }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $trabajador->Puesto ?? 'N/A' }}</td>
                                        <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $trabajador->Estado }}</td>
                                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                            <a href="{{ route('admin.trabajadores.legajo', $trabajador) }}" wire:navigate class="text-green-600 hover:text-green-900 mr-3">{{ __('Legajo') }}</a>
                                            <a href="{{ route('admin.trabajadores.edit', $trabajador) }}" wire:navigate class="text-indigo-600 hover:text-indigo-900 mr-3">{{ __('Editar') }}</a>
                                            <button wire:click="eliminarTrabajador({{ $trabajador->ID_Trabajador }})" wire:confirm="{{ __('¿Estás seguro de que quieres eliminar a este trabajador?') }}" class="text-red-600 hover:text-red-900">
                                                {{ __('Eliminar') }}
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="whitespace-nowrap px-3 py-4 text-sm text-center text-gray-500">
                                            {{ __('No hay trabajadores registrados.') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8">
            {{ $trabajadores->links() }}
        </div>
    </div>
</div>
