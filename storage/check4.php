<?php
$grados = App\Models\Grado::all();
$niveles = App\Models\Nivel::all();
$out = [
    'niveles' => $niveles->map(fn($n) => "{$n->id} - {$n->nombre}")->toArray(),
    'grados' => $grados->map(fn($g) => "{$g->id} - {$g->nombre} (Nivel {$g->nivel_id})")->toArray()
];
file_put_contents('d:/temp_out.json', json_encode($out, JSON_PRETTY_PRINT));
