<div>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-blue-800 leading-tight">
                {{ __('Mi Legajo Completo') }}
            </h2>
            <a href="{{ route('portal.dashboard') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Volver al Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if ($trabajador)
                <!-- Información Personal y de Contacto -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-blue-50">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Información Personal y de Contacto
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nombre Completo</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->NombreCompleto }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Legajo</dt>
                                <dd class="mt-1 text-sm text-gray-900 font-semibold text-blue-600">{{ $trabajador->NumeroLegajo ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">DNI/CUIL</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->DNI_CUIL }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Tipo de Documento</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->TipoDocumento ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Nacionalidad</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->Nacionalidad ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Estado Civil</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->EstadoCivil ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Sexo</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->Sexo ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha de Nacimiento</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->FechaNacimiento ? $trabajador->FechaNacimiento->format('d/m/Y') : 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Email</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->Email ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->Telefono ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Dirección</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->Direccion ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Localidad</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->Localidad ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Provincia</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->Provincia ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Código Postal</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->CodigoPostal ?? 'N/A' }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Datos Laborales -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-green-50">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
                            </svg>
                            Datos Laborales
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Puesto</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->Puesto ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Sector</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->Sector ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Categoría</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->Categoria ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Centro de Costos</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->CentroCostos ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">CCT</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->CCT ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha de Ingreso</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->FechaIngreso ? $trabajador->FechaIngreso->format('d/m/Y') : 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha Reconocida</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->FechaReconocida ? $trabajador->FechaReconocida->format('d/m/Y') : 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                        {{ $trabajador->Estado === 'Activo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $trabajador->Estado ?? 'N/A' }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Días de Vacaciones Anuales</dt>
                                <dd class="mt-1 text-sm text-gray-900 font-semibold text-blue-600">{{ $trabajador->DiasVacacionesAnuales ?? 'N/A' }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Datos Bancarios -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-yellow-50">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Datos Bancarios
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Banco</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->Banco ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Número de Cuenta Bancaria</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->NroCuentaBancaria ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">CBU</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->CBU ?? 'N/A' }}</dd>
                            </div>
                            <div class="md:col-span-2 lg:col-span-3">
                                <dt class="text-sm font-medium text-gray-500">Datos Adicionales Bancarios</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->DatosAdicBco ?? 'N/A' }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información Adicional -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200 bg-purple-50">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Información Adicional
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha de Creación del Registro</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->created_at ? $trabajador->created_at->format('d/m/Y H:i') : 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Última Actualización</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $trabajador->updated_at ? $trabajador->updated_at->format('d/m/Y H:i') : 'N/A' }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Acciones -->
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex flex-wrap gap-4">
                            <a href="{{ route('portal.vacaciones.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-4 0V7"></path>
                                </svg>
                                Mis Vacaciones
                            </a>
                            
                            <a href="{{ route('portal.licencias.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Mis Licencias
                            </a>
                        </div>
                    </div>
                </div>

            @else
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="p-6">
                        <div class="text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.965-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Datos no encontrados</h3>
                            <p class="mt-1 text-sm text-gray-500">No se encontró información del trabajador asociada a este usuario.</p>
                            <p class="mt-1 text-sm text-gray-500">Por favor, contacte al administrador si cree que esto es un error.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div> 