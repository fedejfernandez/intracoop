<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea o actualiza un usuario administrador con el rol correspondiente';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Crear o encontrar el rol de administrador
        $adminRole = Role::firstOrCreate(
            ['name' => 'administrador'], // Usar 'administrador' para consistencia
            [
                'display_name' => 'Administrador',
                'description' => 'Administrador del sistema con acceso completo'
            ]
        );

        // Crear o actualizar el usuario
        $user = User::updateOrCreate(
            ['email' => $this->argument('email')],
            [
                'name' => 'Administrador ' . $this->argument('email'),
                'password' => Hash::make($this->argument('password')),
            ]
        );

        // Asignar el rol de administrador
        $user->addRole($adminRole);

        $this->info('Usuario administrador creado/actualizado exitosamente.');
        $this->info('Email: ' . $user->email);
    }
}
