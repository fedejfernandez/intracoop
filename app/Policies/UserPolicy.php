<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        // Generalmente, los administradores o usuarios con permisos específicos pueden ver la lista de usuarios.
        return $user->hasRole('administrador') || $user->hasRole('rrhh');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $model)
    {
        // Un usuario puede ver su propio perfil, o administradores/rrhh pueden ver cualquier perfil.
        return $user->id === $model->id || $user->hasRole('administrador') || $user->hasRole('rrhh');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        // Generalmente, los administradores o usuarios con permisos específicos pueden crear usuarios.
        return $user->hasRole('administrador') || $user->hasRole('rrhh');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $model)
    {
        // Un usuario puede actualizar su propio perfil.
        // Administradores/RRHH pueden actualizar cualquier perfil.
        // Si es el mismo usuario, permitir (Jetstream maneja esto para el perfil).
        if ($user->id === $model->id) {
            return true;
        }
        // Si es un administrador o RRHH intentando editar otro usuario.
        return $user->hasRole('administrador') || $user->hasRole('rrhh');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user  // El usuario autenticado que realiza la acción
     * @param  \App\Models\User  $model // El usuario que se intenta eliminar
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $model): bool
    {
        // Regla 1: Un usuario con rol "Portal" NO PUEDE eliminarse a sí mismo.
        if ($user->id === $model->id && $user->hasRole('portal')) {
            return false;
        }

        // Regla 2: Un usuario puede eliminarse a sí mismo (si no es "Portal", ya cubierto arriba).
        if ($user->id === $model->id) {
            return true;
        }

        // Regla 3: Administradores pueden eliminar a OTROS usuarios.
        // (Asegúrate de que el rol 'administrador' no sea 'portal' para evitar conflictos lógicos)
        if ($user->hasRole('administrador')) {
            // Opcional: Un administrador no puede eliminar a otro administrador como medida de seguridad
            // if ($model->hasRole('administrador') && $user->id !== $model->id) {
            // return false; // O permitir si se desea
            // }
            return true;
        }
        
        // Por defecto, denegar cualquier otra eliminación.
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, User $model)
    {
        return $user->hasRole('administrador');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, User $model)
    {
        // Solo administradores pueden eliminar permanentemente, y no a sí mismos si son el último admin.
        if ($user->hasRole('administrador')) {
            // Prevenir que un admin se elimine permanentemente a sí mismo si es el único admin.
            // Esta lógica puede ser más compleja dependiendo de los requisitos.
            // if ($user->id === $model->id && User::whereHas('roles', fn($q) => $q->where('name', 'administrador'))->count() === 1) {
            // return false;
            // }
            return true;
        }
        return false;
    }
} 