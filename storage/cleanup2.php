<?php
use Illuminate\Support\Facades\DB;

DB::transaction(function() {
    $mapaGrados = [
        30 => 1,
        31 => 9,
        32 => 3,
        36 => 1
    ];

    $mapaNiveles = [
        8 => 1,
        9 => 3,
        10 => 2,
        14 => 1
    ];

    $tablas = [
        ['table' => 'inscripciones', 'col' => 'grado_id'],
        ['table' => 'reserva_hijos', 'col' => 'grado_solicitado_id']
    ];

    foreach ($tablas as $t) {
        foreach ($mapaGrados as $bad_id => $good_id) {
            DB::table($t['table'])->where($t['col'], $bad_id)->update([$t['col'] => $good_id]);
        }
    }

    foreach ($mapaNiveles as $bad_id => $good_id) {
        DB::table('grados')->where('nivel_id', $bad_id)->update(['nivel_id' => $good_id]);
    }

    DB::table('grados')->whereIn('id', array_keys($mapaGrados))->delete();
    DB::table('niveles')->whereIn('id', array_keys($mapaNiveles))->delete();

    echo "LIMPIEZA FINAL COMPLETADA.\n";
});
