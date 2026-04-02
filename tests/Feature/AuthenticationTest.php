<?php

namespace Tests\Feature;

use App\Models\User;
use Faker\Factory as Faker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthenticationTest extends TestCase
{
    use DatabaseTransactions;
    
    protected $faker;
    
    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Faker::create('es_ES');
    }
    
    /**
     * Test: GET /login muestra el formulario de login
     */
    public function test_login_page_loads()
    {
        $response = $this->get('/login');
        
        $response->assertStatus(200);
    }

    /**
     * Test: POST /login autentica usuario válido
     */
    public function test_user_can_login_with_valid_credentials()
    {
        $adminEmail = 'admin_' . time() . '_' . rand(1000, 9999) . '@test.com';
        $user = User::create([
            'name' => 'Admin Test',
            'email' => $adminEmail,
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'active' => true
        ]);

        $response = $this->post('/login', [
            'email' => $adminEmail,
            'password' => 'password123'
        ]);

        // Debe redirigir al dashboard
        $response->assertRedirect(route('dashboard'));
        
        // Usuario debe estar autenticado
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test: POST /login rechaza credenciales inválidas
     */
    public function test_user_cannot_login_with_invalid_password()
    {
        $testEmail = 'testuser_' . time() . '_' . rand(1000, 9999) . '@test.com';
        User::create([
            'name' => 'Test User',
            'email' => $testEmail,
            'password' => bcrypt('correct_password'),
            'role' => 'padre',
            'active' => true
        ]);

        $response = $this->post('/login', [
            'email' => $testEmail,
            'password' => 'wrong_password'
        ]);

        // Debe redirigir con error
        $response->assertRedirect(route('login'));
        
        // No debe estar autenticado
        $this->assertGuest();
    }

    /**
     * Test: Usuario inactivo no puede hacer login
     */
    public function test_inactive_user_cannot_login()
    {
        $inactiveEmail = 'inactive_' . time() . '_' . rand(1000, 9999) . '@test.com';
        User::create([
            'name' => 'Inactive User',
            'email' => $inactiveEmail,
            'password' => bcrypt('password123'),
            'role' => 'padre',
            'active' => false  // Inactivo
        ]);

        $response = $this->post('/login', [
            'email' => $inactiveEmail,
            'password' => 'password123'
        ]);

        // No debe autenticarse
        $this->assertGuest();
    }

    /**
     * Test: POST /logout cierra la sesión
     */
    public function test_user_can_logout()
    {
        $logoutEmail = 'logout_' . time() . '_' . rand(1000, 9999) . '@test.com';
        $user = User::create([
            'name' => 'Logout Test',
            'email' => $logoutEmail,
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'active' => true
        ]);

        // Autenticarse
        $this->actingAs($user);
        
        // Verificar que está autenticado
        $this->assertAuthenticatedAs($user);

        // Logout
        $response = $this->post('/logout');

        // Debe redirigir a login
        $response->assertRedirect(route('login'));

        // No debe estar autenticado
        $this->assertGuest();
    }

    /**
     * Test: Rutas protegidas requieren autenticación
     */
    public function test_protected_routes_require_authentication()
    {
        // GET /estudiantes
        $response = $this->get('/estudiantes');
        $response->assertRedirect(route('login'));

        // GET /inscripciones
        $response = $this->get('/inscripciones');
        $response->assertRedirect(route('login'));

        // GET /reservas
        $response = $this->get('/reservas');
        $response->assertRedirect(route('login'));

        // GET /padres
        $response = $this->get('/padres');
        $response->assertRedirect(route('login'));
    }

    /**
     * Test: Usuario autenticado puede acceder a dashboard
     */
    public function test_authenticated_user_can_access_dashboard()
    {
        $dashEmail = 'dashboard_' . time() . '_' . rand(1000, 9999) . '@test.com';
        $user = User::create([
            'name' => 'Dashboard User',
            'email' => $dashEmail,
            'password' => bcrypt('password123'),
            'role' => 'admin',
            'active' => true
        ]);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertStatus(200);
    }

    /**
     * Test: Diferentes roles acceden a diferentes dashboards
     */
    public function test_role_based_dashboard_access()
    {
        // Admin
        $adminEmail = 'admin_' . time() . '_' . rand(1000, 9999) . '@test.com';
        $admin = User::create([
            'name' => 'Admin User',
            'email' => $adminEmail,
            'password' => bcrypt('password'),
            'role' => 'admin',
            'active' => true
        ]);

        $response = $this->actingAs($admin)->get('/dashboard');
        $response->assertStatus(200);

        // Secretaria
        $secretariaEmail = 'secretaria_' . time() . '_' . rand(1000, 9999) . '@test.com';
        $secretaria = User::create([
            'name' => 'Secretaria User',
            'email' => $secretariaEmail,
            'password' => bcrypt('password'),
            'role' => 'secretaria',
            'active' => true
        ]);

        $response = $this->actingAs($secretaria)->get('/dashboard');
        $response->assertStatus(200);

        // Padre
        $padreEmail = 'padre_' . time() . '_' . rand(1000, 9999) . '@test.com';
        $padre = User::create([
            'name' => 'Padre User',
            'email' => $padreEmail,
            'password' => bcrypt('password'),
            'role' => 'padre',
            'active' => true
        ]);

        $response = $this->actingAs($padre)->get('/dashboard');
        $response->assertStatus(200);

        // Docente
        $docenteEmail = 'docente_' . time() . '_' . rand(1000, 9999) . '@test.com';
        $docente = User::create([
            'name' => 'Docente User',
            'email' => $docenteEmail,
            'password' => bcrypt('password'),
            'role' => 'docente',
            'active' => true
        ]);

        $response = $this->actingAs($docente)->get('/dashboard');
        $response->assertStatus(200);
    }
}
