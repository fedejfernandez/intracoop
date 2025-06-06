<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Trabajador;
use Carbon\Carbon;

class TrabajadorVacacionesTest extends TestCase
{
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        $this->assertTrue(true);
    }

    /**
     * Test de cálculo de días de vacaciones para trabajador con menos de 5 años
     */
    public function test_calcula_vacaciones_menos_de_5_anios()
    {
        $trabajador = new Trabajador();
        $trabajador->FechaIngreso = Carbon::create(2022, 1, 15); // 3 años de antigüedad al 31/12/2025
        $trabajador->CCT = '36/75';
        
        $diasVacaciones = $trabajador->calcularDiasVacacionesAnuales(2025);
        
        $this->assertEquals(14, $diasVacaciones);
    }

    /**
     * Test de cálculo de días de vacaciones para trabajador con 5-10 años
     */
    public function test_calcula_vacaciones_5_a_10_anios()
    {
        $trabajador = new Trabajador();
        $trabajador->FechaIngreso = Carbon::create(2018, 6, 10); // 7 años de antigüedad al 31/12/2025
        $trabajador->CCT = '459/06';
        
        $diasVacaciones = $trabajador->calcularDiasVacacionesAnuales(2025);
        
        $this->assertEquals(21, $diasVacaciones);
    }

    /**
     * Test de cálculo de días de vacaciones para trabajador con 10-20 años
     */
    public function test_calcula_vacaciones_10_a_20_anios()
    {
        $trabajador = new Trabajador();
        $trabajador->FechaIngreso = Carbon::create(2010, 3, 20); // 15 años de antigüedad al 31/12/2025
        $trabajador->CCT = '177/75';
        
        $diasVacaciones = $trabajador->calcularDiasVacacionesAnuales(2025);
        
        $this->assertEquals(28, $diasVacaciones);
    }

    /**
     * Test de cálculo de días de vacaciones para trabajador con más de 20 años
     */
    public function test_calcula_vacaciones_mas_de_20_anios()
    {
        $trabajador = new Trabajador();
        $trabajador->FechaIngreso = Carbon::create(2000, 8, 30); // 25 años de antigüedad al 31/12/2025
        $trabajador->CCT = '107/75';
        
        $diasVacaciones = $trabajador->calcularDiasVacacionesAnuales(2025);
        
        $this->assertEquals(35, $diasVacaciones);
    }

    /**
     * Test de cálculo de antigüedad en años
     */
    public function test_calcula_antiguedad_en_anios()
    {
        $trabajador = new Trabajador();
        $trabajador->FechaIngreso = Carbon::create(2020, 4, 15);
        
        $antiguedad = $trabajador->obtenerAntiguedadEnAnios(2025);
        
        $this->assertEquals(5, $antiguedad);
    }

    /**
     * Test de trabajador sin fecha de ingreso
     */
    public function test_trabajador_sin_fecha_ingreso()
    {
        $trabajador = new Trabajador();
        $trabajador->FechaIngreso = null;
        $trabajador->CCT = '36/75';
        
        $diasVacaciones = $trabajador->calcularDiasVacacionesAnuales();
        
        $this->assertEquals(14, $diasVacaciones); // Valor por defecto
    }

    /**
     * Test de CCT no reconocido (usa escala estándar)
     */
    public function test_cct_no_reconocido_usa_escala_estandar()
    {
        $trabajador = new Trabajador();
        $trabajador->FechaIngreso = Carbon::create(2015, 1, 1); // 10 años de antigüedad al 31/12/2025
        $trabajador->CCT = 'CCT_INEXISTENTE';
        
        $diasVacaciones = $trabajador->calcularDiasVacacionesAnuales(2025);
        
        $this->assertEquals(28, $diasVacaciones); // Escala estándar para 10 años
    }

    /**
     * Test de límites: exactamente 5 años
     */
    public function test_limite_exactamente_5_anios()
    {
        $trabajador = new Trabajador();
        $trabajador->FechaIngreso = Carbon::create(2020, 12, 31); // Exactamente 5 años al 31/12/2025
        $trabajador->CCT = '36/75';
        
        $diasVacaciones = $trabajador->calcularDiasVacacionesAnuales(2025);
        
        $this->assertEquals(14, $diasVacaciones); // Hasta 5 años = 14 días
    }

    /**
     * Test de límites: 6 años (más de 5)
     */
    public function test_limite_6_anios()
    {
        $trabajador = new Trabajador();
        $trabajador->FechaIngreso = Carbon::create(2019, 12, 31); // 6 años al 31/12/2025
        $trabajador->CCT = '36/75';
        
        $diasVacaciones = $trabajador->calcularDiasVacacionesAnuales(2025);
        
        $this->assertEquals(21, $diasVacaciones); // Más de 5 años = 21 días
    }

    /**
     * Test con método dinámico diasVacacionesCalculados
     */
    public function test_metodo_dinamico_dias_vacaciones_calculados()
    {
        $trabajador = new Trabajador();
        $trabajador->FechaIngreso = Carbon::create(2010, 6, 15); // 15 años de antigüedad
        $trabajador->CCT = '177/75';
        
        $diasVacaciones = $trabajador->diasVacacionesCalculados(2025);
        
        $this->assertEquals(28, $diasVacaciones);
    }
}
