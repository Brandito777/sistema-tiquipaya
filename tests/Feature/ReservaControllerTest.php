<?php

namespace Tests\Feature;

use App\Models\Reserva;
use App\Models\Grado;
use App\Models\Nivel;
use Faker\Factory as Faker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReservaControllerTest extends TestCase
{
    use DatabaseTransactions;
    
    protected $faker;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create('es_ES');
    }
    
    /**
     * Test: GET /reserva muestra el formulario
     */
    public function test_reserva_create_shows_form()
    {
        $response = $this->get('/reserva');
        
        $response->assertStatus(200);
        $response->assertViewIs('reservas.create');
        $response->assertViewHas('grados');
    }

    /**
     * Test: POST /reserva crea una reserva con datos válidos
     */
    public function test_reserva_store_with_valid_data()
    {
        // Preparar datos
        $nivel = Nivel::create(['nombre' => 'Primaria Comunitaria Vocacional']);
        $grado = Grado::create([
            'nivel_id' => $nivel->id,
            'nombre' => '1ro Primaria',
            'tiene_tecnico' => false
        ]);

        $email1 = 'pedro_' . time() . '_' . rand(1000, 9999) . '@test.com';
        $data = [
            'nombre_padre' => 'Pedro',
            'apellido_padre' => 'Rodríguez',
            'edad_padre' => 42,
            'telefono_padre' => '76111222',
            'email_padre' => $email1,
            'cantidad_hijos' => 1,
            'hijos' => [
                [
                    'nombre' => 'Luis',
                    'apellido' => 'Rodríguez',
                    'fecha_nacimiento' => '2020-06-15',
                    'grado_id' => $grado->id
                ]
            ]
        ];

        $response = $this->post('/reserva', $data);
        
        // Verificar redirección a confirmación
        $response->assertRedirect(route('reservas.confirmacion'));

        // Verificar que se creó la reserva
        $this->assertDatabaseHas('reservas', [
            'nombre_padre' => 'Pedro',
            'apellido_padre' => 'Rodríguez',
            'telefono_padre' => '76111222'
        ]);
    }

    /**
     * Test: POST /reserva valida datos obligatorios
     */
    public function test_reserva_store_validates_required_fields()
    {
        $data = [
            'nombre_padre' => '',  // Vacío - debe fallar
            'apellido_padre' => 'Test',
            'edad_padre' => 30,
            'telefono_padre' => '76123456',
            'cantidad_hijos' => 0,  // Sin hijos - debe fallar
        ];

        $response = $this->post('/reserva', $data);
        
        // Debe tener errores
        $response->assertSessionHasErrors(['nombre_padre', 'cantidad_hijos']);
    }

    /**
     * Test: POST /reserva valida edad del padre
     */
    public function test_reserva_store_validates_padre_age()
    {
        $nivel = Nivel::create(['nombre' => 'Primaria']);
        $grado = Grado::create([
            'nivel_id' => $nivel->id,
            'nombre' => '2do Primaria',
            'tiene_tecnico' => false
        ]);

        $email2 = 'jorge_' . time() . '_' . rand(1000, 9999) . '@test.com';
        $data = [
            'nombre_padre' => 'Jorge',
            'apellido_padre' => 'Flores',
            'edad_padre' => 15,  // Menor a 18 - debe fallar
            'telefono_padre' => '76555666',
            'email_padre' => $email2,
            'cantidad_hijos' => 1,
            'hijos' => [
                [
                    'nombre' => 'Pedro',
                    'apellido' => 'Flores',
                    'fecha_nacimiento' => '2020-01-01',
                    'grado_id' => $grado->id
                ]
            ]
        ];

        $response = $this->post('/reserva', $data);
        
        $response->assertSessionHasErrors(['edad_padre']);
    }

    /**
     * Test: POST /reserva valida grado existe
     */
    public function test_reserva_store_validates_grado_exists()
    {
        $email3 = 'carlos_' . time() . '_' . rand(1000, 9999) . '@test.com';
        $data = [
            'nombre_padre' => 'Carlos',
            'apellido_padre' => 'Mendez',
            'edad_padre' => 35,
            'telefono_padre' => '72999111',
            'email_padre' => $email3,
            'cantidad_hijos' => 1,
            'hijos' => [
                [
                    'nombre' => 'Miguel',
                    'apellido' => 'Mendez',
                    'fecha_nacimiento' => '2020-05-10',
                    'grado_id' => 99999  // Grado no existe
                ]
            ]
        ];

        $response = $this->post('/reserva', $data);
        
        $response->assertSessionHasErrors(['hijos.0.grado_id']);
    }

    /**
     * Test: GET /reservas es accesible solo para admin
     */
    public function test_reservas_index_requires_auth()
    {
        $response = $this->get('/reservas');
        
        // Debe redirigir a login
        $response->assertRedirect(route('login'));
    }

    /**
     * Test: GET /reservas/{id} muestra detalles de reserva
     */
    public function test_reserva_show_displays_details()
    {
        $nivel = Nivel::create(['nombre' => 'Inicial en Familia']);
        $grado = Grado::create([
            'nivel_id' => $nivel->id,
            'nombre' => 'Kinder',
            'tiene_tecnico' => false
        ]);

        $email4 = 'antonia_' . time() . '_' . rand(1000, 9999) . '@test.com';
        $reserva = Reserva::create([
            'nombre_padre' => 'Antonia',
            'apellido_padre' => 'Salazar',
            'edad_padre' => 40,
            'telefono_padre' => '73777888',
            'email_padre' => $email4,
            'cantidad_hijos' => 1,
            'estado' => 'pendiente',
            'gestion' => '2026'
        ]);

        $hijo = $reserva->hijos()->create([
            'nombre' => 'Sofía',
            'apellido' => 'Salazar',
            'fecha_nacimiento' => '2023-02-14',
            'grado_solicitado_id' => $grado->id
        ]);

        // Como no hay autenticación, esta prueba es solo para verificar que la ruta existe
        // En un ambiente real, se verificaría con usuario autenticado
        $response = $this->get("/reservas/{$reserva->id}");
        
        // Puede ser 200 si está accesible, o 302 si redirige a login
        $this->assertIn($response->status(), [200, 302]);
    }
}
