<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class AssignAdminRole extends Command
{
    protected $signature = 'user:make-admin {email : El email del usuario a convertir en administrador}';
    protected $description = 'Asigna el rol de administrador a un usuario existente';

    public function handle()
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("No se encontró un usuario con el email: {$email}");
            return 1;
        }

        $user->assignRole('administrador');
        $this->info("Se asignó el rol de administrador al usuario: {$user->name} ({$user->email})");
        return 0;
    }
} 