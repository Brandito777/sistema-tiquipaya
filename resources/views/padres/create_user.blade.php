@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7 mt-4">
        <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="card-header bg-primary text-white d-flex align-items-center">
                <i class="fas fa-user-plus me-2" style="font-size: 1.5rem;"></i>
                <h4 class="mb-0">Crear Cuenta Web para Padre</h4>
            </div>

            <div class="card-body bg-light">
                <p class="text-muted mb-4">
                    Asignará credenciales de acceso al sistema para el padre/tutor: 
                    <strong class="text-dark">{{ $padre->nombre }} {{ $padre->apellido }}</strong> (CI: {{ $padre->ci }}).
                </p>

                @if($errors->any())
                    <div class="alert alert-danger" style="border-radius: 8px;">
                        <ul class="mb-0">
                            @foreach($errors->all() as $e)
                                <li>{{ $e }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('padres.store_user', $padre) }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label" style="font-weight: 600;"><i class="fas fa-envelope"></i> Correo Electrónico (Email)</label>
                        <input type="email" name="email" class="form-control" required placeholder="ejemplo@correo.com">
                        <small class="form-text text-muted">Este será su usuario para iniciar sesión.</small>
                    </div>

                    <div class="mb-4">
                        <label class="form-label" style="font-weight: 600;"><i class="fas fa-key"></i> Contraseña Temporal</label>
                        <input type="password" name="password" class="form-control" required minlength="6" placeholder="Mínimo 6 caracteres">
                        <small class="form-text text-muted">Asegúrese de proporcionarle esta contraseña al padre. Luego podrá cambiarla.</small>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('padres.index') }}" class="btn btn-outline-secondary px-4">
                            <i class="fas fa-arrow-left"></i> Volver al Listado
                        </a>
                        <button type="submit" class="btn btn-success px-4" style="background-color: #2e7d32; border: none;">
                            <i class="fas fa-save"></i> Generar Cuenta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
