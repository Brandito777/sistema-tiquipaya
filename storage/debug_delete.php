<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $padre = App\Models\Padre::find(47);
    if (!$padre) {
        echo "Padre 47 no existe.\n";
        exit;
    }

    foreach ($padre->estudiantes as $estudiante) {
        foreach ($estudiante->inscripciones as $ins) {
            \App\Models\DocumentoInscripcion::where('inscripcion_id', $ins->id)->delete();
            $ins->delete();
        }
        $estudiante->delete();
    }

    $user = $padre->user;
    $padre->delete();
    
    if ($user) {
        $user->delete();
    }
    
    echo "Exito. Padre 47 borrado.\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo $e->getFile() . " " . $e->getLine() . "\n";
}
