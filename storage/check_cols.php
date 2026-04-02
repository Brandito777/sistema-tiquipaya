<?php
$columns = \Illuminate\Support\Facades\DB::select('SHOW COLUMNS FROM padres');
foreach($columns as $c) {
    echo $c->Field . ' | Type: ' . $c->Type . ' | Null: ' . $c->Null .  ' | Default: ' . $c->Default . "\n";
}
