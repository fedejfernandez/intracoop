<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Contracts\LaratrustUser;
use Laratrust\Traits\HasRolesAndPermissions;

class User extends Authenticatable implements LaratrustUser
{
    use HasApiTokens,
        HasFactory,
        HasProfilePhoto,
        Notifiable,
        TwoFactorAuthenticatable,
        // Laratrust and Jetstream traits with conflict resolution
        \Laratrust\Traits\HasRolesAndPermissions, // Using FQCN
        \Laravel\Jetstream\HasTeams { // Using FQCN
            \Laravel\Jetstream\HasTeams::allTeams insteadof \Laratrust\Traits\HasRolesAndPermissions;
            \Laratrust\Traits\HasRolesAndPermissions::allTeams as laratrustAllTeams;
    }

    /** @use HasFactory<\Database\Factories\UserFactory> */
    // This line (use HasFactory;) might be redundant if already included above, model will remove it if so.

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the trabajador associated with the user.
     */
    public function trabajador()
    {
        return $this->hasOne(Trabajador::class, 'user_id');
    }

    /**
     * The roles that belong to the user.
     * Overridden to include 'sector' from the pivot table.
     */
    public function roles(): \Illuminate\Database\Eloquent\Relations\MorphToMany
    {
        // Obtener la configuración de Laratrust
        $config = config('laratrust');

        $relationship = $this->morphToMany(
            $config['models']['role'],
            'user',
            'user_roles',
            $config['foreign_keys']['user'],
            $config['foreign_keys']['role']
        );

        // Añadir el campo 'sector' a los datos pivot
        $relationship->withPivot('sector');

        // Si los equipos están habilitados en Laratrust, también incluir la foreign key del equipo
        if ($config['teams']['enabled'] === true) {
            $relationship->withPivot($config['foreign_keys']['team']);
        }
        
        return $relationship;
    }

    /**
     * Obtiene el sector asignado al usuario si tiene el rol 'jefe_area'.
     *
     * @return string|null
     */
    public function getSectorAsignado(): ?string
    {
        // Accede a la colección de roles del usuario.
        // Si la relación 'roles' fue definida con withPivot('sector'),
        // cada rol en esta colección debería tener acceso a $role->pivot->sector.
        foreach ($this->roles as $role) { 
            if ($role->name === 'jefe_area') {
                // Verifica si el atributo pivot existe y si 'sector' está en él
                if (isset($role->pivot) && isset($role->pivot->sector)) {
                    return $role->pivot->sector;
                }
                // Si el rol es 'jefe_area' pero no hay 'sector' en el pivot,
                // puede que no se haya guardado o cargado correctamente.
                // Devolvemos null o podrías loguear una advertencia aquí.
                return null; 
            }
        }
        // Si el bucle termina, el usuario no tiene el rol 'jefe_area'.
        return null;
    }

    public function testLaratrustMethods()
    {
        return [
            'isAdmin' => $this->hasRole('administrador'),
            'isRRHH' => $this->hasRole('rrhh'),
            'isJefeArea' => $this->hasRole('jefe_area'),
            'isPortal' => $this->hasRole('portal'),
            'canViewTrabajadores' => $this->hasPermission('trabajadores.view')
        ];
    }

    /**
     * Obtiene todas las evaluaciones de desempeño realizadas por este usuario (como evaluador).
     */
    public function evaluacionesRealizadas()
    {
        return $this->hasMany(EvaluacionDesempeno::class, 'evaluador_id');
    }
}
