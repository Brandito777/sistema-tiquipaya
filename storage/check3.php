<?php
$grados = App\Models\Grado::with('nivel')->get();
foreach ($grados as $g) {
    echo $g->id . ' | ' . $g->nombre . ' | Nivel: ' . ($g->nivel->nombre ?? 'N/A') . "\n";
}
