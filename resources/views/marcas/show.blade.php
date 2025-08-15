@extends('layouts.app')

@section('title', 'Detalles de la Marca')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <h2>Detalles de la Marca</h2>
            <a class="btn btn-primary mb-3" href="{{ route('marcas.index') }}">Volver</a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <strong>Nombre:</strong> {{ $marca->nombre }}
        </div>
        <div class="card-body">
            <p class="card-text"><strong>País de Origen:</strong> {{ $marca->pais_origen }}</p>
            <p class="card-text"><strong>Descripción:</strong> {{ $marca->descripcion }}</p>
        </div>
    </div>
@endsection