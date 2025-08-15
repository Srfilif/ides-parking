@extends('layouts.app')

@section('title', 'Listado de Marcas')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h2>Marcas de Autos</h2>
            <div class="d-flex justify-content-between mb-3">
                <a class="btn btn-success" href="{{ route('marcas.create') }}">Crear Nueva Marca</a>
            </div>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success mt-3">
            <p>{{ $message }}</p>
        </div>
    @endif

    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>País de Origen</th>
                <th>Descripción</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($marcas as $marca)
            <tr>
                <td>{{ $marca->id }}</td>
                <td>{{ $marca->nombre }}</td>
                <td>{{ $marca->pais_origen }}</td>
                <td>{{ $marca->descripcion }}</td>
                <td>
                    <form action="{{ route('marcas.destroy', $marca->id) }}" method="POST">
                        <a class="btn btn-info btn-sm" href="{{ route('marcas.show', $marca->id) }}">Mostrar</a>
                        <a class="btn btn-primary btn-sm" href="{{ route('marcas.edit', $marca->id) }}">Editar</a>

                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que quieres eliminar esta marca?')">Eliminar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endsection