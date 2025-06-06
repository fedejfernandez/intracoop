<div>
    <form wire:submit.prevent="save" class="space-y-6 p-4 sm:p-6 lg:p-8">
        
        {{-- Tarjeta para Información Personal y de Contacto --}}
        <div class="bg-white shadow-md sm:rounded-lg overflow-hidden">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-xl leading-6 font-semibold text-gray-800">
                    {{ $trabajadorId ? 'Editar Trabajador' : 'Nuevo Trabajador' }} - Información Personal y de Contacto
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Completa los datos personales y de contacto del trabajador.
                </p>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-y-6 gap-x-4">
                    {{-- Nombre Completo --}}
                    <div class="md:col-span-2 lg:col-span-4">
                        <label for="NombreCompleto" class="block text-sm font-medium text-gray-700"> Nombre Completo </label>
                        <div class="mt-1">
                            <input type="text" wire:model.defer="NombreCompleto" id="NombreCompleto" autocomplete="name" class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                        </div>
                        @error('NombreCompleto') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- DNI/CUIL --}}
                    <div class="lg:col-span-2">
                        <label for="DNI_CUIL" class="block text-sm font-medium text-gray-700"> DNI/CUIL </label>
                        <div class="mt-1">
                            <input type="text" wire:model.defer="DNI_CUIL" id="DNI_CUIL" class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                        </div>
                        @error('DNI_CUIL') <p class="mt-1 text-xs text-red-600">{{ $message }}</p @enderror
                    </div>

                    {{-- NumeroLegajo --}}
                    <div class="lg:col-span-2">
                        <label for="NumeroLegajo" class="block text-sm font-medium text-gray-700"> Número de Legajo </label>
                        <div class="mt-1">
                            <input type="text" wire:model.defer="NumeroLegajo" id="NumeroLegajo" class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                        </div>
                        @error('NumeroLegajo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p @enderror
                    </div>
                    
                    {{-- TipoDocumento --}}
                    <div class="lg:col-span-2">
                        <label for="TipoDocumento" class="block text-sm font-medium text-gray-700"> Tipo de Documento </label>
                        <div class="mt-1">
                            <input type="text" wire:model.defer="TipoDocumento" id="TipoDocumento" class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                        </div>
                        @error('TipoDocumento') <p class="mt-1 text-xs text-red-600">{{ $message }}</p @enderror
                    </div>

                    {{-- Fecha Nacimiento --}}
                    <div class="lg:col-span-2">
                        <label for="FechaNacimiento" class="block text-sm font-medium text-gray-700"> Fecha de Nacimiento </label>
                        <div class="mt-1">
                            <input type="date" wire:model.defer="FechaNacimiento" id="FechaNacimiento" class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                        </div>
                        @error('FechaNacimiento') <p class="mt-1 text-xs text-red-600">{{ $message }}</p @enderror
                    </div>

                    {{-- Nacionalidad --}}
                    <div class="lg:col-span-2">
                        <label for="Nacionalidad" class="block text-sm font-medium text-gray-700"> Nacionalidad </label>
                        <div class="mt-1">
                            <input type="text" wire:model.defer="Nacionalidad" id="Nacionalidad" class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                        </div>
                        @error('Nacionalidad') <p class="mt-1 text-xs text-red-600">{{ $message }}</p @enderror
                    </div>

                    {{-- EstadoCivil --}}
                    <div class="lg:col-span-2">
                        <label for="EstadoCivil" class="block text-sm font-medium text-gray-700"> Estado Civil </label>
                        <div class="mt-1">
                            <input type="text" wire:model.defer="EstadoCivil" id="EstadoCivil" class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                        </div>
                        @error('EstadoCivil') <p class="mt-1 text-xs text-red-600">{{ $message }}</p @enderror
                    </div>

                    {{-- Sexo --}}
                    <div class="lg:col-span-2">
                        <label for="Sexo" class="block text-sm font-medium text-gray-700"> Sexo </label>
                        <div class="mt-1">
                            <input type="text" wire:model.defer="Sexo" id="Sexo" class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                        </div>
                        @error('Sexo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p @enderror
                    </div>

                    {{-- Email --}}
                    <div class="lg:col-span-2">
                        <label for="Email" class="block text-sm font-medium text-gray-700"> Email </label>
                        <div class="mt-1">
                            <input type="email" wire:model.defer="Email" id="Email" autocomplete="email" class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                        </div>
                        @error('Email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p @enderror
                    </div>

                    {{-- Telefono --}}
                    <div class="lg:col-span-2">
                        <label for="Telefono" class="block text-sm font-medium text-gray-700"> Teléfono </label>
                        <div class="mt-1">
                            <input type="text" wire:model.defer="Telefono" id="Telefono" autocomplete="tel" class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                        </div>
                        @error('Telefono') <p class="mt-1 text-xs text-red-600">{{ $message }}</p @enderror
                    </div>

                    {{-- Direccion --}}
                    <div class="md:col-span-2 lg:col-span-4">
                        <label for="Direccion" class="block text-sm font-medium text-gray-700"> Dirección </label>
                        <div class="mt-1">
                            <textarea wire:model.defer="Direccion" id="Direccion" rows="2" class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md"></textarea>
                        </div>
                        @error('Direccion') <p class="mt-1 text-xs text-red-600">{{ $message }}</p @enderror
                    </div>

                    {{-- Localidad --}}
                    <div class="lg:col-span-2">
                        <label for="Localidad" class="block text-sm font-medium text-gray-700"> Localidad </label>
                        <div class="mt-1">
                            <input type="text" wire:model.defer="Localidad" id="Localidad" class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                        </div>
                        @error('Localidad') <p class="mt-1 text-xs text-red-600">{{ $message }}</p @enderror
                    </div>

                    {{-- Provincia --}}
                    <div class="lg:col-span-2">
                        <label for="Provincia" class="block text-sm font-medium text-gray-700"> Provincia </label>
                        <div class="mt-1">
                            <input type="text" wire:model.defer="Provincia" id="Provincia" class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                        </div>
                        @error('Provincia') <p class="mt-1 text-xs text-red-600">{{ $message }}</p @enderror
                    </div>

                    {{-- CodigoPostal --}}
                    <div class="lg:col-span-2">
                        <label for="CodigoPostal" class="block text-sm font-medium text-gray-700"> Código Postal </label>
                        <div class="mt-1">
                            <input type="text" wire:model.defer="CodigoPostal" id="CodigoPostal" class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                        </div>
                        @error('CodigoPostal') <p class="mt-1 text-xs text-red-600">{{ $message }}</p @enderror
                    </div>
                    
                    {{-- Foto --}}
                    <div class="md:col-span-2 lg:col-span-4">
                        <label for="Foto" class="block text-sm font-medium text-gray-700"> Foto </label>
                        <div class="mt-1 flex items-center">
                            @if ($existingFotoUrl)
                                <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100 mr-4">
                                    <img src="{{ $existingFotoUrl }}" alt="Foto actual" class="h-full w-full object-cover text-gray-300">
                                </span>
                            @endif
                            @if ($Foto)
                                <span class="inline-block h-12 w-12 rounded-full overflow-hidden bg-gray-100 mr-4">
                                    <img src="{{ $Foto->temporaryUrl() }}" alt="Nueva foto" class="h-full w-full object-cover text-gray-300">
                                </span>
                            @endif
                            <input type="file" wire:model="Foto" id="Foto" class="ml-5 bg-white py-2 px-3 border border-gray-300 rounded-md shadow-sm text-sm leading-4 font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        </div>
                        <div wire:loading wire:target="Foto" class="text-sm text-gray-500">Cargando...</div>
                        @error('Foto') <p class="mt-1 text-xs text-red-600">{{ $message }}</p @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Tarjeta para Datos Laborales --}}
        <div class="bg-white shadow-md sm:rounded-lg overflow-hidden mt-6">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-xl leading-6 font-semibold text-gray-800">
                    Datos Laborales
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Información sobre el puesto y condiciones laborales del trabajador.
                </p>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-y-6 gap-x-4">
                    {{-- Fecha Ingreso --}}
                    <div class="lg:col-span-2">
                        <label for="FechaIngreso" class="block text-sm font-medium text-gray-700"> Fecha de Ingreso </label>
                        <div class="mt-1">
                            <input type="date" wire:model.defer="FechaIngreso" id="FechaIngreso" class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                        </div>
                        @error('FechaIngreso') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- FechaReconocida (Antigüedad) --}}
                    <div class="lg:col-span-2">
                        <label for="FechaReconocida" class="block text-sm font-medium text-gray-700"> Fecha Reconocida (Antigüedad) </label>
                        <div class="mt-1">
                            <input type="date" wire:model.defer="FechaReconocida" id="FechaReconocida" class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                        </div>
                        @error('FechaReconocida') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Puesto --}}
                    <div class="lg:col-span-2">
                        <label for="Puesto" class="block text-sm font-medium text-gray-700"> Puesto </label>
                        <div class="mt-1">
                            <select wire:model.defer="Puesto" id="Puesto" class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                                <option value="">-- Seleccione un Puesto --</option>
                                @if(isset($opcionesPuesto) && count($opcionesPuesto) > 0)
                                    @foreach ($opcionesPuesto as $puestoOpt)
                                        <option value="{{ $puestoOpt }}">{{ $puestoOpt }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        @error('Puesto') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Sector --}}
                    <div class="lg:col-span-2">
                        <label for="Sector" class="block text-sm font-medium text-gray-700"> Sector </label>
                        <div class="mt-1">
                            <select wire:model.defer="Sector" id="Sector" class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                                <option value="">-- Seleccione un Sector --</option>
                                @if(isset($opcionesSector) && count($opcionesSector) > 0)
                                    @foreach ($opcionesSector as $sectorOpt)
                                        <option value="{{ $sectorOpt }}">{{ $sectorOpt }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        @error('Sector') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                    
                    {{-- Categoria (Laboral) --}}
                    <div class="lg:col-span-2">
                        <label for="Categoria" class="block text-sm font-medium text-gray-700"> Categoría Laboral </label>
                        <div class="mt-1">
                            <select wire:model.defer="Categoria" id="Categoria" class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                                <option value="">-- Seleccione una Categoría --</option>
                                @if(isset($opcionesCategoria) && count($opcionesCategoria) > 0)
                                    @foreach ($opcionesCategoria as $categoriaOpt)
                                        <option value="{{ $categoriaOpt }}">{{ $categoriaOpt }}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        @error('Categoria') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- CCT (Convenio Colectivo de Trabajo) --}}
                    <div class="lg:col-span-2">
                        <label for="CCT" class="block text-sm font-medium text-gray-700"> CCT (Convenio Colectivo) </label>
                        <div class="mt-1">
                            <select wire:model.live="CCT" id="CCT" class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                                <option value="">-- Seleccione un CCT --</option>
                                <option value="36/75">36/75</option>
                                <option value="459/06">459/06</option>
                                <option value="107/75">107/75</option>
                                {{-- Otros CCTs pueden ser agregados aquí o cargados dinámicamente --}}
                            </select>
                        </div>
                        @error('CCT') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Estado --}}
                    <div class="lg:col-span-2">
                        <label for="Estado" class="block text-sm font-medium text-gray-700"> Estado </label>
                        <div class="mt-1">
                            <select wire:model.defer="Estado" id="Estado" class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                                <option value="Licencia">Licencia</option>
                            </select>
                        </div>
                        @error('Estado') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Días Vacaciones Anuales (Calculados) --}}
                    <div class="md:col-span-2 lg:col-span-4">
                        <label for="DiasVacacionesAnuales" class="block text-sm font-medium text-gray-700"> Días de Vacaciones Anuales </label>
                        <div class="mt-1 flex items-center space-x-3">
                            <input type="number" wire:model.live="DiasVacacionesAnuales" id="DiasVacacionesAnuales" class="block w-1/2 shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                            <button type="button" wire:click="calcularDiasVacaciones" class="px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Recalcular según Antigüedad
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Días calculados en base a CCT y antigüedad: <strong>{{ $antiguedadCalculada }}</strong>. Ajustar manualmente si es necesario.</p>
                        @error('DiasVacacionesAnuales') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Tarjeta para Datos Bancarios --}}
        <div class="bg-white shadow-md sm:rounded-lg overflow-hidden mt-6">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-xl leading-6 font-semibold text-gray-800">
                    Datos Bancarios
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Información bancaria para el depósito de haberes.
                </p>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-y-6 gap-x-4">
                    {{-- Banco --}}
                    <div class="lg:col-span-2">
                        <label for="Banco" class="block text-sm font-medium text-gray-700"> Banco </label>
                        <div class="mt-1">
                            <input type="text" wire:model.defer="Banco" id="Banco" class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                        </div>
                        @error('Banco') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- NroCuentaBancaria --}}
                    <div class="lg:col-span-2">
                        <label for="NroCuentaBancaria" class="block text-sm font-medium text-gray-700"> Número de Cuenta Bancaria </label>
                        <div class="mt-1">
                            <input type="text" wire:model.defer="NroCuentaBancaria" id="NroCuentaBancaria" class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                        </div>
                        @error('NroCuentaBancaria') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- CBU --}}
                    <div class="md:col-span-1 lg:col-span-2"> {{-- CBU puede ser largo --}}
                        <label for="CBU" class="block text-sm font-medium text-gray-700"> CBU/CVU </label>
                        <div class="mt-1">
                            <input type="text" wire:model.defer="CBU" id="CBU" class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                        </div>
                        @error('CBU') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- DatosAdicBco (Alias, etc.) --}}
                    <div class="md:col-span-1 lg:col-span-2">
                        <label for="DatosAdicBco" class="block text-sm font-medium text-gray-700"> Alias / Otros Datos Bancarios </label>
                        <div class="mt-1">
                            <input type="text" wire:model.defer="DatosAdicBco" id="DatosAdicBco" class="block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                        </div>
                        @error('DatosAdicBco') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Tarjeta para Vinculación con Usuario del Sistema --}}
        <div class="bg-white shadow-md sm:rounded-lg overflow-hidden mt-6">
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <h3 class="text-xl leading-6 font-semibold text-gray-800">
                    Vinculación con Usuario del Sistema
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Asigna un usuario existente o crea uno nuevo para el portal de empleados.
                </p>
            </div>
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-4">
                    {{-- Asignar Usuario Existente --}}
                    <div class="{{ $crearUsuarioAutomaticamente ? 'opacity-50' : '' }}">
                        <label for="user_id" class="block text-sm font-medium text-gray-700">Asignar Usuario Existente</label>
                        <select id="user_id" wire:model.live="user_id" 
                                class="mt-1 block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md"
                                {{ $crearUsuarioAutomaticamente ? 'disabled' : '' }}>
                            <option value="">-- No asignar / Desvincular --</option>
                            @foreach ($availableUsers as $user)
                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Si se selecciona, el trabajador se vinculará a este usuario.</p>
                        @error('user_id') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                    </div>

                    {{-- Crear Usuario Automáticamente --}}
                    <div>
                        @if (!$disableCreateUserCheckbox)
                        <div class="relative flex items-start">
                            <div class="flex items-center h-5">
                                <input id="crearUsuarioAutomaticamente" wire:model.live="crearUsuarioAutomaticamente" type="checkbox" 
                                       class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="crearUsuarioAutomaticamente" class="font-medium text-gray-700">Crear Usuario Automáticamente</label>
                                <p class="text-gray-500 text-xs">Se creará un usuario con el Email y Nombre Completo del trabajador. Se le asignará el rol 'portal'.</p>
                            </div>
                        </div>
                        @else
                        <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700">
                            <p class="font-bold">Usuario Ya Vinculado</p>
                            <p class="text-xs">Este trabajador ya tiene un usuario del sistema asociado ({{ $this->trabajador?->user?->email ?? 'N/A' }}). Para cambiarlo, primero desvincule el actual seleccionando "-- No asignar / Desvincular --" arriba y guarde los cambios.</p>
                        </div>
                        @endif

                        @if ($crearUsuarioAutomaticamente && !$disableCreateUserCheckbox)
                            <div class="mt-4 space-y-4 pl-7">
                                <div>
                                    <label for="newUserPassword" class="block text-sm font-medium text-gray-700">Contraseña para Nuevo Usuario</label>
                                    <input id="newUserPassword" type="password" wire:model="newUserPassword" class="mt-1 block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                                    @error('newUserPassword') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label for="newUserPassword_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                                    <input id="newUserPassword_confirmation" type="password" wire:model="newUserPassword_confirmation" class="mt-1 block w-full shadow-sm sm:text-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Acciones --}}
        <div class="pt-5">
            <div class="flex justify-end space-x-3">
                <a href="{{ route('admin.trabajadores.index') }}" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancelar
                </a>
                <button type="submit" wire:loading.attr="disabled" wire:target="save, Foto"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                    <svg wire:loading wire:target="save, Foto" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    {{ $trabajadorId ? 'Actualizar' : 'Guardar' }} Trabajador
                </button>
            </div>
        </div>

        @if (session()->has('message'))
            <div class="mt-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                {{ session('message') }}
            </div>
        @endif
    </form>
</div>
