<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Padre;
use App\Models\Estudiante;
use App\Models\Inscripcion;
use App\Models\Grado;
use App\Models\Nivel;
use Faker\Factory as Faker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EstudianteControllerTest extends TestCase
{
    use DatabaseTransactions;
    
    protected $faker;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create('es_ES');
    }

    /**
     * Test: GET /estudiantes lista estudiantes
     */
    public function test_estudiantes_index_shows_list()
    {
        $adminEmail = 'admin_est_' . time() . '_' . rand(1000, 9999) . '@test.com';
        $admin = User::create([
            'name' => 'Admin Estudiantes',
            'email' => $adminEmail,
            'password' => bcrypt('password'),
            'role' => 'admin',
            'active' => true
        ]);

        $response = $this->actingAs($admin)->get('/estudiantes');

        $response->assertStatus(200);
        $response->assertViewIs('estudiantes.index');
    }

    /**
     * Test: GET /estudiantes filtra por nivel
     */
    public function test_estudiantes_filter_by_nivel()
    {
        $adminEmail = 'admin_filter_' . time() . '_' . rand(1000, 9999) . '@test.com';
        $admin = User::create([
            'name' => 'Admin Filter',
            'email' => $adminEmail,
            'password' => bcrypt('password'),
            'role' => 'admin',
            'active' => true
        ]);

        // Crear estructura
        $nivel1 = Nivel::create(['nombre' => 'Primaria']);
        $nivel2 = Nivel::create(['nombre' => 'Secundaria']);
        
        $grado1 = Grado::create([
            'nivel_id' => $nivel1->id,
            'nombre' => '1ro Primaria',
            'tiene_tecnico' => false
        ]);
        
        $grado2 = Grado::create([
            'nivel_id' => $nivel2->id,
            'nombre' => '1ro Secundaria',
            'tiene_tecnico' => false
        ]);

        $padreFilterEmail = 'padre_filter_' . time() . '_' . rand(1000, 9999) . '@test.com';
        $user = User::create([
            'name' => 'Padre Filtro',
            'email' => $padreFilterEmail,
            'password' => bcrypt('password'),
            'role' => 'padre',
            'active' => true
        ]);

        $padre = Padre::create([
            'user_id' => $user->id,
            'nombre' => 'Padre',
            'apellido' => 'Filtro',
            'ci' => '2111111',
            'edad' => 40,
            'telefono' => '76999999',
            'email' => $padreFilterEmail
        ]);

        // Crear estudiantes
        $est1 = Estudiante::create([
            'padre_id' => $padre->id,
            'nombre' => 'Est1',
            'apellido' => 'Primaria',
            'ci' => '4444444',
            'genero' => 'M',
            'fecha_nacimiento' => '2015-01-01',
            'tipo' => 'nuevo'
        ]);

        $est2 = Estudiante::create([
            'padre_id' => $padre->id,
            'nombre' => 'Est2',
            'apellido' => 'Secundaria',
            'ci' => '5555555',
            'genero' => 'M',
            'fecha_nacimiento' => '2010-01-01',
            'tipo' => 'antiguo'
        ]);

        // Crear inscripciones
        Inscripcion::create([
            'estudiante_id' => $est1->id,
            'grado_id' => $grado1->id,
            'estado' => 'aprobada',
            'gestion' => '2026',
            'fecha_inscripcion' => '2026-01-10'
        ]);

        Inscripcion::create([
            'estudiante_id' => $est2->id,
            'grado_id' => $grado2->id,
            'estado' => 'aprobada',
            'gestion' => '2026',
            'fecha_inscripcion' => '2026-01-10'
        ]);

        // Filtrar por nivel
        $response = $this->actingAs($admin)->get("/estudiantes?nivel_id={$nivel1->id}");

        $response->assertStatus(200);
    }

    /**
     * Test: GET /estudiantes busca por nombre
     */
    public function test_estudiantes_search_by_name()
    {
        $adminEmail = 'admin_search_' . time() . '_' . rand(1000, 9999) . '@test.com';
        $admin = User::create([
            'name' => 'Admin Search',
            'email' => $adminEmail,
            'password' => bcrypt('password'),
            'role' => 'admin',
            'active' => true
        ]);

        $padreSearchEmail = 'padre_search_' . time() . '_' . rand(1000, 9999) . '@test.com';
        $user = User::create([
            'name' => 'Padre Search',
            'email' => $padreSearchEmail,
            'password' => bcrypt('password'),
            'role' => 'padre',
            'active' => true
        ]);

        $padre = Padre::create([
            'user_id' => $user->id,
            'nombre' => 'Padre',
            'apellido' => 'Search',
            'ci' => '2111112',
            'edad' => 35,
            'telefono' => '76111111',
            'email' => $padreSearchEmail
        ]);

        Estudiante::create([
            'padre_id' => $padre->id,
            'nombre' => 'Alejandro',
            'apellido' => 'Search',
            'ci' => '6666666',
            'genero' => 'M',
            'fecha_nacimiento' => '2012-05-15',
            'tipo' => 'nuevo'
        ]);

        // Buscar por nombre
        $response = $this->actingAs($admin)->get('/estudiantes?buscar=Alejandro');

        $response->assertStatus(200);
    }

    /**
     * Test: GET /estudiantes/{id} muestra detalles
     */
    public function test_estudiantes_show_details()
    {
        $adminEmail = 'admin_show_' . time() . '_' . rand(1000, 9999) . '@test.com';
        $admin = User::create([
            'name' => 'Admin Show',
            'email' => $adminEmail,
            'password' => bcrypt('password'),
            'role' => 'admin',
            'active' => true
        ]);

        $padreShowEmail = 'padre_show_' . time() . '_' . rand(1000, 9999) . '@test.com';
        $user = User::create([
            'name' => 'Padre Show',
            'email' => $padreShowEmail,
            'password' => bcrypt('password'),
            'role' => 'padre',
            'active' => true
        ]);

        $padre = Padre::create([
            'user_id' => $user->id,
            'nombre' => 'Padre',
            'apellido' => 'Show',
            'ci' => '2111113',
            'edad' => 45,
            'telefono' => '76222222',
            'email' => $padreShowEmail
        ]);

        $nivel = Nivel::create(['nombre' => 'Primaria']);
        $grado = Grado::create([
            'nivel_id' => $nivel->id,
            'nombre' => '5to Primaria',
            'tiene_tecnico' => false
        ]);

        $estudiante = Estudiante::create([
            'padre_id' => $padre->id,
            'nombre' => 'Estudiante',
            'apellido' => 'Show',
            'ci' => '7777777',
            'genero' => 'F',
            'fecha_nacimiento' => '2014-02-28',
            'tipo' => 'nuevo'
        ]);

        Inscripcion::create([
            'estudiante_id' => $estudiante->id,
            'grado_id' => $grado->id,
            'estado' => 'aprobada',
            'gestion' => '2026',
            'fecha_inscripcion' => '2026-02-01'
        ]);

        // Ver detalles
        $response = $this->actingAs($admin)->get("/estudiantes/{$estudiante->id}");

        $response->assertStatus(200);
        $response->assertViewIs('estudiantes.show');
    }

    /**
     * Test: GET /estudiantes/create muestra formulario
     */
    public function test_estudiantes_create_form()
    {
        $adminEmail = 'admin_create_' . time() . '_' . rand(1000, 9999) . '@test.com';
        $admin = User::create([
            'name' => 'Admin Create',
            'email' => $adminEmail,
            'password' => bcrypt('password'),
            'role' => 'admin',
            'active' => true
        ]);

        $response = $this->actingAs($admin)->get('/estudiantes/create');

        $response->assertStatus(200);
        $response->assertViewIs('estudiantes.create');
        $response->assertViewHas('padres');
    }

    /**
     * Test: POST /estudiantes crea estudiante válido
     */
    public function test_estudiantes_store_creates_successfully()
    {
        $adminEmail = 'admin_store_' . time() . '_' . rand(1000, 9999) . '@test.com';
        $admin = User::create([
            'name' => 'Admin Store',
            'email' => $adminEmail,
            'password' => bcrypt('password'),
            'role' => 'admin',
            'active' => true
        ]);

        $padreStoreEmail = 'padre_store_' . time() . '_' . rand(1000, 9999) . '@test.com';
        $user = User::create([
            'name' => 'Padre Store',
            'email' => $padreStoreEmail,
            'password' => bcrypt('password'),
            'role' => 'padre',
            'active' => true
        ]);

        $padre = Padre::create([
            'user_id' => $user->id,
            'nombre' => 'Padre',
            'apellido' => 'Store',
            'ci' => '2111114',
            'edad' => 42,
            'telefono' => '76333333',
            'email' => $padreStoreEmail
        ]);

        $data = [
            'padre_id' => $padre->id,
            'nombre' => 'Nuevo',
            'apellido' => 'Estudiante',
            'ci' => '8888888',
            'genero' => 'M',
            'fecha_nacimiento' => '2016-08-10',
            'tipo' => 'nuevo'
        ];

        $response = $this->actingAs($admin)->post('/estudiantes', $data);

        $response->assertRedirect(route('estudiantes.index'));

        $this->assertDatabaseHas('estudiantes', [
            'nombre' => 'Nuevo',
            'apellido' => 'Estudiante',
            'ci' => '8888888'
        ]);
    }

    /**
     * Test: POST /estudiantes valida datos obligatorios
     */
    public function test_estudiantes_store_validates_required()
    {
        $adminEmail = 'admin_validate_' . time() . '_' . rand(1000, 9999) . '@test.com';
        $admin = User::create([
            'name' => 'Admin Validate',
            'email' => $adminEmail,
            'password' => bcrypt('password'),
            'role' => 'admin',
            'active' => true
        ]);

        $data = [
            'padre_id' => '',  // Vacío
            'nombre' => '',    // Vacío
            'apellido' => 'Test',
            'ci' => '1111111',
            'genero' => 'M',
            'fecha_nacimiento' => '2016-01-01',
            'tipo' => 'nuevo'
        ];

        $response = $this->actingAs($admin)->post('/estudiantes', $data);

        $response->assertSessionHasErrors(['padre_id', 'nombre']);
    }
}
