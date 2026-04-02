<?php
use App\Models\Padre;

$padresNulos = Padre::whereNull('user_id')->get();
echo "Padres sin cuenta: " . $padresNulos->count() . "\n";
foreach($padresNulos as $p) {
    echo $p->id . " - " . $p->nombre . " " . $p->apellido . "\n";
}

// Ensure there is at least one
if ($padresNulos->count() == 0) {
    Padre::create([
        'nombre' => 'Test',
        'apellido' => 'Orphan',
        'ci' => 'ORPHAN123',
        'telefono' => '1234567',
        'direccion' => 'Casa Falsa 123'
    ]);
    echo "Created artificial orphan parent.\n";
}
