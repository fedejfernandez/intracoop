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
            // Primero eliminamos el índice unique si existe, luego cambiamos a nullable
            // Es importante verificar si el índice se llama 'trabajadores_email_unique' 
            // o como Laravel lo haya nombrado.
            // Por defecto, Laravel lo nombra así: table_column_type
            // $table->dropUnique('trabajadores_email_unique'); 
            // En lugar de adivinar, podemos pasar un array con el nombre de la columna.
            $table->dropUnique(['Email']);
            $table->string('Email')->nullable()->change();
            // Volvemos a añadir el unique, pero ahora permitirá NULLs (la mayoría de BDs lo hacen, MySQL sí)
            $table->unique('Email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trabajadores', function (Blueprint $table) {
            // Para revertir, quitamos el unique que permite NULLs, 
            // hacemos no nullable, y luego ponemos el unique original.
            // ¡PRECAUCIÓN con datos existentes!
            $table->dropUnique(['Email']);
            $table->string('Email')->nullable(false)->change();
            $table->unique('Email'); 
        });
    }
};
