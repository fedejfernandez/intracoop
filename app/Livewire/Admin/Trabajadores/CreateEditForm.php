<?php

namespace App\Livewire\Admin\Trabajadores;

use App\Models\Trabajador;
use App\Models\User; // Importar el modelo User
use App\Models\HistorialLaboral; // <-- Importar HistorialLaboral
use Livewire\Component;
use Livewire\WithFileUploads; // Para el campo de foto
use Illuminate\Support\Facades\Storage; // Para manejar el almacenamiento de fotos
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Facades\DB; // Importar DB
use Illuminate\Support\Facades\Config; // Importar Config
use Carbon\Carbon;
use Illuminate\Support\Facades\Log; // Import Log facade
use Illuminate\Support\Facades\Auth; // <-- Importar Auth

class CreateEditForm extends Component
{
    use WithFileUploads;

    public ?Trabajador $trabajador = null;
    public $trabajadorId = null;

    // Propiedades del formulario (deben coincidir con los campos de la migración)
    public $user_id = null;
    public $NombreCompleto = '';
    public $DNI_CUIL = '';
    public $FechaNacimiento = '';
    public $FechaIngreso = '';
    public $Puesto = '';
    public $Sector = '';
    public $Email = '';
    public $Telefono = '';
    public $Direccion = '';
    public $Foto = null; // Para la nueva foto a subir
    public $existingFotoUrl = null; // Para mostrar la foto existente
    public $Estado = 'Activo';
    public $DiasVacacionesAnuales = 14;

    // Added missing fields
    public $NumeroLegajo = '';
    public $TipoDocumento = '';
    public $Nacionalidad = '';
    public $EstadoCivil = '';
    public $Sexo = '';
    public $FechaReconocida = '';
    public $Categoria = '';
    public $CCT = '';
    public $Banco = '';
    public $NroCuentaBancaria = '';
    public $CBU = '';
    public $DatosAdicBco = '';
    public $Localidad = '';
    public $Provincia = '';
    public $CodigoPostal = '';
    // End of added missing fields

    // Las opciones se cargarán desde config/form_options.php
    public $opcionesCategoria = [];
    public $opcionesSector = [];
    public $opcionesPuesto = [];

    public $availableUsers = []; // Para la lista de usuarios

    // Para creación automática de usuario
    public $crearUsuarioAutomaticamente = false;
    public $newUserPassword = '';
    public $newUserPassword_confirmation = '';
    public $disableCreateUserCheckbox = false;

    // Para mostrar información calculada
    public $antiguedadCalculada = '';
    public $diasVacacionesCalculados = 14;

    protected function rules()
    {
        Log::debug('CreateEditForm Rules Debug: Entered rules() method.');
        Log::debug('CreateEditForm Rules Debug: trabajadorId = ' . $this->trabajadorId);
        Log::debug('CreateEditForm Rules Debug: trabajador is set? ' . ($this->trabajador ? 'Yes' : 'No'));
        if ($this->trabajador) {
            Log::debug('CreateEditForm Rules Debug: trabajador->user_id = ' . $this->trabajador->user_id);
        }
        Log::debug('CreateEditForm Rules Debug: crearUsuarioAutomaticamente = ' . ($this->crearUsuarioAutomaticamente ? 'true' : 'false'));

        $rules = [
            'NombreCompleto' => 'required|string|max:255',
            'DNI_CUIL' => 'required|string|max:255|unique:trabajadores,DNI_CUIL,' . $this->trabajadorId . ',ID_Trabajador',
            'FechaNacimiento' => 'required|date',
            'FechaIngreso' => 'required|date|after_or_equal:FechaNacimiento',
            'Puesto' => 'nullable|string|max:255',
            'Sector' => 'nullable|string|max:255',
            'Email' => ['required', 'email', 'max:255', 'unique:trabajadores,Email,' . $this->trabajadorId . ',ID_Trabajador'],
            'Telefono' => 'nullable|string|max:255',
            'Direccion' => 'nullable|string',
            'Foto' => 'nullable|image|max:2048', // Max 2MB, solo imágenes
            'Estado' => 'required|in:Activo,Inactivo,Licencia',
            'NumeroLegajo' => 'nullable|string|max:255|unique:trabajadores,NumeroLegajo,' . $this->trabajadorId . ',ID_Trabajador',
            'TipoDocumento' => 'nullable|string|max:50',
            'Nacionalidad' => 'nullable|string|max:100',
            'EstadoCivil' => 'nullable|string|max:50',
            'Sexo' => 'nullable|string|max:50',
            'FechaReconocida' => 'nullable|date',
            'Categoria' => 'nullable|string|max:100',
            'CCT' => 'nullable|string|max:100',
            'Banco' => 'nullable|string|max:100',
            'NroCuentaBancaria' => 'nullable|string|max:100',
            'CBU' => 'nullable|string|max:50',
            'DatosAdicBco' => 'nullable|string',
            'Localidad' => 'nullable|string|max:100',
            'Provincia' => 'nullable|string|max:100',
            'CodigoPostal' => 'nullable|string|max:20',
        ];

        if ($this->crearUsuarioAutomaticamente) {
            $rules['Email'][] = 'unique:users,email'; // Email también debe ser único en la tabla users
            $rules['newUserPassword'] = ['required', 'string', PasswordRule::min(8)->mixedCase()->numbers()->symbols(), 'confirmed'];
            $rules['user_id'] = 'nullable'; // Se ignora si se crea automáticamente
        } else {
            // Si no se crea usuario automáticamente, pero estamos editando un trabajador
            // que ya tiene un usuario asociado, su email debe ser único ignorando a ese mismo usuario.
            if ($this->trabajadorId && $this->trabajador && $this->trabajador->user_id) {
                $rules['Email'][] = 'unique:users,email,' . $this->trabajador->user_id;
            }
            // Si estamos creando un nuevo trabajador y asignando un usuario existente,
            // el email del trabajador debe ser único en 'trabajadores'. La unicidad en 'users'
            // ya está garantizada por el usuario existente. No se añade regla 'unique:users' aquí
            // para evitar conflictos si el email del trabajador se está estableciendo y es diferente
            // al del usuario existente seleccionado (lo cual podría ser un caso a refinar si se permite).

            $rules['user_id'] = ['nullable', function ($attribute, $value, $fail) {
                if ($value === '') return; // Permite desasignar
                if ($value === null) return; // Permite nulo si no se selecciona nada
                if (!User::find($value)) {
                    $fail('El usuario seleccionado no es válido.');
                }
            }];
        }

        Log::debug('CreateEditForm Rules Debug: Email rules = ' . json_encode($rules['Email'] ?? []));
        return $rules;
    }

    protected $messages = [
        'Email.unique' => 'Este correo electrónico ya está en uso.',
    ];

    public function mount($trabajadorId = null)
    {
        $this->opcionesCategoria = Config::get('form_options.categorias', []);
        $this->opcionesSector = Config::get('form_options.sectores', []);
        $this->opcionesPuesto = Config::get('form_options.puestos', []);

        if ($trabajadorId) {
            $this->trabajadorId = $trabajadorId;
            $this->trabajador = Trabajador::findOrFail($trabajadorId);
            $this->NombreCompleto = $this->trabajador->NombreCompleto;
            $this->DNI_CUIL = $this->trabajador->DNI_CUIL;
            $this->FechaNacimiento = $this->trabajador->FechaNacimiento ? $this->trabajador->FechaNacimiento->format('Y-m-d') : '';
            $this->FechaIngreso = $this->trabajador->FechaIngreso ? $this->trabajador->FechaIngreso->format('Y-m-d') : '';
            $this->Puesto = $this->trabajador->Puesto;
            $this->Sector = $this->trabajador->Sector;
            $this->Email = $this->trabajador->Email;
            $this->Telefono = $this->trabajador->Telefono;
            $this->Direccion = $this->trabajador->Direccion;
            $this->Estado = $this->trabajador->Estado;
            $this->DiasVacacionesAnuales = $this->trabajador->DiasVacacionesAnuales;
            $this->user_id = $this->trabajador->user_id;
            if ($this->trabajador->Foto) {
                $this->existingFotoUrl = Storage::url($this->trabajador->Foto);
            }

            // Initialize new fields
            $this->NumeroLegajo = $this->trabajador->NumeroLegajo;
            $this->TipoDocumento = $this->trabajador->TipoDocumento;
            $this->Nacionalidad = $this->trabajador->Nacionalidad;
            $this->EstadoCivil = $this->trabajador->EstadoCivil;
            $this->Sexo = $this->trabajador->Sexo;
            $this->FechaReconocida = $this->trabajador->FechaReconocida ? $this->trabajador->FechaReconocida->format('Y-m-d') : '';
            $this->Categoria = $this->trabajador->Categoria;
            $this->CCT = $this->trabajador->CCT;
            $this->Banco = $this->trabajador->Banco;
            $this->NroCuentaBancaria = $this->trabajador->NroCuentaBancaria;
            $this->CBU = $this->trabajador->CBU;
            $this->DatosAdicBco = $this->trabajador->DatosAdicBco;
            $this->Localidad = $this->trabajador->Localidad;
            $this->Provincia = $this->trabajador->Provincia;
            $this->CodigoPostal = $this->trabajador->CodigoPostal;
            // End of initializing new fields

            if ($this->trabajador->user_id) { // If already linked to a user
                $this->crearUsuarioAutomaticamente = false; // Default to not creating another one
                $this->disableCreateUserCheckbox = true;    // UI should disable the checkbox
            }
        }
        $this->loadAvailableUsers();
        $this->calcularDiasVacaciones();
    }

    /**
     * Calculadora de días de vacaciones cuando cambia la fecha de ingreso
     */
    public function updatedFechaIngreso()
    {
        $this->calcularDiasVacaciones();
    }

    /**
     * Calculadora de días de vacaciones cuando cambia el CCT
     */
    public function updatedCCT()
    {
        $this->calcularDiasVacaciones();
    }

    /**
     * Calcula automáticamente los días de vacaciones y antigüedad
     */
    public function calcularDiasVacaciones()
    {
        if (!$this->FechaIngreso) {
            $this->antiguedadCalculada = 'Sin fecha de ingreso';
            $this->diasVacacionesCalculados = 14;
            $this->DiasVacacionesAnuales = 14;
            return;
        }

        try {
            $fechaIngreso = Carbon::parse($this->FechaIngreso);
            $fechaReferencia = Carbon::createFromDate(date('Y'), 12, 31);
            $antiguedadAnios = $fechaIngreso->diffInYears($fechaReferencia);
            
            // Crear un trabajador temporal para usar el método de cálculo
            $trabajadorTemporal = new Trabajador();
            $trabajadorTemporal->FechaIngreso = $fechaIngreso;
            $trabajadorTemporal->CCT = $this->CCT;
            
            $diasCalculados = $trabajadorTemporal->calcularDiasVacacionesAnuales();
            
            $this->antiguedadCalculada = $antiguedadAnios . ' año' . ($antiguedadAnios != 1 ? 's' : '');
            $this->diasVacacionesCalculados = $diasCalculados;
            $this->DiasVacacionesAnuales = $diasCalculados;
            
        } catch (\Exception $e) {
            $this->antiguedadCalculada = 'Error en cálculo';
            $this->diasVacacionesCalculados = 14;
            $this->DiasVacacionesAnuales = 14;
        }
    }

    public function updatedUserId($value)
    {
        if ($value === '') {
            $this->user_id = null;
        }
    }

    public function updatedCrearUsuarioAutomaticamente($value)
    {
        if ($value) {
            $this->user_id = null; // Limpiar selección de usuario existente si se opta por crear uno nuevo
        }
        // No es necesario recargar usuarios aquí, el select se ocultará/mostrará
    }

    public function loadAvailableUsers()
    {
        $unassignedUsers = User::whereDoesntHave('trabajador')->get();
        $currentUser = null;
        if ($this->user_id && !$this->crearUsuarioAutomaticamente) { // Solo cargar el actual si no se está creando uno nuevo
            $currentUser = User::find($this->user_id);
        }

        if ($currentUser) {
            if (!$unassignedUsers->contains($currentUser)) {
                $this->availableUsers = $unassignedUsers->push($currentUser)->sortBy('name');
            } else {
                 $this->availableUsers = $unassignedUsers->sortBy('name');
            }
        } else {
            $this->availableUsers = $unassignedUsers->sortBy('name');
        }
    }

    public function save()
    {
        Log::debug('CreateEditForm Save Debug: User ID Selected = ' . $this->user_id);
        Log::debug('CreateEditForm Save Debug: Crear Usuario Automáticamente = ' . ($this->crearUsuarioAutomaticamente ? 'true' : 'false'));
        
        $validatedData = $this->validate();

        $originalData = [];
        if ($this->trabajadorId) {
            $trabajadorExistente = Trabajador::find($this->trabajadorId);
            if ($trabajadorExistente) {
                $originalData = [
                    'Puesto' => $trabajadorExistente->Puesto,
                    'Sector' => $trabajadorExistente->Sector,
                    'Categoria' => $trabajadorExistente->Categoria,
                    // Añadir 'Salario' si existe un campo directo en el modelo Trabajador y se gestiona aquí
                ];
            }
        }

        DB::transaction(function () use ($validatedData, $originalData) {
            $trabajadorData = [
                'NombreCompleto' => $this->NombreCompleto,
                'DNI_CUIL' => $this->DNI_CUIL,
                'FechaNacimiento' => $this->FechaNacimiento,
                'FechaIngreso' => $this->FechaIngreso,
                'Puesto' => $this->Puesto,
                'Sector' => $this->Sector,
                'Email' => $this->Email,
                'Telefono' => $this->Telefono,
                'Direccion' => $this->Direccion,
                'Estado' => $this->Estado,
                // DiasVacacionesAnuales se calculará automáticamente por el modelo
                'NumeroLegajo' => $this->NumeroLegajo,
                'TipoDocumento' => $this->TipoDocumento,
                'Nacionalidad' => $this->Nacionalidad,
                'EstadoCivil' => $this->EstadoCivil,
                'Sexo' => $this->Sexo,
                'FechaReconocida' => $this->FechaReconocida,
                'Categoria' => $this->Categoria,
                'CCT' => $this->CCT,
                'Banco' => $this->Banco,
                'NroCuentaBancaria' => $this->NroCuentaBancaria,
                'CBU' => $this->CBU,
                'DatosAdicBco' => $this->DatosAdicBco,
                'Localidad' => $this->Localidad,
                'Provincia' => $this->Provincia,
                'CodigoPostal' => $this->CodigoPostal,
            ];

            $currentSelectedUserId = $this->crearUsuarioAutomaticamente ? null : $this->user_id;

            if ($this->crearUsuarioAutomaticamente) {
                $newUser = User::create([
                    'name' => $this->NombreCompleto,
                    'email' => $this->Email,
                    'password' => Hash::make($this->newUserPassword),
                    'role' => 'portal',
                ]);
                $currentSelectedUserId = $newUser->id;
            }
            
            $trabajadorData['user_id'] = $currentSelectedUserId;

            if ($this->Foto) {
                if ($this->trabajador && $this->trabajador->Foto) {
                    Storage::disk('public')->delete($this->trabajador->Foto);
                }
                $trabajadorData['Foto'] = $this->Foto->store('trabajadores_fotos', 'public');
            }

            if ($this->trabajadorId) {
                // Actualizar Trabajador existente
                $trabajador = Trabajador::findOrFail($this->trabajadorId);
                $trabajador->update($trabajadorData);
                session()->flash('message', 'Trabajador actualizado exitosamente.');

                // Registrar cambios en el historial laboral
                $cambios = [];
                if (array_key_exists('Puesto', $originalData) && $originalData['Puesto'] !== $trabajador->Puesto) {
                    $cambios['Puesto'] = ['anterior' => $originalData['Puesto'], 'nuevo' => $trabajador->Puesto];
                }
                if (array_key_exists('Sector', $originalData) && $originalData['Sector'] !== $trabajador->Sector) {
                    $cambios['Sector'] = ['anterior' => $originalData['Sector'], 'nuevo' => $trabajador->Sector];
                }
                if (array_key_exists('Categoria', $originalData) && $originalData['Categoria'] !== $trabajador->Categoria) {
                    $cambios['Categoria'] = ['anterior' => $originalData['Categoria'], 'nuevo' => $trabajador->Categoria];
                }
                // Añadir lógica para Salario si es necesario

                if (!empty($cambios)) {
                    $descripcionHistorial = "Actualización de datos:";
                    foreach ($cambios as $campo => $valores) {
                        $descripcionHistorial .= " {$campo} ('{$valores['anterior']}' a '{$valores['nuevo']}');";
                    }

                    HistorialLaboral::create([
                        'trabajador_id' => $trabajador->ID_Trabajador,
                        'registrado_por_usuario_id' => Auth::id(),
                        'fecha_evento' => now(),
                        'tipo_evento' => 'Actualización de Datos',
                        'descripcion' => trim($descripcionHistorial),
                        'puesto_anterior' => $cambios['Puesto']['anterior'] ?? null,
                        'puesto_nuevo' => $cambios['Puesto']['nuevo'] ?? null,
                        'sector_anterior' => $cambios['Sector']['anterior'] ?? null,
                        'sector_nuevo' => $cambios['Sector']['nuevo'] ?? null,
                        'categoria_anterior' => $cambios['Categoria']['anterior'] ?? null,
                        'categoria_nueva' => $cambios['Categoria']['nuevo'] ?? null,
                        // 'salario_anterior' => ..., 
                        // 'salario_nuevo' => ...,
                    ]);
                }

            } else {
                // Crear nuevo Trabajador
                $trabajador = Trabajador::create($trabajadorData);
                session()->flash('message', 'Trabajador creado exitosamente.');
                // Registrar evento de creación en el historial
                HistorialLaboral::create([
                    'trabajador_id' => $trabajador->ID_Trabajador,
                    'registrado_por_usuario_id' => Auth::id(),
                    'fecha_evento' => $trabajador->FechaIngreso ?? now(), // Usar FechaIngreso si está disponible
                    'tipo_evento' => 'Alta de Trabajador',
                    'descripcion' => 'Trabajador creado en el sistema.',
                    'puesto_nuevo' => $trabajador->Puesto,
                    'sector_nuevo' => $trabajador->Sector,
                    'categoria_nueva' => $trabajador->Categoria,
                    // 'salario_nuevo' => $trabajador->Salario, // Si aplica
                ]);
            }
        }); // Fin de DB::transaction

        return redirect()->route('admin.trabajadores.index');
    }

    public function render()
    {
        if (!$this->crearUsuarioAutomaticamente) {
            $this->loadAvailableUsers(); // Recargar usuarios si el select es visible
        }
        return view('livewire.admin.trabajadores.create-edit-form', [
            'usersForSelect' => $this->availableUsers,
            'opcionesSector' => $this->opcionesSector,
            'opcionesPuesto' => $this->opcionesPuesto,
            'opcionesCategoria' => $this->opcionesCategoria,
        ]);
    }
}
