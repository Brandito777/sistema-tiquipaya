@extends('layouts.app')
@section('content')

<h1>Inscripción – Alumno Antiguo</h1>
<p style="color:#555; margin-bottom:20px;">
    Busca al estudiante por nombre o apellido. Al seleccionarlo, sus datos aparecerán automáticamente.
    Confirma o cambia el grado para la gestión <strong>{{ date('Y') }}</strong> y guarda.
</p>

@if($errors->any())
    <div style="background:#fdecea; border:1px solid #f44336; padding:10px; border-radius:6px; margin-bottom:15px;">
        @foreach($errors->all() as $error)
            <p style="color:#c62828; margin:3px 0;">⚠ {{ $error }}</p>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ route('inscripciones.antiguo.store') }}" id="form-antiguo">
    @csrf

    {{-- ① BUSCADOR SELECT2 --}}
    <label style="font-weight:600;">Buscar estudiante (nombre o apellido):</label>
    <select id="select-estudiante" name="estudiante_id" style="width:100%; margin-bottom:20px;" required>
        <option value="">Escriba para buscar...</option>
    </select>

    {{-- ② DATOS AUTO-RELLENADOS (solo lectura) --}}
    <div id="info-estudiante" style="display:none; background:#f1f8e9; border:1px solid #a5d6a7;
         border-radius:8px; padding:16px; margin-bottom:20px;">
        <h3 style="margin-top:0; color:#2e7d32;">📋 Datos del estudiante seleccionado</h3>
        <table style="width:100%; border-collapse:collapse;">
            <tr>
                <th style="width:35%; padding:6px 0; text-align:left; color:#555;">Nombre completo:</th>
                <td id="info-nombre" style="padding:6px 0; font-weight:600;"></td>
            </tr>
            <tr>
                <th style="padding:6px 0; text-align:left; color:#555;">Padre / Tutor:</th>
                <td id="info-padre" style="padding:6px 0;"></td>
            </tr>
            <tr>
                <th style="padding:6px 0; text-align:left; color:#555;">Teléfono del padre:</th>
                <td id="info-telefono" style="padding:6px 0;"></td>
            </tr>
            <tr>
                <th style="padding:6px 0; text-align:left; color:#555;">Grado anterior:</th>
                <td id="info-grado" style="padding:6px 0; color:#2e7d32; font-weight:600;"></td>
            </tr>
        </table>
    </div>

    {{-- ③ SELECCIÓN DE NUEVO GRADO --}}
    <div id="seccion-grado" style="display:none;">
        <label style="font-weight:600;">Grado para la gestión {{ date('Y') }}:</label>
        <p style="color:#888; font-size:13px; margin:2px 0 8px;">
            Verifica si sube de grado o permanece en el mismo. Cambia si corresponde.
        </p>
        <select name="grado_id" id="select-grado" required style="width:100%; margin-bottom:16px;">
            <option value="">-- Seleccionar grado --</option>
            @foreach($grados->groupBy(fn($g) => $g->nivel->nombre ?? 'Sin nivel') as $nivel => $gradosNivel)
                <optgroup label="{{ $nivel }}">
                    @foreach($gradosNivel as $grado)
                        <option value="{{ $grado->id }}">{{ $grado->nombre }}</option>
                    @endforeach
                </optgroup>
            @endforeach
        </select>

        <label style="font-weight:600;">Observaciones (opcional):</label>
        <textarea name="observaciones" rows="2" style="width:100%; padding:8px; margin-bottom:16px;"
            placeholder="Notas adicionales sobre la inscripción..."></textarea>

        <button type="submit" class="btn"
                style="background:#2e7d32; color:white; padding:10px 24px; font-size:15px;">
            ✅ Confirmar Inscripción
        </button>
        <a href="{{ route('inscripciones.index') }}" class="btn"
           style="background:#757575; color:white; padding:10px 20px; font-size:15px; margin-left:8px;">
            Cancelar
        </a>
    </div>

</form>

{{-- Select2 CSS + JS --}}
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {

    // Inicializar Select2 con AJAX
    $('#select-estudiante').select2({
        placeholder: 'Escriba nombre o apellido del estudiante...',
        minimumInputLength: 2,
        language: {
            inputTooShort: () => 'Escriba al menos 2 caracteres para buscar...',
            noResults:     () => 'No se encontraron alumnos antiguos con ese nombre.',
            searching:     () => 'Buscando...',
        },
        ajax: {
            url: '{{ route("estudiantes.buscar") }}',
            dataType: 'json',
            delay: 300,
            data: params => ({ q: params.term }),
            processResults: data => ({ results: data.results }),
            cache: true,
        },
    });

    // Cuando se selecciona un estudiante → auto-rellenar info
    $('#select-estudiante').on('select2:select', function(e) {
        const d = e.params.data;

        // Mostrar datos
        $('#info-nombre').text(d.text);
        $('#info-padre').text(d.padre_nombre);
        $('#info-telefono').text(d.padre_tel);
        $('#info-grado').text(
            d.grado_actual + (d.nivel_actual ? ' (' + d.nivel_actual + ')' : '')
        );
        $('#info-estudiante').show();
        $('#seccion-grado').show();

        // Pre-seleccionar el mismo grado del año anterior en el select
        if (d.grado_id) {
            $('#select-grado').val(d.grado_id);
        }
    });

    // Si se limpia la selección → ocultar info
    $('#select-estudiante').on('select2:clear', function() {
        $('#info-estudiante').hide();
        $('#seccion-grado').hide();
    });
});
</script>

@endsection
