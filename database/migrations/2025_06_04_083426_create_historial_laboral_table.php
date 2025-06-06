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
        Schema::create('historial_laboral', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trabajador_id')->constrained('trabajadores', 'ID_Trabajador')->cascadeOnDelete();
            $table->foreignId('registrado_por_usuario_id')->nullable()->constrained('users')->onDelete('set null');
            $table->date('fecha_evento');
            $table->string('tipo_evento'); // Ej: "Cambio de Puesto", "Promoción", "Inicio Contrato", "Cambio Salario"
            $table->text('descripcion')->nullable();
            $table->string('puesto_anterior')->nullable();
            $table->string('puesto_nuevo')->nullable();
            $table->string('sector_anterior')->nullable();
            $table->string('sector_nuevo')->nullable();
            $table->string('categoria_anterior')->nullable();
            $table->string('categoria_nueva')->nullable();
            $table->decimal('salario_anterior', 10, 2)->nullable();
            $table->decimal('salario_nuevo', 10, 2)->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historial_laboral');
    }
};
