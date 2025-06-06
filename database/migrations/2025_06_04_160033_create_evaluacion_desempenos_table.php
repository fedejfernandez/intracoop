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
        Schema::dropIfExists('evaluaciones_desempeno');
        Schema::create('evaluaciones_desempeno', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trabajador_id')->constrained('trabajadores', 'ID_Trabajador')->onDelete('cascade');
            $table->foreignId('evaluador_id')->nullable()->constrained('users')->onDelete('set null'); // Quién realizó la evaluación
            $table->date('fecha_evaluacion');
            $table->date('periodo_evaluado_inicio');
            $table->date('periodo_evaluado_fin');
            $table->string('calificacion_general')->nullable(); // Ej: Necesita Mejorar, Cumple Expectativas, Supera Expectativas
            $table->text('fortalezas')->nullable();
            $table->text('areas_mejora')->nullable();
            $table->text('comentarios_evaluador')->nullable();
            $table->text('comentarios_trabajador')->nullable(); // Feedback del trabajador
            $table->string('estado')->default('Borrador'); // Ej: Borrador, Publicada, Discutida, Cerrada
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluaciones_desempeno');
    }
};
