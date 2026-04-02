<?php

namespace Tests\Unit;

use App\Models\Reserva;
use App\Models\ReservaHijo;
use App\Models\Grado;
use App\Models\Nivel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReservaModelTest extends TestCase
{
    use DatabaseTransactions;
    /**
     * Test: Reserva tiene muchos ReservaHijo
     */
    public function test_reserva_has_many_hijos()
    {
        // Crear Nivel y Grado
        $nivel = Nivel::create(['nombre' => 'Primaria Comunitaria Vocacional']);
        $grado = Grado::create([
            'nivel_id' => $nivel->id,
            'nombre' => '1ro Primaria',
            'tiene_tecnico' => false
        ]);

        // Crear Reserva
        $reserva = Reserva::create([
            'nombre_padre' => 'Juan',
            'apellido_padre' => 'Pérez',
            'edad_padre' => 45,
            'telefono_padre' => '76123456',
            'email_padre' => 'juan@test.com',
            'cantidad_hijos' => 2,
            'estado' => 'pendiente',
            'gestion' => '2026'
        ]);

        // Crear 2 hijos en esa reserva
        $hijo1 = ReservaHijo::create([
            'reserva_id' => $reserva->id,
            'nombre' => 'Carlos',
            'apellido' => 'Pérez',
            'fecha_nacimiento' => '2020-05-15',
            'grado_solicitado_id' => $grado->id
        ]);

        $hijo2 = ReservaHijo::create([
            'reserva_id' => $reserva->id,
            'nombre' => 'María',
            'apellido' => 'Pérez',
            'fecha_nacimiento' => '2018-08-22',
            'grado_solicitado_id' => $grado->id
        ]);

        // Verificar relación
        $this->assertCount(2, $reserva->hijos);
        $this->assertEquals('Carlos', $reserva->hijos[0]->nombre);
        $this->assertEquals('María', $reserva->hijos[1]->nombre);
    }

    /**
     * Test: ReservaHijo pertenece a Reserva
     */
    public function test_reserva_hijo_belongs_to_reserva()
    {
        $nivel = Nivel::create(['nombre' => 'Inicial']);
        $grado = Grado::create([
            'nivel_id' => $nivel->id,
            'nombre' => 'Kinder 1',
            'tiene_tecnico' => false
        ]);

        $reserva = Reserva::create([
            'nombre_padre' => 'María',
            'apellido_padre' => 'López',
            'edad_padre' => 38,
            'telefono_padre' => '77654321',
            'email_padre' => 'maria@test.com',
            'cantidad_hijos' => 1,
            'estado' => 'confirmada',
            'gestion' => '2026'
        ]);

        $hijo = ReservaHijo::create([
            'reserva_id' => $reserva->id,
            'nombre' => 'Ana',
            'apellido' => 'López',
            'fecha_nacimiento' => '2023-01-10',
            'grado_solicitado_id' => $grado->id
        ]);

        // Verificar relación inversa
        $this->assertEquals($reserva->id, $hijo->reserva->id);
        $this->assertEquals('María', $hijo->reserva->nombre_padre);
    }

    /**
     * Test: ReservaHijo tiene edad calculada correctamente
     */
    public function test_reserva_hijo_edad_calculation()
    {
        $nivel = Nivel::create(['nombre' => 'Secundaria']);
        $grado = Grado::create([
            'nivel_id' => $nivel->id,
            'nombre' => '1ro Secundaria',
            'tiene_tecnico' => false
        ]);

        $reserva = Reserva::create([
            'nombre_padre' => 'Roberto',
            'apellido_padre' => 'García',
            'edad_padre' => 50,
            'telefono_padre' => '75999888',
            'email_padre' => 'roberto@test.com',
            'cantidad_hijos' => 1,
            'estado' => 'pendiente',
            'gestion' => '2026'
        ]);

        $hijo = ReservaHijo::create([
            'reserva_id' => $reserva->id,
            'nombre' => 'Diego',
            'apellido' => 'García',
            'fecha_nacimiento' => '2011-03-20',
            'grado_solicitado_id' => $grado->id
        ]);

        // Verificar edad calculada
        $edad = $hijo->edad;
        $this->assertGreaterThan(14, $edad); // Debe ser ~15 años
        $this->assertLessThan(20, $edad);
    }

    /**
     * Test: Reserva tiene fillable correcto
     */
    public function test_reserva_fillable()
    {
        $reserva = new Reserva();
        
        $this->assertContains('nombre_padre', $reserva->getFillable());
        $this->assertContains('apellido_padre', $reserva->getFillable());
        $this->assertContains('edad_padre', $reserva->getFillable());
        $this->assertContains('telefono_padre', $reserva->getFillable());
        $this->assertContains('email_padre', $reserva->getFillable());
        $this->assertContains('cantidad_hijos', $reserva->getFillable());
        $this->assertContains('estado', $reserva->getFillable());
    }

    /**
     * Test: ReservaHijo tiene fillable correcto
     */
    public function test_reserva_hijo_fillable()
    {
        $hijo = new ReservaHijo();
        
        $this->assertContains('reserva_id', $hijo->getFillable());
        $this->assertContains('nombre', $hijo->getFillable());
        $this->assertContains('apellido', $hijo->getFillable());
        $this->assertContains('fecha_nacimiento', $hijo->getFillable());
        $this->assertContains('grado_solicitado_id', $hijo->getFillable());
    }
}
