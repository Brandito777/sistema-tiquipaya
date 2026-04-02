<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    App\Models\Padre::create([
        'nombre' => 'Fernando',
        'apellido' => 'Orphan',
        'ci' => 'ORP001',
        'telefono' => '123'
    ]);
    echo "CREADO!";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
