<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role; // Asegúrate que el namespace de tu modelo Role sea este
use App\Models\Permission; // <-- Añadir importación del modelo Permission

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rolesData = [ // Renombrado para claridad
            [
                'name' => 'administrador',
                'display_name' => 'Administrador General',
                'description' => 'Usuario con todos los permisos del sistema'
            ],
            [
                'name' => 'rrhh',
                'display_name' => 'Recursos Humanos',
                'description' => 'Usuario con permisos para gestionar RRHH'
            ],
            [
                'name' => 'jefe_area',
                'display_name' => 'Jefe de Área',
                'description' => 'Usuario con permisos para gestionar su área'
            ],
            [
                'name' => 'portal',
                'display_name' => 'Portal Trabajador',
                'description' => 'Rol básico para trabajadores con acceso al portal'
            ],
        ];

        foreach ($rolesData as $roleInfo) { // Usar variable renombrada
            Role::updateOrCreate(['name' => $roleInfo['name']], $roleInfo);
        }
        $this->command->info('Roles básicos creados/actualizados exitosamente.');

        // ---- INICIO: Crear Permisos y Asignar a Administrador ----
        $permissionsData = [
            [
                'name' => 'admin.roles.index', 
                'display_name' => 'Ver Roles', 
                'description' => 'Permite ver la lista de roles y sus detalles', 
                'module' => 'Gestión de Roles y Permisos'
            ],
            [
                'name' => 'admin.roles.create', 
                'display_name' => 'Crear Roles', 
                'description' => 'Permite crear nuevos roles en el sistema', 
                'module' => 'Gestión de Roles y Permisos'
            ],
            [
                'name' => 'admin.roles.edit', 
                'display_name' => 'Editar Roles', 
                'description' => 'Permite modificar roles existentes', 
                'module' => 'Gestión de Roles y Permisos'
            ],
            [
                'name' => 'admin.roles.delete', 
                'display_name' => 'Eliminar Roles', 
                'description' => 'Permite eliminar roles', 
                'module' => 'Gestión de Roles y Permisos'
            ],
            [
                'name' => 'admin.permissions.assign', 
                'display_name' => 'Asignar Permisos a Roles', 
                'description' => 'Permite asignar y quitar permisos a los roles', 
                'module' => 'Gestión de Roles y Permisos'
            ],
        ];

        $createdPermissionNames = [];
        foreach ($permissionsData as $permissionInfo) {
            $permission = Permission::firstOrCreate(
                ['name' => $permissionInfo['name']],
                [
                    'display_name' => $permissionInfo['display_name'],
                    'description' => $permissionInfo['description'],
                    'module' => $permissionInfo['module']
                ]
            );
            $createdPermissionNames[] = $permission->name; // Guardar nombres para syncPermissions
        }
        $this->command->info(count($createdPermissionNames) . ' permisos de gestión de roles creados/actualizados.');

        // Asignar los permisos al rol 'administrador'
        $adminRole = Role::where('name', 'administrador')->first();
        if ($adminRole) {
            $adminRole->syncPermissions($createdPermissionNames); // Laratrust puede sincronizar por nombres
            $this->command->info('Permisos de gestión de roles asignados al rol "administrador".');
        } else {
            $this->command->warn('El rol "administrador" no fue encontrado. No se asignaron permisos de gestión.');
        }
        // ---- FIN: Crear Permisos y Asignar a Administrador ----
    }
} 