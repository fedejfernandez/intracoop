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
        // Modificar tabla vacaciones para el nuevo flujo
        Schema::table('vacaciones', function (Blueprint $table) {
            // Cambiar Estado_Solicitud para incluir nuevos estados
            $table->dropColumn('Estado_Solicitud');
        });

        Schema::table('vacaciones', function (Blueprint $table) {
            $table->enum('Estado_Solicitud', [
                'Pendiente',           // Inicial - esperando jefe de área
                'AprobadaJefe',        // Aprobada por jefe de área, esperando RRHH
                'RechazadaJefe',       // Rechazada por jefe de área
                'AprobadaRRHH',        // Aprobación final por RRHH
                'RechazadaRRHH',       // Rechazada por RRHH
                'Cancelada'            // Cancelada por el trabajador
            ])->default('Pendiente');

            // Campos para tracking de aprobaciones
            $table->foreignId('aprobado_por_jefe')->nullable()->constrained('users')->after('Aprobado_por');
            $table->timestamp('fecha_aprobacion_jefe')->nullable()->after('aprobado_por_jefe');
            $table->text('comentarios_jefe')->nullable()->after('fecha_aprobacion_jefe');
            
            $table->foreignId('aprobado_por_rrhh')->nullable()->constrained('users')->after('comentarios_jefe');
            $table->timestamp('fecha_aprobacion_rrhh')->nullable()->after('aprobado_por_rrhh');
            $table->text('comentarios_rrhh')->nullable()->after('fecha_aprobacion_rrhh');

            // Renombrar campo existente
            $table->renameColumn('Aprobado_por', 'aprobado_por_legacy');
            $table->renameColumn('Fecha_Aprobacion_Rechazo', 'fecha_aprobacion_legacy');
            $table->renameColumn('Comentarios_Admin', 'comentarios_legacy');
        });

        // Modificar tabla licencias para el nuevo flujo
        Schema::table('licencias', function (Blueprint $table) {
            // Cambiar Estado_Solicitud para incluir nuevos estados
            $table->dropColumn('Estado_Solicitud');
        });

        Schema::table('licencias', function (Blueprint $table) {
            $table->enum('Estado_Solicitud', [
                'Pendiente',           // Inicial - esperando jefe de área
                'AprobadaJefe',        // Aprobada por jefe de área, esperando RRHH
                'RechazadaJefe',       // Rechazada por jefe de área
                'AprobadaRRHH',        // Aprobación final por RRHH
                'RechazadaRRHH',       // Rechazada por RRHH
                'Cancelada'            // Cancelada por el trabajador
            ])->default('Pendiente');

            // Campos para tracking de aprobaciones
            $table->foreignId('aprobado_por_jefe')->nullable()->constrained('users')->after('Aprobado_por');
            $table->timestamp('fecha_aprobacion_jefe')->nullable()->after('aprobado_por_jefe');
            $table->text('comentarios_jefe')->nullable()->after('fecha_aprobacion_jefe');
            
            $table->foreignId('aprobado_por_rrhh')->nullable()->constrained('users')->after('comentarios_jefe');
            $table->timestamp('fecha_aprobacion_rrhh')->nullable()->after('aprobado_por_rrhh');
            $table->text('comentarios_rrhh')->nullable()->after('fecha_aprobacion_rrhh');

            // Renombrar campos existentes
            $table->renameColumn('Aprobado_por', 'aprobado_por_legacy');
            $table->renameColumn('Fecha_Aprobacion_Rechazo', 'fecha_aprobacion_legacy');
            $table->renameColumn('Comentarios_Admin', 'comentarios_legacy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revertir vacaciones
        Schema::table('vacaciones', function (Blueprint $table) {
            $table->dropForeign(['aprobado_por_jefe']);
            $table->dropForeign(['aprobado_por_rrhh']);
            $table->dropColumn([
                'aprobado_por_jefe', 'fecha_aprobacion_jefe', 'comentarios_jefe',
                'aprobado_por_rrhh', 'fecha_aprobacion_rrhh', 'comentarios_rrhh'
            ]);
            
            $table->renameColumn('aprobado_por_legacy', 'Aprobado_por');
            $table->renameColumn('fecha_aprobacion_legacy', 'Fecha_Aprobacion_Rechazo');
            $table->renameColumn('comentarios_legacy', 'Comentarios_Admin');
            
            $table->dropColumn('Estado_Solicitud');
        });

        Schema::table('vacaciones', function (Blueprint $table) {
            $table->enum('Estado_Solicitud', ['Pendiente', 'Aprobada', 'Rechazada'])->default('Pendiente');
        });

        // Revertir licencias
        Schema::table('licencias', function (Blueprint $table) {
            $table->dropForeign(['aprobado_por_jefe']);
            $table->dropForeign(['aprobado_por_rrhh']);
            $table->dropColumn([
                'aprobado_por_jefe', 'fecha_aprobacion_jefe', 'comentarios_jefe',
                'aprobado_por_rrhh', 'fecha_aprobacion_rrhh', 'comentarios_rrhh'
            ]);
            
            $table->renameColumn('aprobado_por_legacy', 'Aprobado_por');
            $table->renameColumn('fecha_aprobacion_legacy', 'Fecha_Aprobacion_Rechazo');
            $table->renameColumn('comentarios_legacy', 'Comentarios_Admin');
            
            $table->dropColumn('Estado_Solicitud');
        });

        Schema::table('licencias', function (Blueprint $table) {
            $table->enum('Estado_Solicitud', ['Pendiente', 'Aprobada', 'Rechazada'])->default('Pendiente');
        });
    }
}; 