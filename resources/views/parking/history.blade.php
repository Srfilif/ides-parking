{{-- resources/views/parking/history.blade.php --}}
@extends('layouts.app')

@section('title', 'Historial - Parqueadero')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">
            <i class="fas fa-history me-2"></i>Historial de Entradas y Salidas
        </h1>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">

            {{-- Encabezado de la tarjeta --}}
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Registro de Movimientos</h5>
                    <small class="text-muted">Total: {{ $entries->total() }} registros</small>
                </div>
            </div>

            {{-- Contenido de la tarjeta --}}
            <div class="card-body">
                @if($entries->count() > 0)

                {{-- Tabla de registros --}}
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Placa</th>
                                <th>Tipo</th>
                                <th>Espacio</th>
                                <th>Entrada</th>
                                <th>Salida</th>
                                <th>Duración</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($entries as $entry)
                            <tr>
                                {{-- Placa --}}
                                <td>
                                    <strong>{{ $entry->vehicle->plate }}</strong>
                                </td>

                                {{-- Tipo de vehículo --}}
                                <td>
                                    @if($entry->vehicle && $entry->vehicle->tipoVehiculo)
                                    <span class="badge bg-secondary">
                                        {{ ucfirst($entry->vehicle->tipoVehiculo->nombre) }}
                                    </span>
                                    @else
                                    <span class="text-muted">Desconocido</span>
                                    @endif
                                </td>

                                {{-- Espacio --}}
                                <td>
                                    @if ($entry->espacio)
                                    <span class="badge bg-info">
                                        Zona {{ $entry->espacio->zona->nombre ?? 'Sin zona' }} -
                                        Espacio #{{ $entry->espacio->numero_espacio }}
                                    </span>
                                    @else
                                    <span class="badge bg-secondary">No asignado</span>
                                    @endif
                                </td>

                                {{-- Hora de entrada --}}
                                <td>
                                    {{ $entry->entry_time->format('d/m/Y H:i') }}
                                </td>

                                {{-- Hora de salida --}}
                                <td>
                                    @if($entry->exit_time)
                                    {{ $entry->exit_time->format('d/m/Y H:i') }}
                                    @else
                                    <span class="badge bg-warning text-dark">En parqueadero</span>
                                    @endif
                                </td>

                                {{-- Duración --}}
                                <td>
                                    @if($entry->exit_time)
                                    @php
                                    $duration = $entry->entry_time->diffInMinutes($entry->exit_time);
                                    $hours = floor($duration / 60);
                                    $minutes = $duration % 60;
                                    @endphp
                                    {{ $hours > 0 ? $hours . 'h ' : '' }}{{ $minutes }}m
                                    @else
                                    <span class="badge bg-info">
                                        {{ $entry->entry_time->diffForHumans(null, true) }}
                                    </span>
                                    @endif
                                </td>

                                {{-- Estado --}}
                                <td>
                                    @if($entry->exit_time)
                                    <span class="badge bg-success">Completado</span>
                                    @else
                                    <span class="badge bg-primary">Activo</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}

                <div class="d-flex justify-content-center mt-3">

                    {{ $entries->links('pagination::simple-bootstrap-5') }}

                </div>

                @else
                {{-- Mensaje si no hay registros --}}
                <div class="text-center py-4">
                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No hay registros en el historial</p>
                </div>
                @endif
            </div>

        </div>
    </div>

</div>
@endsection