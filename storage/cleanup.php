<?php
use Illuminate\Support\Facades\DB;

$clean = [];
DB::transaction(function() {
    // Definir los IDs canónicos
    $mapaNiveles = [];
    $niveles = DB::table('niveles')->get();
    foreach($niveles as $n) {
        $canon = DB::table('niveles')->where('nombre', $n->nombre)->orderBy('id')->first();
        if ($canon->id !== $n->id) {
            $mapaNiveles[$n->id] = $canon->id;
        }
    }

    $mapaGrados = [];
    $grados = DB::table('grados')->get();
    foreach($grados as $g) {
        $canon = DB::table('grados')
            ->where('nombre', $g->nombre)
            ->where('nivel_id', $mapaNiveles[$g->nivel_id] ?? $g->nivel_id)
            ->orderBy('id')->first();
        if ($canon->id !== $g->id) {
            $mapaGrados[$g->id] = $canon->id;
        }
    }

    echo "Voy a mapear " . count($mapaGrados) . " grados y " . count($mapaNiveles) . " niveles duplicados.\n";

    // Actualizar referencias
    $tablas = [
        ['table' => 'inscripciones', 'col' => 'grado_id'],
        ['table' => 'reserva_hijos', 'col' => 'grado_solicitado_id']
    ];

    foreach ($tablas as $t) {
        foreach ($mapaGrados as $bad_id => $good_id) {
            DB::table($t['table'])->where($t['col'], $bad_id)->update([$t['col'] => $good_id]);
        }
    }

    // Update grados nivel_id to canonical so we can delete bad niveles
    foreach ($mapaNiveles as $bad_id => $good_id) {
        DB::table('grados')->where('nivel_id', $bad_id)->update(['nivel_id' => $good_id]);
    }

    // Now delete duplicates
    if (!empty($mapaGrados)) {
        DB::table('grados')->whereIn('id', array_keys($mapaGrados))->delete();
        echo "Borrados grados duplicados.\n";
    }

    if (!empty($mapaNiveles)) {
        DB::table('niveles')->whereIn('id', array_keys($mapaNiveles))->delete();
        echo "Borrados niveles duplicados.\n";
    }

    echo "LIMPIEZA COMPLETADA.\n";
});
