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
        Schema::create('trabajadores', function (Blueprint $table) {
    $table->id('ID_Trabajador'); // O simplemente $table->id(); y Laravel lo maneja
    $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Enlaza con la tabla users
    $table->string('NombreCompleto');
    $table->string('DNI_CUIL')->unique();
    $table->date('FechaNacimiento');
    $table->date('FechaIngreso');
    $table->string('Puesto')->nullable();
    $table->string('Sector')->nullable();
    $table->string('Email')->unique(); // Podría ser el mismo que el del User
    $table->string('Telefono')->nullable();
    $table->text('Direccion')->nullable();
    $table->string('Foto')->nullable(); // Path a la imagen
    $table->enum('Estado', ['Activo', 'Inactivo', 'Licencia'])->default('Activo');
    $table->integer('DiasVacacionesAnuales')->default(14); // O el valor por defecto que corresponda
    // RolUsuario ya lo manejamos en la tabla 'users'
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trabajadores');
    }
};
