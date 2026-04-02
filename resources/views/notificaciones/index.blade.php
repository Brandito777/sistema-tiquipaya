@extends('layouts.app')
@section('content')
<h1>Mis Notificaciones</h1>

<table>
    <thead>
        <tr>
            <th>Título</th>
            <th>Mensaje</th>
            <th>Tipo</th>
            <th>Fecha</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        @forelse($notificaciones as $notif)
            <tr>
                <td>{{ $notif->titulo }}</td>
                <td>{{ $notif->mensaje }}</td>
                <td>{{ $notif->tipo }}</td>
                <td>{{ $notif->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    @if(!$notif->leida)
                        <form method="POST" action="{{ route('notificaciones.leer', $notif) }}" style="display:inline;">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn">Marcar Leída</button>
                        </form>
                    @else
                        <span style="color: green;">✓ Leída</span>
                    @endif
                </td>
            </tr>
        @empty
            <tr><td colspan="5">No tienes notificaciones.</td></tr>
        @endforelse
    </tbody>
</table>

{{ $notificaciones->links() }}

@endsection
