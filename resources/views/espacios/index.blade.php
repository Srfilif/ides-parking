@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Espacios de Parqueadero</h1>

    <a href="{{ route('espacios.create') }}" class="btn btn-primary mb-3">Nuevo Espacio</a>

    <div class="table-responsive"> <!-- 👈 evita que se desborde -->
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Número</th>
                    <th>Zona</th>
                    <th>Tipo Vehículo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($espacios as $espacio)
                <tr>
                    <td>{{ $espacio->id }}</td>
                    <td>{{ $espacio->numero_espacio }}</td>
                    <td>{{ $espacio->zona->nombre }}</td>
                    <td>{{ $espacio->tipoVehiculo->nombre }}</td>
                    <td>
                        <a href="{{ route('espacios.edit', $espacio) }}" class="btn btn-sm btn-warning">Editar</a>
                        <form action="{{ route('espacios.destroy', $espacio) }}" method="POST" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este espacio?')">Eliminar</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    
    <div class="d-flex justify-content-center mt-3">
    
        {{ $espacios->links('pagination::simple-bootstrap-5') }}

    </div>
</div>
@endsection
