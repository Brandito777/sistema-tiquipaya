<?php

namespace Tests\Unit;

use App\Models\Estudiante;
use App\Models\Padre;
use App\Models\User;
use App\Models\Inscripcion;
use App\Models\Grado;
use App\Models\Nivel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Faker\Factory as Faker;

class EstudianteModelTest extends TestCase
{
    use DatabaseTransactions;

    private $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create('es_ES');
    }
    /**
     * Test: Estudiante pertenece a Padre
     */
    public function test_estudiante_belongs_to_padre()
    {
        $uniqueEmail = 'juan_' . time() . '@test.com';
        $user = User::create([
            'name' => 'Juan García',
            'email' => $uniqueEmail,
            'password' => bcrypt('password'),
            'role' => 'padre',
            'active' => true
        ]);

        $padre = Padre::create([
            'user_id' => $user->id,
            'nombre' => 'Juan',
            'apellido' => 'García',
            'ci' => '1111111',
            'edad' => 45,
            'telefono' => '76123456',
            'email' => $uniqueEmail
        ]);

        $estudiante = Estudiante::create([
            'padre_id' => $padre->id,
            'nombre' => 'Roberto',
            'apellido' => 'García',
            'ci' => '1234567',
            'genero' => 'M',
            'fecha_nacimiento' => '2012-03-15',
            'tipo' => 'nuevo'
        ]);

        // Verificar relación
        $this->assertEquals($padre->id, $estudiante->padre->id);
        $this->assertEquals('Juan', $estudiante->padre->nombre);
    }

    /**
     * Test: Estudiante tiene muchas Inscripciones
     */
    public function test_estudiante_has_many_inscripciones()
    {
        $uniqueEmail = 'maria_' . time() . '_' . rand(1000, 9999) . '@test.com';
        $user = User::create([
            'name' => 'María López',
            'email' => $uniqueEmail,
            'password' => bcrypt('password'),
            'role' => 'padre',
            'active' => true
        ]);

        $padre = Padre::create([
            'user_id' => $user->id,
            'nombre' => 'María',
            'apellido' => 'López',
            'ci' => '1111112',
            'edad' => 38,
            'telefono' => '77654321',
            'email' => $uniqueEmail
        ]);

        $estudiante = Estudiante::create([
            'padre_id' => $padre->id,
            'nombre' => 'Carlos',
            'apellido' => 'López',
            'ci' => '9876543',
            'genero' => 'M',
            'fecha_nacimiento' => '2010-06-20',
            'tipo' => 'antiguo'
        ]);

        $nivel = Nivel::create(['nombre' => 'Primaria']);
        $grado = Grado::create([
            'nivel_id' => $nivel->id,
            'nombre' => '4to Primaria',
            'tiene_tecnico' => false
        ]);

        // Crear 2 inscripciones
        Inscripcion::create([
            'estudiante_id' => $estudiante->id,
            'grado_id' => $grado->id,
            'estado' => 'aprobada',
            'gestion' => '2025',
            'fecha_inscripcion' => '2025-01-15'
        ]);

        Inscripcion::create([
            'estudiante_id' => $estudiante->id,
            'grado_id' => $grado->id,
            'estado' => 'pendiente',
            'gestion' => '2026',
            'fecha_inscripcion' => '2026-01-15'
        ]);

        // Verificar relación
        $this->assertCount(2, $estudiante->inscripciones);
    }

    /**
     * Test: Estudiante calcula edad correctamente
     */
    public function test_estudiante_edad_calculation()
    {
        $uniqueEmail = 'pedro_' . time() . '_' . rand(1000, 9999) . '@test.com';
        $user = User::create([
            'name' => 'Pedro Sánchez',
            'email' => $uniqueEmail,
            'password' => bcrypt('password'),
            'role' => 'padre',
            'active' => true
        ]);

        $padre = Padre::create([
            'user_id' => $user->id,
            'nombre' => 'Pedro',
            'apellido' => 'Sánchez',
            'ci' => '1111113',
            'edad' => 50,
            'telefono' => '75222333',
            'email' => $uniqueEmail
        ]);

        $estudiante = Estudiante::create([
            'padre_id' => $padre->id,
            'nombre' => 'Ana',
            'apellido' => 'Sánchez',
            'ci' => '5555555',
            'genero' => 'F',
            'fecha_nacimiento' => '2012-11-25',
            'tipo' => 'nuevo'
        ]);

        // Verificar edad
        $edad = $estudiante->edad;
        $this->assertGreaterThan(12, $edad);
        $this->assertLessThan(18, $edad);
    }

    /**
     * Test: Inscripción pertenece a Estudiante
     */
    public function test_inscripcion_belongs_to_estudiante()
    {
        $uniqueEmail = 'lopez_' . time() . '_' . rand(1000, 9999) . '@test.com';
        $user = User::create([
            'name' => 'López Familia',
            'email' => $uniqueEmail,
            'password' => bcrypt('password'),
            'role' => 'padre',
            'active' => true
        ]);

        $padre = Padre::create([
            'user_id' => $user->id,
            'nombre' => 'Fernando',
            'apellido' => 'López',
            'ci' => '1111114',
            'edad' => 48,
            'telefono' => '76888999',
            'email' => $uniqueEmail
        ]);

        $estudiante = Estudiante::create([
            'padre_id' => $padre->id,
            'nombre' => 'Felipe',
            'apellido' => 'López',
            'ci' => '2222222',
            'genero' => 'M',
            'fecha_nacimiento' => '2013-01-10',
            'tipo' => 'nuevo'
        ]);

        $nivel = Nivel::create(['nombre' => 'Secundaria']);
        $grado = Grado::create([
            'nivel_id' => $nivel->id,
            'nombre' => '2do Secundaria',
            'tiene_tecnico' => false
        ]);

        $inscripcion = Inscripcion::create([
            'estudiante_id' => $estudiante->id,
            'grado_id' => $grado->id,
            'estado' => 'aprobada',
            'gestion' => '2026',
            'fecha_inscripcion' => '2026-01-20'
        ]);

        // Verificar relación inversa
        $this->assertEquals($estudiante->id, $inscripcion->estudiante->id);
        $this->assertEquals('Felipe', $inscripcion->estudiante->nombre);
    }

    /**
     * Test: Inscripción pertenece a Grado
     */
    public function test_inscripcion_belongs_to_grado()
    {
        $uniqueEmail = 'cliente_' . time() . '_' . rand(1000, 9999) . '@test.com';
        $user = User::create([
            'name' => 'Cliente Test',
            'email' => $uniqueEmail,
            'password' => bcrypt('password'),
            'role' => 'padre',
            'active' => true
        ]);

        $padre = Padre::create([
            'user_id' => $user->id,
            'nombre' => 'Cliente',
            'apellido' => 'Test',
            'ci' => '1111115',
            'edad' => 35,
            'telefono' => '79111222',
            'email' => $uniqueEmail
        ]);

        $estudiante = Estudiante::create([
            'padre_id' => $padre->id,
            'nombre' => 'Estudiante',
            'apellido' => 'Test',
            'ci' => '3333333',
            'genero' => 'M',
            'fecha_nacimiento' => '2014-07-20',
            'tipo' => 'nuevo'
        ]);

        $nivel = Nivel::create(['nombre' => 'Inicial']);
        $grado = Grado::create([
            'nivel_id' => $nivel->id,
            'nombre' => 'Kinder 2',
            'tiene_tecnico' => false
        ]);

        $inscripcion = Inscripcion::create([
            'estudiante_id' => $estudiante->id,
            'grado_id' => $grado->id,
            'estado' => 'pendiente',
            'gestion' => '2026',
            'fecha_inscripcion' => '2026-03-01'
        ]);

        // Verificar relación
        $this->assertEquals($grado->id, $inscripcion->grado->id);
        $this->assertEquals('Kinder 2', $inscripcion->grado->nombre);
    }
}
