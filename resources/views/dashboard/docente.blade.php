@extends('layouts.app')
@section('content')
<h1>Dashboard Docente</h1>

<p>Bienvenido, {{ auth()->user()->name }}.</p>
<p>Panel de docente - acceso limitado a información de estudiantes.</p>

@endsection
