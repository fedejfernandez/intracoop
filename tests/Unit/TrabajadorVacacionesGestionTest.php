<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Trabajador;
use App\Models\Vacacion;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TrabajadorVacacionesGestionTest extends TestCase
{
    use RefreshDatabase;

    protected $trabajador;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear un trabajador de prueba
        $this->trabajador = Trabajador::create([
            'NombreCompleto' => 'Juan Pérez Test',
            'DNI_CUIL' => '12345678901',
            'Email' => 'juan.test@example.com',
            'FechaIngreso' => Carbon::create(2013, 1, 1), // 12 años de antigüedad = 28 días (11-20 años)
            'CCT' => '36/75',
            'Estado' => 'Activo'
        ]);
    }

    /** @test */
    public function puede_calcular_dias_utilizados_en_anio_especifico()
    {
        // Crear vacaciones aprobadas para 2025
        Vacacion::create([
            'ID_Trabajador' => $this->trabajador->ID_Trabajador,
            'Fecha_Inicio' => Carbon::create(2025, 3, 1),
            'Fecha_Fin' => Carbon::create(2025, 3, 7),
            'Dias_Solicitados' => 5,
            'Estado_Solicitud' => 'Aprobada'
        ]);

        Vacacion::create([
            'ID_Trabajador' => $this->trabajador->ID_Trabajador,
            'Fecha_Inicio' => Carbon::create(2025, 8, 1),
            'Fecha_Fin' => Carbon::create(2025, 8, 10),
            'Dias_Solicitados' => 7,
            'Estado_Solicitud' => 'Aprobada'
        ]);

        // Crear una vacación rechazada (no debe contar)
        Vacacion::create([
            'ID_Trabajador' => $this->trabajador->ID_Trabajador,
            'Fecha_Inicio' => Carbon::create(2025, 12, 1),
            'Fecha_Fin' => Carbon::create(2025, 12, 5),
            'Dias_Solicitados' => 3,
            'Estado_Solicitud' => 'Rechazada'
        ]);

        $diasUtilizados = $this->trabajador->diasVacacionesUtilizados(2025);
        
        $this->assertEquals(12, $diasUtilizados); // Solo las aprobadas: 5 + 7 = 12
    }

    /** @test */
    public function puede_calcular_dias_pendientes_de_aprobacion()
    {
        // Crear vacaciones pendientes
        Vacacion::create([
            'ID_Trabajador' => $this->trabajador->ID_Trabajador,
            'Fecha_Inicio' => Carbon::create(2025, 6, 1),
            'Fecha_Fin' => Carbon::create(2025, 6, 5),
            'Dias_Solicitados' => 4,
            'Estado_Solicitud' => 'Pendiente'
        ]);

        Vacacion::create([
            'ID_Trabajador' => $this->trabajador->ID_Trabajador,
            'Fecha_Inicio' => Carbon::create(2025, 9, 1),
            'Fecha_Fin' => Carbon::create(2025, 9, 3),
            'Dias_Solicitados' => 2,
            'Estado_Solicitud' => 'Pendiente'
        ]);

        $diasPendientes = $this->trabajador->diasVacacionesPendientes(2025);
        
        $this->assertEquals(6, $diasPendientes); // 4 + 2 = 6
    }

    /** @test */
    public function puede_calcular_dias_disponibles_correctamente()
    {
        // El trabajador tiene 28 días asignados (10 años de antigüedad)
        // Crear 10 días utilizados
        Vacacion::create([
            'ID_Trabajador' => $this->trabajador->ID_Trabajador,
            'Fecha_Inicio' => Carbon::create(2025, 3, 1),
            'Fecha_Fin' => Carbon::create(2025, 3, 14),
            'Dias_Solicitados' => 10,
            'Estado_Solicitud' => 'Aprobada'
        ]);

        $diasDisponibles = $this->trabajador->diasVacacionesDisponibles(2025);
        
        $this->assertEquals(18, $diasDisponibles); // 28 - 10 = 18
    }

    /** @test */
    public function puede_calcular_dias_libres_considerando_pendientes()
    {
        // Crear vacaciones aprobadas
        Vacacion::create([
            'ID_Trabajador' => $this->trabajador->ID_Trabajador,
            'Fecha_Inicio' => Carbon::create(2025, 3, 1),
            'Fecha_Fin' => Carbon::create(2025, 3, 7),
            'Dias_Solicitados' => 5,
            'Estado_Solicitud' => 'Aprobada'
        ]);

        // Crear vacaciones pendientes
        Vacacion::create([
            'ID_Trabajador' => $this->trabajador->ID_Trabajador,
            'Fecha_Inicio' => Carbon::create(2025, 6, 1),
            'Fecha_Fin' => Carbon::create(2025, 6, 10),
            'Dias_Solicitados' => 7,
            'Estado_Solicitud' => 'Pendiente'
        ]);

        $diasLibres = $this->trabajador->diasVacacionesLibres(2025);
        
        // 28 asignados - 5 utilizados - 7 pendientes = 16 libres
        $this->assertEquals(16, $diasLibres);
    }

    /** @test */
    public function genera_resumen_completo_de_vacaciones()
    {
        // Crear diferentes tipos de vacaciones
        Vacacion::create([
            'ID_Trabajador' => $this->trabajador->ID_Trabajador,
            'Fecha_Inicio' => Carbon::create(2025, 3, 1),
            'Fecha_Fin' => Carbon::create(2025, 3, 7),
            'Dias_Solicitados' => 5,
            'Estado_Solicitud' => 'Aprobada'
        ]);

        Vacacion::create([
            'ID_Trabajador' => $this->trabajador->ID_Trabajador,
            'Fecha_Inicio' => Carbon::create(2025, 6, 1),
            'Fecha_Fin' => Carbon::create(2025, 6, 5),
            'Dias_Solicitados' => 3,
            'Estado_Solicitud' => 'Pendiente'
        ]);

        $resumen = $this->trabajador->resumenVacaciones(2025);

        $this->assertArrayHasKey('anio', $resumen);
        $this->assertArrayHasKey('dias_asignados', $resumen);
        $this->assertArrayHasKey('dias_utilizados', $resumen);
        $this->assertArrayHasKey('dias_pendientes', $resumen);
        $this->assertArrayHasKey('dias_disponibles', $resumen);
        $this->assertArrayHasKey('dias_libres', $resumen);
        $this->assertArrayHasKey('porcentaje_utilizado', $resumen);

        $this->assertEquals(2025, $resumen['anio']);
        $this->assertEquals(28, $resumen['dias_asignados']);
        $this->assertEquals(5, $resumen['dias_utilizados']);
        $this->assertEquals(3, $resumen['dias_pendientes']);
        $this->assertEquals(23, $resumen['dias_disponibles']); // 28 - 5
        $this->assertEquals(20, $resumen['dias_libres']); // 28 - 5 - 3
    }

    /** @test */
    public function calcula_porcentaje_utilizado_correctamente()
    {
        // Utilizar 14 días de 28 asignados = 50%
        Vacacion::create([
            'ID_Trabajador' => $this->trabajador->ID_Trabajador,
            'Fecha_Inicio' => Carbon::create(2025, 3, 1),
            'Fecha_Fin' => Carbon::create(2025, 3, 20),
            'Dias_Solicitados' => 14,
            'Estado_Solicitud' => 'Aprobada'
        ]);

        $porcentaje = $this->trabajador->calcularPorcentajeUtilizado(2025);
        
        $this->assertEquals(50.0, $porcentaje);
    }

    /** @test */
    public function valida_si_puede_solicitar_vacaciones()
    {
        // Crear vacaciones ya utilizadas
        Vacacion::create([
            'ID_Trabajador' => $this->trabajador->ID_Trabajador,
            'Fecha_Inicio' => Carbon::create(2025, 3, 1),
            'Fecha_Fin' => Carbon::create(2025, 3, 15),
            'Dias_Solicitados' => 10,
            'Estado_Solicitud' => 'Aprobada'
        ]);

        // Crear vacaciones pendientes
        Vacacion::create([
            'ID_Trabajador' => $this->trabajador->ID_Trabajador,
            'Fecha_Inicio' => Carbon::create(2025, 6, 1),
            'Fecha_Fin' => Carbon::create(2025, 6, 10),
            'Dias_Solicitados' => 8,
            'Estado_Solicitud' => 'Pendiente'
        ]);

        // Puede solicitar 5 días (quedan 10 libres: 28 - 10 - 8 = 10)
        $resultado = $this->trabajador->puedesolicitarVacaciones(5, 2025);
        $this->assertTrue($resultado['puede_solicitar']);

        // No puede solicitar 15 días (solo quedan 10 libres)
        $resultado = $this->trabajador->puedesolicitarVacaciones(15, 2025);
        $this->assertFalse($resultado['puede_solicitar']);
        $this->assertStringContainsString('Solo tienes 10 días disponibles', $resultado['mensaje']);
    }

    /** @test */
    public function no_permite_solicitar_cero_o_dias_negativos()
    {
        $resultado = $this->trabajador->puedesolicitarVacaciones(0, 2025);
        $this->assertFalse($resultado['puede_solicitar']);
        $this->assertEquals('La cantidad de días debe ser mayor a 0.', $resultado['mensaje']);

        $resultado = $this->trabajador->puedesolicitarVacaciones(-5, 2025);
        $this->assertFalse($resultado['puede_solicitar']);
        $this->assertEquals('La cantidad de días debe ser mayor a 0.', $resultado['mensaje']);
    }

    /** @test */
    public function obtiene_vacaciones_del_anio_ordenadas()
    {
        // Crear vacaciones en diferentes fechas del mismo año
        Vacacion::create([
            'ID_Trabajador' => $this->trabajador->ID_Trabajador,
            'Fecha_Inicio' => Carbon::create(2025, 8, 1),
            'Fecha_Fin' => Carbon::create(2025, 8, 5),
            'Dias_Solicitados' => 3,
            'Estado_Solicitud' => 'Aprobada'
        ]);

        Vacacion::create([
            'ID_Trabajador' => $this->trabajador->ID_Trabajador,
            'Fecha_Inicio' => Carbon::create(2025, 3, 1),
            'Fecha_Fin' => Carbon::create(2025, 3, 5),
            'Dias_Solicitados' => 3,
            'Estado_Solicitud' => 'Pendiente'
        ]);

        Vacacion::create([
            'ID_Trabajador' => $this->trabajador->ID_Trabajador,
            'Fecha_Inicio' => Carbon::create(2025, 12, 1),
            'Fecha_Fin' => Carbon::create(2025, 12, 5),
            'Dias_Solicitados' => 3,
            'Estado_Solicitud' => 'Aprobada'
        ]);

        $vacacionesDelAnio = $this->trabajador->vacacionesDelAnio(2025);

        $this->assertCount(3, $vacacionesDelAnio);
        
        // Verificar que están ordenadas por fecha de inicio
        $fechas = $vacacionesDelAnio->pluck('Fecha_Inicio')->toArray();
        $this->assertEquals('2025-03-01', $fechas[0]->format('Y-m-d'));
        $this->assertEquals('2025-08-01', $fechas[1]->format('Y-m-d'));
        $this->assertEquals('2025-12-01', $fechas[2]->format('Y-m-d'));
    }

    /** @test */
    public function obtiene_proximas_vacaciones_aprobadas()
    {
        $fechaFutura1 = Carbon::now()->addDays(10);
        $fechaFutura2 = Carbon::now()->addDays(30);
        $fechaPasada = Carbon::now()->subDays(10);

        // Crear vacaciones futuras aprobadas
        Vacacion::create([
            'ID_Trabajador' => $this->trabajador->ID_Trabajador,
            'Fecha_Inicio' => $fechaFutura2,
            'Fecha_Fin' => $fechaFutura2->copy()->addDays(5),
            'Dias_Solicitados' => 4,
            'Estado_Solicitud' => 'Aprobada'
        ]);

        Vacacion::create([
            'ID_Trabajador' => $this->trabajador->ID_Trabajador,
            'Fecha_Inicio' => $fechaFutura1,
            'Fecha_Fin' => $fechaFutura1->copy()->addDays(3),
            'Dias_Solicitados' => 3,
            'Estado_Solicitud' => 'Aprobada'
        ]);

        // Crear vacaciones pasadas (no deben aparecer)
        Vacacion::create([
            'ID_Trabajador' => $this->trabajador->ID_Trabajador,
            'Fecha_Inicio' => $fechaPasada,
            'Fecha_Fin' => $fechaPasada->copy()->addDays(3),
            'Dias_Solicitados' => 3,
            'Estado_Solicitud' => 'Aprobada'
        ]);

        // Crear vacaciones futuras pendientes (no deben aparecer)
        Vacacion::create([
            'ID_Trabajador' => $this->trabajador->ID_Trabajador,
            'Fecha_Inicio' => $fechaFutura1->copy()->addDays(5),
            'Fecha_Fin' => $fechaFutura1->copy()->addDays(8),
            'Dias_Solicitados' => 2,
            'Estado_Solicitud' => 'Pendiente'
        ]);

        $proximasVacaciones = $this->trabajador->proximasVacaciones();

        $this->assertCount(2, $proximasVacaciones);
        
        // Verificar que están ordenadas por fecha (la más próxima primero)
        $this->assertEquals(
            $fechaFutura1->format('Y-m-d'),
            $proximasVacaciones->first()->Fecha_Inicio->format('Y-m-d')
        );
    }
} 