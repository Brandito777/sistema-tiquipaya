<?php
$est = App\Models\Estudiante::where('tipo', 'nuevo')->get();
echo "Total nuevos: " . $est->count() . "\n";
foreach($est as $e) {
    echo "ID: $e->id | Nombre: $e->nombre $e->apellido | Tipo: $e->tipo\n";
    $hasInscripcion = $e->inscripciones()->where('gestion', date('Y'))->exists();
    echo "  - Tiene inscripcion este año? " . ($hasInscripcion ? 'SI' : 'NO') . "\n";
}

echo "Testing Endpoint Query:\n";
$q = 'Test';
$tipo = 'nuevo';
$query = App\Models\Estudiante::with(['padre'])
    ->where('tipo', $tipo)
    ->where(function($q2) use ($q) {
        $q2->where('nombre', 'like', "%{$q}%")
            ->orWhere('apellido', 'like', "%{$q}%")
            ->orWhere('ci', 'like', "%{$q}%");
    });
if ($tipo === 'nuevo') {
    $query->whereDoesntHave('inscripciones', function($q3) {
        $q3->where('gestion', date('Y'));
    });
}
$results = $query->get();
echo "Resultados Endpoint para 'Test': " . $results->count() . "\n";
foreach($results as $r) {
    echo "  - " . $r->nombre . " " . $r->apellido . "\n";
}
