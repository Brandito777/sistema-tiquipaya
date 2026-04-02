<?php
try {
    App\Models\Padre::create([
        'nombre' => 'Test',
        'apellido' => 'Orphan Final',
        'ci' => 'ORP999',
        'telefono' => '123'
    ]);
    echo "CREADO HUERFANO!";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
