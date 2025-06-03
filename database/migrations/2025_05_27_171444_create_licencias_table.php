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
    Schema::create('licencias', function (Blueprint $table) {
    $table->id('ID_Licencia');
    $table->foreignId('ID_Empleado')->constrained('trabajadores', 'ID_Trabajador')->onDelete('cascade');
    $table->string('TipoLicencia'); // Considerar tabla aparte si son muchos tipos o con propiedades
    $table->date('FechaInicio');
    $table->date('FechaFin');
    $table->integer('CantidadDias'); // Se puede calcular
    $table->text('Motivo')->nullable();
    $table->string('Certificado')->nullable(); // Path al archivo
    $table->enum('Estado_Solicitud', ['Pendiente', 'Aprobada', 'Rechazada'])->default('Pendiente');
    $table->foreignId('Aprobado_por')->nullable()->constrained('users')->onDelete('set null'); // ID del User admin que aprueba
    $table->timestamp('Fecha_Aprobacion_Rechazo')->nullable();
    $table->text('Comentarios_Admin')->nullable(); // Renombrado para claridad
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('licencias');
    }
};
