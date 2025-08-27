@extends('layouts.app')

@section('content')
<h2>Editar Tarifa</h2>

@if($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<form action="{{ route('tarifas.update', $tarifa) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="mb-3">
        <label for="zona_id" class="form-label">Zona</label>
        <select name="zona_id" class="form-select" required>
            @foreach($zonas as $zona)
            <option value="{{ $zona->id }}" {{ $zona->id == $tarifa->zona_id ? 'selected' : '' }}>
                {{ $zona->nombre }}
            </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label for="tipo_vehiculo_id" class="form-label">Tipo de Vehículo</label>
        <select name="tipo_vehiculo_id" class="form-select" required>
            @foreach($tiposVehiculo as $tipo)
            <option value="{{ $tipo->id }}" {{ $tipo->id == $tarifa->tipo_vehiculo_id ? 'selected' : '' }}>
                {{ $tipo->nombre }}
            </option>
            @endforeach
        </select>
    </div>

    {{-- Nuevos campos --}}
    <div class="mb-3">
        <label for="fraccion_hora" class="form-label">Fracción (Hora)</label>
        <input type="text" name="fraccion_hora" class="form-control"
            value="{{ $tarifa->fraccion_hora }}" oninput="formatearMoneda(this)">
    </div>

    <div class="mb-3">
        <label for="hora_adicional" class="form-label">Hora Adicional</label>
        <input type="text" name="hora_adicional" class="form-control"
            value="{{ $tarifa->hora_adicional }}" oninput="formatearMoneda(this)">
    </div>

    <div class="mb-3">
        <label for="media_jornada" class="form-label">Media Jornada</label>
        <input type="text" name="media_jornada" class="form-control"
            value="{{ $tarifa->media_jornada }}" oninput="formatearMoneda(this)">
    </div>

    <div class="mb-3">
        <label for="jornada_completa" class="form-label">Jornada Completa</label>
        <input type="text" name="jornada_completa" class="form-control"
            value="{{ $tarifa->jornada_completa }}" oninput="formatearMoneda(this)">
    </div>

    <div class="mb-3">
        <label for="mensualidad_diurna" class="form-label">Mensualidad Diurna</label>
        <input type="text" name="mensualidad_diurna" class="form-control"
            value="{{ $tarifa->mensualidad_diurna }}" oninput="formatearMoneda(this)">
    </div>

    <button type="submit" class="btn btn-primary">Actualizar</button>
    <a href="{{ route('tarifas.index') }}" class="btn btn-secondary">Cancelar</a>
</form>
@endsection

<script>
    function formatearMoneda(input) {

        let valor = input.value.replace(/[^0-9,]/g, '');


        let partes = valor.split(",");
        let parteEntera = partes[0];
        let parteDecimal = partes[1] !== undefined ? "," + partes[1].substring(0, 2) : "";


        parteEntera = parteEntera.replace(/\B(?=(\d{3})+(?!\d))/g, ".");


        input.value = parteEntera + parteDecimal;
    }
</script>