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
       Schema::create('vacaciones', function (Blueprint $table) {
    $table->id('ID_Vacaciones');
    $table->foreignId('ID_Trabajador')->constrained('trabajadores', 'ID_Trabajador')->onDelete('cascade');
    $table->date('Fecha_Inicio');
    $table->date('Fecha_Fin');
    $table->integer('Dias_Solicitados'); // Se puede calcular
    $table->enum('Estado_Solicitud', ['Pendiente', 'Aprobada', 'Rechazada'])->default('Pendiente');
    $table->foreignId('Aprobado_por')->nullable()->constrained('users')->onDelete('set null'); // ID del User admin que aprueba
    $table->timestamp('Fecha_Aprobacion_Rechazo')->nullable();
    $table->text('Comentarios_Admin')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vacaciones');
    }
};
