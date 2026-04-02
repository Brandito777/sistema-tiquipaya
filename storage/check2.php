<?php
echo "=== RESERVAS ===\n";
foreach(App\Models\Reserva::all() as $r) {
    echo "ID: $r->id | Padre: $r->nombre_padre | Estado: $r->estado\n";
}
echo "\n=== ESTUDIANTES NUEVOS ===\n";
foreach(App\Models\Estudiante::where('tipo', 'nuevo')->get() as $e) {
    echo "ID: $e->id | Nombre: $e->nombre $e->apellido\n";
}
