<div>
    <div class="p-4 sm:p-6 lg:p-8">
        <div class="sm:flex sm:items-center mb-8">
            <div class="sm:flex-auto">
                <h1 class="text-2xl font-semibold text-gray-900">Legajo del Trabajador</h1>
                <p class="mt-2 text-sm text-gray-700">Detalles completos de {{ $trabajador->NombreCompleto }}.</p>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <a href="{{ route('admin.trabajadores.index') }}" wire:navigate class="inline-flex items-center justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 sm:w-auto">
                    Volver al Listado
                </a>
            </div>
        </div>

        {{-- Tarjeta para Información Personal y de Contacto --}}
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Información Personal y de Contacto</h3>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">
                    <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Foto</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            @if ($trabajador->Foto)
                                <img src="{{ Storage::url($trabajador->Foto) }}" alt="Foto de {{ $trabajador->NombreCompleto }}" class="h-24 w-24 rounded-md object-cover">
                            @else
                                <span class="text-gray-500">Sin foto</span>
                            @endif
                        </dd>
                    </div>
                    <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Nombre Completo</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trabajador->NombreCompleto ?? 'N/A' }}</dd>
                    </div>
                     <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Número de Legajo</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trabajador->NumeroLegajo ?? 'N/A' }}</dd>
                    </div>
                    <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">DNI/CUIL</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trabajador->DNI_CUIL ?? 'N/A' }}</dd>
                    </div>
                    <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Tipo de Documento</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trabajador->TipoDocumento ?? 'N/A' }}</dd>
                    </div>
                    <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Fecha de Nacimiento</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trabajador->FechaNacimiento ? $trabajador->FechaNacimiento->format('d/m/Y') : 'N/A' }}</dd>
                    </div>
                    <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Nacionalidad</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trabajador->Nacionalidad ?? 'N/A' }}</dd>
                    </div>
                    <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Estado Civil</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trabajador->EstadoCivil ?? 'N/A' }}</dd>
                    </div>
                    <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Sexo</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trabajador->Sexo ?? 'N/A' }}</dd>
                    </div>
                    <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trabajador->Email ?? 'N/A' }}</dd>
                    </div>
                    <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trabajador->Telefono ?? 'N/A' }}</dd>
                    </div>
                    <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Dirección</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trabajador->Direccion ?? 'N/A' }}</dd>
                    </div>
                    <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Localidad</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trabajador->Localidad ?? 'N/A' }}</dd>
                    </div>
                    <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Provincia</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trabajador->Provincia ?? 'N/A' }}</dd>
                    </div>
                    <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Código Postal</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trabajador->CodigoPostal ?? 'N/A' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Tarjeta para Datos Laborales --}}
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Datos Laborales</h3>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">
                    <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Fecha de Ingreso</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trabajador->FechaIngreso ? $trabajador->FechaIngreso->format('d/m/Y') : 'N/A' }}</dd>
                    </div>
                    <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Fecha Reconocida (Antigüedad)</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trabajador->FechaReconocida ? $trabajador->FechaReconocida->format('d/m/Y') : 'N/A' }}</dd>
                    </div>
                    <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Puesto</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trabajador->Puesto ?? 'N/A' }}</dd>
                    </div>
                    <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Sector</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trabajador->Sector ?? 'N/A' }}</dd>
                    </div>
                    <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Categoría Laboral</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trabajador->Categoria ?? 'N/A' }}</dd>
                    </div>
                    <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">CCT (Convenio Colectivo)</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trabajador->CCT ?? 'N/A' }}</dd>
                    </div>
                    <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Estado</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trabajador->Estado }}</dd>
                    </div>
                     <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Días de Vacaciones Anuales</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trabajador->DiasVacacionesAnuales }}</dd>
                    </div>
                </dl>
            </div>
        </div>
        
        {{-- Tarjeta para Datos Bancarios --}}
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Datos Bancarios</h3>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">
                    <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Banco</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trabajador->Banco ?? 'N/A' }}</dd>
                    </div>
                    <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Nro. Cuenta Bancaria</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trabajador->NroCuentaBancaria ?? 'N/A' }}</dd>
                    </div>
                    <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">CBU</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trabajador->CBU ?? 'N/A' }}</dd>
                    </div>
                    <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Datos Adicionales Banco</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trabajador->DatosAdicBco ?? 'N/A' }}</dd>
                    </div>
                </dl>
            </div>
        </div>

        {{-- Tarjeta para Cuenta de Usuario del Portal --}}
        @if($trabajador->user)
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Cuenta de Usuario del Portal</h3>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-0">
                <dl class="sm:divide-y sm:divide-gray-200">
                    <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Nombre de Usuario</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trabajador->user->name }}</dd>
                    </div>
                    <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Email de Usuario</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $trabajador->user->email }}</dd>
                    </div>
                     <div class="py-3 sm:py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Rol</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ ucfirst($trabajador->user->role) }}</dd>
                    </div>
                </dl>
            </div>
        </div>
        @else
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Cuenta de Usuario del Portal</h3>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                <p class="text-sm text-gray-500">Este trabajador no tiene una cuenta de usuario asociada para el portal.</p>
            </div>
        </div>
        @endif

        {{-- Sección de Licencias --}}
        <div class="mb-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-3">Licencias Registradas</h3>
            @if($trabajador->licencias && $trabajador->licencias->count() > 0)
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <ul role="list" class="divide-y divide-gray-200">
                        @foreach($trabajador->licencias->sortByDesc('FechaInicio') as $licencia)
                            <li class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <p class="text-sm font-medium text-indigo-600 truncate">
                                        {{ $licencia->TipoLicencia }}
                                    </p>
                                    <div class="ml-2 flex-shrink-0 flex">
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
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <p class="flex items-center text-sm text-gray-500">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                            </svg>
                                            Desde: {{ $licencia->FechaInicio ? $licencia->FechaInicio->format('d/m/Y') : 'N/A' }} - 
                                            Hasta: {{ $licencia->FechaFin ? $licencia->FechaFin->format('d/m/Y') : 'N/A' }}
                                            ({{ $licencia->CantidadDias ?? 0 }} días)
                                        </p>
                                    </div>
                                     <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                        <p>Solicitada: {{ $licencia->created_at ? $licencia->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
                                    </div>
                                </div>
                                 @if($licencia->Motivo)
                                <p class="text-sm text-gray-600 mt-1"><strong>Motivo:</strong> {{ $licencia->Motivo }}</p>
                                @endif
                                @if($licencia->Comentarios_Admin)
                                <p class="text-sm text-gray-500 mt-1"><em>Comentarios Admin: {{ $licencia->Comentarios_Admin }}</em></p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <p class="text-sm text-gray-500">No hay licencias registradas para este trabajador.</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Sección de Vacaciones --}}
        <div>
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-3">Vacaciones Registradas</h3>
            @if($trabajador->vacaciones && $trabajador->vacaciones->count() > 0)
                 <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <ul role="list" class="divide-y divide-gray-200">
                        @foreach($trabajador->vacaciones->sortByDesc('Fecha_Inicio') as $vacacion)
                            <li class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                     <p class="text-sm font-medium text-indigo-600 truncate">
                                        Solicitud de Vacaciones
                                    </p>
                                    <div class="ml-2 flex-shrink-0 flex">
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
                                    </div>
                                </div>
                                <div class="mt-2 sm:flex sm:justify-between">
                                    <div class="sm:flex">
                                        <p class="flex items-center text-sm text-gray-500">
                                            <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                            </svg>
                                            Desde: {{ $vacacion->Fecha_Inicio ? $vacacion->Fecha_Inicio->format('d/m/Y') : 'N/A' }} - 
                                            Hasta: {{ $vacacion->Fecha_Fin ? $vacacion->Fecha_Fin->format('d/m/Y') : 'N/A' }}
                                            ({{ $vacacion->Dias_Solicitados ?? 0 }} días)
                                        </p>
                                    </div>
                                     <div class="mt-2 flex items-center text-sm text-gray-500 sm:mt-0">
                                        <p>Solicitada: {{ $vacacion->created_at ? $vacacion->created_at->format('d/m/Y H:i') : 'N/A' }}</p>
                                    </div>
                                </div>
                                @if($vacacion->Comentarios_Admin)
                                <p class="text-sm text-gray-500 mt-1"><em>Comentarios Admin: {{ $vacacion->Comentarios_Admin }}</em></p>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <p class="text-sm text-gray-500">No hay vacaciones registradas para este trabajador.</p>
                    </div>
                </div>
            @endif
        </div>

    </div>
</div>
