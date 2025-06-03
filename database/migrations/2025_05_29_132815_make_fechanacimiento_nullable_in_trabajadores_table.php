<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('trabajadores', function (Blueprint $table) {
            $table->date('FechaNacimiento')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trabajadores', function (Blueprint $table) {
            // Si se revierte, volvemos a no nullable. 
            // ¡PRECAUCIÓN! Esto fallará si hay datos NULL existentes.
            // Lo ideal es asegurar que no haya NULLs antes de revertir o manejar la lógica.
            $table->date('FechaNacimiento')->nullable(false)->change();
        });
    }
};
