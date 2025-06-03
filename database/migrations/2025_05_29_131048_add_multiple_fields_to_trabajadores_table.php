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
            $table->string('Localidad')->nullable()->after('Direccion');
            $table->string('Provincia')->nullable()->after('Localidad');
            $table->string('CodigoPostal')->nullable()->after('Provincia');
            $table->string('TipoDocumento')->nullable()->after('DNI_CUIL');
            $table->string('Nacionalidad')->nullable()->after('FechaNacimiento');
            $table->string('EstadoCivil')->nullable()->after('Nacionalidad');
            $table->string('Sexo')->nullable()->after('EstadoCivil');
            $table->string('Banco')->nullable()->after('Email');
            $table->string('NroCuentaBancaria')->nullable()->after('Banco');
            $table->string('CBU')->nullable()->after('NroCuentaBancaria');
            $table->text('DatosAdicBco')->nullable()->after('CBU');
            $table->string('CentroCostos')->nullable()->after('Sector');
            $table->string('Categoria')->nullable()->after('Puesto');
            $table->date('FechaReconocida')->nullable()->after('FechaIngreso');
            $table->string('CCT')->nullable()->after('Sector'); // Convenio Colectivo de Trabajo
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trabajadores', function (Blueprint $table) {
            $table->dropColumn([
                'Localidad', 'Provincia', 'CodigoPostal', 'TipoDocumento', 'Nacionalidad',
                'EstadoCivil', 'Sexo', 'Banco', 'NroCuentaBancaria', 'CBU',
                'DatosAdicBco', 'CentroCostos', 'Categoria', 'FechaReconocida', 'CCT'
            ]);
        });
    }
};
