<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// Illuminate\Database\Eloquent\Model; // No es necesario si LaratrustBasePermission lo extiende
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
// Laratrust\Contracts\LaratrustPermission as LaratrustPermissionContract; // No es necesario
// Laratrust\Traits\LaratrustPermissionTrait; // No es necesario
// Ya no se necesita el alias: use Laratrust\Models\Permission as LaratrustBasePermission;

class Permission extends \Laratrust\Models\Permission // Extender directamente con \ al inicio
{
    use HasFactory; // Quitar LaratrustPermissionTrait de aquí

    protected $fillable = [
        'name',
        'display_name',
        'description',
        'module'
    ];

    /**
     * Relación con roles
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions');
    }

    /**
     * Obtener permisos por módulo
     */
    public static function byModule(string $module)
    {
        return static::where('module', $module)->get();
    }

    /**
     * Obtener todos los módulos disponibles
     */
    public static function getModules(): array
    {
        return static::distinct('module')->pluck('module')->toArray();
    }
} 