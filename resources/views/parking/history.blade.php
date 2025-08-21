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
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Registro de Movimientos</h5>
                <small class="text-muted">Total: {{ $entries->total() }} registros</small>
            </div>

            {{-- Contenido de la tarjeta --}}
            <div class="card-body">
                @if($entries->count() > 0)
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
                                <th>Acciones</th> {{-- Cambiado de "Recibo" a "Acciones" para mejor semántica --}}
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($entries as $entry)
                            <tr>
                                {{-- Placa --}}
                                <td><strong>{{ $entry->vehicle->plate }}</strong></td>

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
                                <td>{{ $entry->entry_time->format('d/m/Y H:i') }}</td>

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
                                    @if ($entry->exit_time)
                                    <span class="badge bg-success rounded-pill px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i> Completado
                                    </span>
                                    @else
                                    <span class="badge bg-primary rounded-pill px-3 py-2">
                                        <i class="fas fa-play-circle me-1"></i> En Curso
                                    </span>
                                    @endif
                                </td>
<td>
    <button class="btn btn-sm btn-primary btn-imprimir" 
            data-type="entrada" 
            data-id="{{ $entry->id }}">
        <i class="fas fa-receipt"></i> Entrada
    </button>
</td>
<td>
    <button class="btn btn-sm btn-primary btn-imprimir" 
            data-type="salida" 
            data-id="{{ $entry->id }}">
        <i class="fas fa-receipt"></i> Salida
    </button>
</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                <div class="d-flex justify-content-center">
                    {{ $entries->links() }}
                </div>

                @else
                <div class="text-center py-4">
                    <i class="fas fa-history fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No hay registros en el historial</p>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>

<!-- Modal para la factura -->
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content" style="width: 380px; margin: auto;">
            <div class="modal-header">
                <h5 class="modal-title">Factura</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body d-flex justify-content-center">
                <div id="invoiceContent" style="text-align:center;">
                    <div class="text-muted">Cargando factura...</div>
                </div>
            </div>
            <div class="modal-footer justify-content-center">
                <button id="btnPrint" class="btn btn-primary">
                    Imprimir
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {

        // Función para abrir modal con factura
       function openInvoiceModal(entryId, type) {
    $('#invoiceContent').html('<div class="text-muted">Cargando factura...</div>');
    const modal = new bootstrap.Modal(document.getElementById('invoiceModal'));
    modal.show();

    let url = '';
    if (type === 'entrada') {
    url = "{{ url('entries') }}/" + entryId + "/entrada";
} else {
    url = "{{ url('entries') }}/" + entryId + "/salida";
}

    $.get(url, function(html) {
        $('#invoiceContent').html(html);
    }).fail(function() {
        $('#invoiceContent').html('<div class="text-danger">No se pudo cargar la factura.</div>');
    });
}

        // Cuando hacen clic en un botón
       // Cuando hacen clic en un botón
$(document).on('click', '.btn-imprimir', function() {
    const entryId = $(this).data('id');   // ✅
    const type = $(this).data('type');    // ✅
    openInvoiceModal(entryId, type);
});


        // Botón de imprimir dentro del modal
        $(document).on('click', '#btnPrint', function() {
            const printContents = $('#invoiceContent').html();
const myWindow = window.open('', '', 'width=800,height=600');
myWindow.document.write(`
    <html>
    <head>
        <title>Factura</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <style>
            body { font-family: monospace; text-align:center; }
            table { width:100%; border-collapse: collapse; }
            td { padding: 5px; }
            hr { border:1px dashed #000; }
        </style>
    </head>
    <body>${printContents}</body>
    </html>
`);
myWindow.document.close();
myWindow.focus();
myWindow.print();
myWindow.close();

        });

    });
</script>

@endpush

