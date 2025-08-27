@extends('layouts.app')

@section('content')
<h1>Tarifas por Zona y Tipo de Vehículo</h1>

<a href="{{ route('tarifas.create') }}" class="btn btn-primary mb-3">Crear nueva tarifa</a>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>Zona</th>
            <th>Tipo de Vehículo</th>
            <th>Fracción Hora</th>
            <th>Hora Adicional</th>
            <th>Media Jornada</th>
            <th>Jornada Completa</th>
            <th>Mensualidad Diurna</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        @forelse($tarifas as $tarifa)
        <tr>
            <td>{{ $tarifa->zona->nombre }}</td>
            <td>{{ $tarifa->tipoVehiculo->nombre }}</td>
            <td>${{ number_format($tarifa->fraccion_hora, 0, ',', '.') }}</td>
            <td>${{ number_format($tarifa->hora_adicional, 0, ',', '.') }}</td>
            <td>${{ number_format($tarifa->media_jornada, 0, ',', '.') }}</td>
            <td>${{ number_format($tarifa->jornada_completa, 0, ',', '.') }}</td>
            <td>${{ number_format($tarifa->mensualidad_diurna, 0, ',', '.') }}</td>
            <td>
                <a href="{{ route('tarifas.edit', $tarifa) }}" class="btn btn-sm btn-warning">Editar</a>
                <form action="{{ route('tarifas.destroy', $tarifa) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar esta tarifa?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Eliminar</button>
                </form>
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="8" class="text-center">No hay tarifas registradas</td>
        </tr>
        @endforelse
    </tbody>
</table>

{{ $tarifas->links() }}
@endsection
