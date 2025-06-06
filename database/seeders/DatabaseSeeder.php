<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash; // Necesario para Hash::make

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            // Aquí puedes añadir otros seeders si los tienes
        ]);

        // User::factory(10)->withPersonalTeam()->create();

        // Crear o actualizar usuario administrador
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@example.com'], // Condición para buscar
            [
                'name' => 'Administrador del Sistema',
                'password' => Hash::make('password'), // Establecer una contraseña por defecto
                // email_verified_at si es necesario, etc.
            ]
        );

        // Crear equipo personal si el método createTeam está disponible (Jetstream)
        // Esto es un poco más complejo con updateOrCreate directamente, ya que no pasa por la factory completa.
        // Si createTeam es crucial, podríamos tener que encontrar el usuario y luego llamar a un método para crear el equipo.
        // Por ahora, nos enfocamos en que el usuario exista y tenga el rol.
        if (method_exists($adminUser, 'ownedTeams') && $adminUser->ownedTeams()->doesntExist() && method_exists($adminUser, 'switchTeam')) {
            if (method_exists(\App\Models\Team::class, 'forceCreate')) { // Asumiendo que Team model existe
                $team = \App\Models\Team::forceCreate([
                    'user_id' => $adminUser->id,
                    'name' => explode(' ', $adminUser->name, 2)[0]."'s Team",
                    'personal_team' => true,
                ]);
                $adminUser->switchTeam($team);
            }
        }

        if ($adminUser->wasRecentlyCreated || !$adminUser->hasRole('administrador')) {
            $adminUser->addRole('administrador');
            $this->command->info('Usuario administrador de prueba creado/actualizado y rol asignado.');
        } else {
            $this->command->info('Usuario administrador ya existía y tenía el rol.');
        }

        // Crear usuario específico solicitado
        $specificUser = User::updateOrCreate(
            ['email' => 'federicojfernandez@gmail.com'],
            [
                'name' => 'Federico Fernandez', // Puedes ajustar el nombre si lo deseas
                'password' => Hash::make('Intracoop1256$'),
            ]
        );

        // Crear equipo personal para el usuario específico
        if (method_exists($specificUser, 'ownedTeams') && $specificUser->ownedTeams()->doesntExist() && method_exists($specificUser, 'switchTeam')) {
            if (method_exists(\App\Models\Team::class, 'forceCreate')) {
                $specificUserTeam = \App\Models\Team::forceCreate([
                    'user_id' => $specificUser->id,
                    'name' => explode(' ', $specificUser->name, 2)[0]."'s Team",
                    'personal_team' => true,
                ]);
                $specificUser->switchTeam($specificUserTeam);
            }
        }

        if ($specificUser->wasRecentlyCreated || !$specificUser->hasRole('administrador')) {
            $specificUser->addRole('administrador');
            $this->command->info('Usuario federicojfernandez@gmail.com creado/actualizado y rol administrador asignado.');
        } else {
            $this->command->info('Usuario federicojfernandez@gmail.com ya existía y tenía el rol administrador.');
        }
    }
}
