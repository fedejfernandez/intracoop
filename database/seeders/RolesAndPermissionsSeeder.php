<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear permisos por módulo
        $permissions = [
            // Módulo Admin
            'admin' => [
                'admin.dashboard' => 'Ver dashboard de administración',
                'admin.users.index' => 'Listar usuarios',
                'admin.users.create' => 'Crear usuarios',
                'admin.users.edit' => 'Editar usuarios',
                'admin.users.delete' => 'Eliminar usuarios',
                'admin.roles.index' => 'Gestionar roles',
                'admin.roles.assign' => 'Asignar roles',
                'admin.permissions.manage' => 'Gestionar permisos',
                'admin.system.settings' => 'Configuración del sistema',
            ],

            // Módulo RRHH
            'rrhh' => [
                'rrhh.dashboard' => 'Ver dashboard de RRHH',
                'rrhh.trabajadores.index' => 'Listar todos los trabajadores',
                'rrhh.trabajadores.create' => 'Crear trabajadores',
                'rrhh.trabajadores.edit' => 'Editar trabajadores',
                'rrhh.trabajadores.delete' => 'Eliminar trabajadores',
                'rrhh.trabajadores.import' => 'Importar nómina',
                'rrhh.trabajadores.export' => 'Exportar trabajadores',
                'rrhh.vacaciones.final_approval' => 'Aprobación final de vacaciones',
                'rrhh.licencias.final_approval' => 'Aprobación final de licencias',
                'rrhh.reports.view' => 'Ver reportes de RRHH',
                'rrhh.statistics.view' => 'Ver estadísticas generales',
            ],

            // Módulo Jefe de Área
            'jefe_area' => [
                'jefe.dashboard' => 'Ver dashboard de jefe de área',
                'jefe.trabajadores.view_sector' => 'Ver trabajadores de su sector',
                'jefe.vacaciones.approve' => 'Aprobar vacaciones de su sector',
                'jefe.licencias.approve' => 'Aprobar licencias de su sector',
                'jefe.reports.sector' => 'Ver reportes de su sector',
            ],

            // Módulo Portal
            'portal' => [
                'portal.dashboard' => 'Ver dashboard personal',
                'portal.profile.view' => 'Ver perfil personal',
                'portal.profile.edit' => 'Editar datos personales limitados',
                'portal.password.change' => 'Cambiar contraseña',
                'portal.vacaciones.request' => 'Solicitar vacaciones',
                'portal.licencias.request' => 'Solicitar licencias',
                'portal.legajo.view' => 'Ver legajo personal',
                'portal.history.view' => 'Ver historial de solicitudes',
            ],
        ];

        // Crear permisos
        foreach ($permissions as $module => $modulePermissions) {
            foreach ($modulePermissions as $name => $description) {
                Permission::create([
                    'name' => $name,
                    'display_name' => ucfirst(str_replace(['_', '.'], [' ', ' '], $name)),
                    'description' => $description,
                    'module' => $module,
                ]);
            }
        }

        // Crear roles
        $roles = [
            [
                'name' => 'administrador',
                'display_name' => 'Administrador',
                'description' => 'Acceso total al sistema. Puede gestionar usuarios, roles y configuraciones.',
                'permissions' => array_keys($permissions['admin']) + array_keys($permissions['rrhh']) + array_keys($permissions['jefe_area']) + array_keys($permissions['portal'])
            ],
            [
                'name' => 'rrhh',
                'display_name' => 'Recursos Humanos',
                'description' => 'Gestiona trabajadores, aprueba solicitudes finalmente y ve estadísticas.',
                'permissions' => array_keys($permissions['rrhh']) + array_keys($permissions['portal'])
            ],
            [
                'name' => 'jefe_area',
                'display_name' => 'Jefe de Área',
                'description' => 'Gestiona trabajadores de su sector y aprueba solicitudes para envío a RRHH.',
                'permissions' => array_keys($permissions['jefe_area']) + array_keys($permissions['portal'])
            ],
            [
                'name' => 'portal',
                'display_name' => 'Portal Trabajador',
                'description' => 'Acceso básico para trabajadores. Puede ver su información y hacer solicitudes.',
                'permissions' => array_keys($permissions['portal'])
            ],
        ];

        foreach ($roles as $roleData) {
            $role = Role::create([
                'name' => $roleData['name'],
                'display_name' => $roleData['display_name'],
                'description' => $roleData['description'],
            ]);

            // Asignar permisos al rol
            $permissionsToAssign = Permission::whereIn('name', $roleData['permissions'])->get();
            $role->permissions()->attach($permissionsToAssign);
        }

        $this->command->info('Roles y permisos creados exitosamente.');
    }
} 