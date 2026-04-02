<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Nivel;
use App\Models\Grado;
use App\Models\Padre;
use App\Models\Estudiante;
use App\Models\Inscripcion;
use App\Models\DocumentoInscripcion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =============================================
        // USUARIOS
        // =============================================
        
        // Admin
        User::create([
            'name' => 'Admin Sistema',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'active' => true,
        ]);

        // Secretaria
        User::create([
            'name' => 'Secretaria',
            'email' => 'secretaria@test.com',
            'password' => Hash::make('password'),
            'role' => 'secretaria',
            'active' => true,
        ]);

        // Padre 1
        $userPadre1 = User::create([
            'name' => 'Juan Morales',
            'email' => 'juan@test.com',
            'password' => Hash::make('password'),
            'role' => 'padre',
            'active' => true,
        ]);

        // Padre 2
        $userPadre2 = User::create([
            'name' => 'María García',
            'email' => 'maria@test.com',
            'password' => Hash::make('password'),
            'role' => 'padre',
            'active' => true,
        ]);

        // Docente
        User::create([
            'name' => 'Prof. Carlos López',
            'email' => 'docente@test.com',
            'password' => Hash::make('password'),
            'role' => 'docente',
            'active' => true,
        ]);

        // =============================================
        // NIVELES EDUCATIVOS
        // =============================================
        
        $nivel1 = Nivel::create([
            'nombre' => 'Inicial en Familia Comunitaria',
            'descripcion' => 'Kinder 1 y Kinder 2',
        ]);

        $nivel2 = Nivel::create([
            'nombre' => 'Primaria Comunitaria Vocacional',
            'descripcion' => '1ro al 6to Primaria',
        ]);

        $nivel3 = Nivel::create([
            'nombre' => 'Secundaria Comunitaria Productiva',
            'descripcion' => '1ro al 6to Secundaria',
        ]);

        // =============================================
        // GRADOS
        // =============================================
        
        // Nivel 1
        Grado::create(['nivel_id' => $nivel1->id, 'nombre' => 'Kinder 1', 'tiene_tecnico' => false]);
        Grado::create(['nivel_id' => $nivel1->id, 'nombre' => 'Kinder 2', 'tiene_tecnico' => false]);

        // Nivel 2
        Grado::create(['nivel_id' => $nivel2->id, 'nombre' => '1ro Primaria', 'tiene_tecnico' => false]);
        Grado::create(['nivel_id' => $nivel2->id, 'nombre' => '2do Primaria', 'tiene_tecnico' => false]);
        Grado::create(['nivel_id' => $nivel2->id, 'nombre' => '3ro Primaria', 'tiene_tecnico' => false]);
        Grado::create(['nivel_id' => $nivel2->id, 'nombre' => '4to Primaria', 'tiene_tecnico' => false]);
        Grado::create(['nivel_id' => $nivel2->id, 'nombre' => '5to Primaria', 'tiene_tecnico' => false]);
        Grado::create(['nivel_id' => $nivel2->id, 'nombre' => '6to Primaria', 'tiene_tecnico' => false]);

        // Nivel 3
        Grado::create(['nivel_id' => $nivel3->id, 'nombre' => '1ro Secundaria', 'tiene_tecnico' => false]);
        Grado::create(['nivel_id' => $nivel3->id, 'nombre' => '2do Secundaria', 'tiene_tecnico' => false]);
        Grado::create(['nivel_id' => $nivel3->id, 'nombre' => '3ro Secundaria', 'tiene_tecnico' => false]);
        $grado4 = Grado::create(['nivel_id' => $nivel3->id, 'nombre' => '4to Secundaria', 'tiene_tecnico' => true]);
        Grado::create(['nivel_id' => $nivel3->id, 'nombre' => '5to Secundaria', 'tiene_tecnico' => true]);
        Grado::create(['nivel_id' => $nivel3->id, 'nombre' => '6to Secundaria', 'tiene_tecnico' => true]);

        // =============================================
        // PADRES
        // =============================================
        
        $padre1 = Padre::create([
            'user_id' => $userPadre1->id,
            'nombre' => 'Juan',
            'apellido' => 'Morales Pérez',
            'ci' => '1234567',
            'telefono' => '76554433',
            'direccion' => 'Calle Principal 123',
        ]);

        $padre2 = Padre::create([
            'user_id' => $userPadre2->id,
            'nombre' => 'María',
            'apellido' => 'García López',
            'ci' => '7654321',
            'telefono' => '71223344',
            'direccion' => 'Avenida Central 456',
        ]);

        // =============================================
        // ESTUDIANTES
        // =============================================
        
        $estudiante1 = Estudiante::create([
            'padre_id' => $padre1->id,
            'nombre' => 'Luis',
            'apellido' => 'Morales García',
            'ci' => '1111111',
            'fecha_nacimiento' => '2015-05-10',
            'genero' => 'M',
            'tipo' => 'antiguo',
        ]);

        $estudiante2 = Estudiante::create([
            'padre_id' => $padre1->id,
            'nombre' => 'Ana',
            'apellido' => 'Morales García',
            'ci' => '2222222',
            'fecha_nacimiento' => '2017-08-22',
            'genero' => 'F',
            'tipo' => 'antiguo',
        ]);

        $estudiante3 = Estudiante::create([
            'padre_id' => $padre2->id,
            'nombre' => 'Carlos',
            'apellido' => 'García Rodríguez',
            'ci' => '3333333',
            'fecha_nacimiento' => '2016-03-15',
            'genero' => 'M',
            'tipo' => 'nuevo',
        ]);

        // =============================================
        // INSCRIPCIONES
        // =============================================
        
        $grados = Grado::all();
        $grado1 = $grados->where('nombre', '2do Primaria')->first();
        $grado2 = $grados->where('nombre', '4to Primaria')->first();
        $grado3 = $grados->where('nombre', '1ro Secundaria')->first();

        $inscripcion1 = Inscripcion::create([
            'estudiante_id' => $estudiante1->id,
            'grado_id' => $grado1->id,
            'gestion' => date('Y'),
            'estado' => 'aprobada',
            'observaciones' => 'Inscripción actualizada',
            'fecha_inscripcion' => date('Y-m-d'),
        ]);

        $inscripcion2 = Inscripcion::create([
            'estudiante_id' => $estudiante2->id,
            'grado_id' => $grado2->id,
            'gestion' => date('Y'),
            'estado' => 'pendiente',
            'observaciones' => '',
            'fecha_inscripcion' => date('Y-m-d'),
        ]);

        $inscripcion3 = Inscripcion::create([
            'estudiante_id' => $estudiante3->id,
            'grado_id' => $grado3->id,
            'gestion' => date('Y'),
            'estado' => 'pendiente',
            'observaciones' => 'Estudiante nuevo',
            'fecha_inscripcion' => date('Y-m-d'),
        ]);

        // =============================================
        // DOCUMENTOS INSCRIPCION
        // =============================================
        
        $documentos = [
            'Certificado de nacimiento',
            'Libreta anterior',
            'Fotocopia CI padre',
            'Foto carnet',
            'Certificado medico',
        ];

        foreach ($documentos as $doc) {
            DocumentoInscripcion::create([
                'inscripcion_id' => $inscripcion1->id,
                'tipo' => $doc,
                'presentado' => true,
            ]);
        }

        foreach ($documentos as $doc) {
            DocumentoInscripcion::create([
                'inscripcion_id' => $inscripcion2->id,
                'tipo' => $doc,
                'presentado' => $doc !== 'Certificado medico',
            ]);
        }

        foreach ($documentos as $doc) {
            DocumentoInscripcion::create([
                'inscripcion_id' => $inscripcion3->id,
                'tipo' => $doc,
                'presentado' => false,
            ]);
        }
    }
}
