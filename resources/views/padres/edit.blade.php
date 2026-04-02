@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 offset-md-3">
            <h1>Editar Padre: {{ $padre->nombre }} {{ $padre->apellido }}</h1>
            <hr>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('padres.update', $padre) }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label class="form-label">Nombre *</label>
                    <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" 
                           value="{{ old('nombre', $padre->nombre) }}" required>
                    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Apellido *</label>
                    <input type="text" name="apellido" class="form-control @error('apellido') is-invalid @enderror"
                           value="{{ old('apellido', $padre->apellido) }}" required>
                    @error('apellido') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">CI *</label>
                    <input type="text" name="ci" class="form-control @error('ci') is-invalid @enderror"
                           value="{{ old('ci', $padre->ci) }}" required>
                    @error('ci') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Teléfono *</label>
                    <input type="text" name="telefono" class="form-control @error('telefono') is-invalid @enderror"
                           value="{{ old('telefono', $padre->telefono) }}" required>
                    @error('telefono') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Dirección</label>
                    <input type="text" name="direccion" class="form-control" value="{{ old('direccion', $padre->direccion) }}">
                </div>

                <div class="mb-3">
                    <label class="form-label">Correo *</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                           value="{{ old('email', $padre->user->email) }}" required>
                    @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="alert alert-info">
                    <small>✓ El padre puede cambiar su contraseña desde su panel</small>
                </div>

                <hr>
                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    <a href="{{ route('padres.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
