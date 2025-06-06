<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// Illuminate\Database\Eloquent\Model; // No es necesario si LaratrustBaseRole lo extiende
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
// Laratrust\Contracts\LaratrustRole as LaratrustRoleContract; // No es necesario
// Laratrust\Traits\LaratrustRoleTrait; // No es necesario
// Ya no se necesita el alias: use Laratrust\Models\Role as LaratrustBaseRole;

class Role extends \Laratrust\Models\Role // Extender directamente con \ al inicio
{
    use HasFactory; // Quitar LaratrustRoleTrait de aquí

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relación con permisos
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions');
    }

    /**
     * Relación con usuarios
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_roles')->withPivot('sector');
    }
} 