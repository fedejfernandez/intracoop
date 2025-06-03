<?php

namespace App\Livewire\Admin\Trabajadores;

use App\Models\Trabajador;
use App\Models\User; // Importar el modelo User
use Livewire\Component;
use Livewire\WithFileUploads; // Para el campo de foto
use Illuminate\Support\Facades\Storage; // Para manejar el almacenamiento de fotos
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Support\Facades\DB; // Importar DB
use Illuminate\Support\Facades\Config; // Importar Config

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

    protected function rules()
    {
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
            'DiasVacacionesAnuales' => 'required|integer|min:0',
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
            $rules['user_id'] = ['nullable', function ($attribute, $value, $fail) {
                if ($value === '') return;
                if (!User::find($value)) {
                    $fail('El usuario seleccionado no es válido.');
                }
            }];
        }
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
        }
        $this->loadAvailableUsers();
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
        if ($this->user_id === '') {
            $this->user_id = null;
        }

        $currentSelectedUserId = $this->crearUsuarioAutomaticamente ? null : $this->user_id;

        $this->validate(); 

        DB::transaction(function () use ($currentSelectedUserId) {
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
                'DiasVacacionesAnuales' => $this->DiasVacacionesAnuales,
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

            $finalUserId = $currentSelectedUserId;

            if ($this->crearUsuarioAutomaticamente) {
                $newUser = User::create([
                    'name' => $this->NombreCompleto,
                    'email' => $this->Email,
                    'password' => Hash::make($this->newUserPassword),
                    'role' => 'portal',
                ]);
                $finalUserId = $newUser->id;
            }
            
            $trabajadorData['user_id'] = $finalUserId;

            if ($this->Foto) {
                if ($this->trabajador && $this->trabajador->Foto) {
                    Storage::disk('public')->delete($this->trabajador->Foto);
                }
                $trabajadorData['Foto'] = $this->Foto->store('trabajadores_fotos', 'public');
            }

            if ($this->trabajador) {
                $this->trabajador->update($trabajadorData);
                session()->flash('message', 'Trabajador actualizado correctamente.');
            } else {
                // Si es un nuevo trabajador, necesitamos la instancia para potencialmente asociar un usuario después
                $newTrabajador = Trabajador::create($trabajadorData);
                // No necesitamos hacer nada más con $newTrabajador aquí si user_id ya está en $trabajadorData
                session()->flash('message', 'Trabajador creado correctamente.');
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
