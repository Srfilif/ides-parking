@extends('layouts.app')

@section('title', 'Crear Marca')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h2>Crear Nueva Marca</h2>
            <a class="btn btn-primary mb-3" href="{{ route('marcas.index') }}">Volver</a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('marcas.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre:</label>
            <input type="text" name="nombre" class="form-control" placeholder="Nombre de la marca">
        </div>
        <div class="mb-3">
            <label for="pais_origen" class="form-label">País de Origen:</label>
            <input type="text" name="pais_origen" class="form-control" placeholder="Ej: Japón, Alemania, Estados Unidos">
        </div>
        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción:</label>
            <textarea name="descripcion" class="form-control" style="height:150px" placeholder="Descripción de la marca"></textarea>
        </div>
        <button type="submit" class="btn btn-success">Guardar</button>
    </form>
@endsection