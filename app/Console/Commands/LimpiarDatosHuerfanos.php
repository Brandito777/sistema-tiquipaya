<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Padre;

class LimpiarDatosHuerfanos extends Command
{
    protected $signature = 'limpiar:huerfanos';
    protected $description = 'Elimina usuarios con role=padre que no tienen registro en tabla padres';

    public function handle()
    {
        $this->info('🧹 Iniciando limpieza de datos huérfanos...');
        
        // Obtener todos los usuarios con role='padre'
        $usuariosHuerfanos = User::where('role', 'padre')
            ->whereDoesntHave('padre')
            ->get();
        
        $cantidad = $usuariosHuerfanos->count();
        
        if ($cantidad === 0) {
            $this->info('✅ No hay usuarios huérfanos. Todo limpio.');
            return;
        }
        
        $this->warn("⚠️  Se encontraron $cantidad usuarios padre sin registro en tabla padres");
        
        if ($this->confirm('¿Deseas eliminarlos?')) {
            foreach ($usuariosHuerfanos as $usuario) {
                $usuario->delete();
                $this->line("❌ Eliminado: {$usuario->name} ({$usuario->email})");
            }
            
            $this->info("✅ Limpieza completada. Eliminados: $cantidad usuarios.");
        } else {
            $this->info('Operación cancelada.');
        }
    }
}
