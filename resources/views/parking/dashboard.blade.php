{{-- resources/views/parking/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard - Parqueadero')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 class="mb-4">
            <i class="fas fa-tachometer-alt me-2"></i>Dashboard del Parqueadero
        </h1>
    </div>
</div>

<!-- Estadísticas -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Espacios Disponibles</h5>
                        <h2>{{ $availableSpaces }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-parking fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Espacios Ocupados</h5>
                        <h2>{{ $totalSpaces - $availableSpaces }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-car fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Total Espacios</h5>
                        <h2>{{ $totalSpaces }}</h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-list fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Formularios de Entrada y Salida -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card parking-card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-sign-in-alt me-2"></i>Registrar Entrada</h5>
            </div>
            <div class="card-body">
                <form id="entryForm">
                    @csrf
                    <div class="mb-3">
                        <label for="entry_plate" class="form-label">Placa del Vehículo</label>
                        <input type="text" class="form-control plate-input" id="entry_plate" name="plate"
                            placeholder="ABC123" pattern="[A-Z]{3}[0-9]{2}[0-9A-Z]" maxlength="6" required>
                        <div class="form-text">Formato: ABC123 o ABC12D</div>
                    </div>

                    <div class="mb-3">

                        <select name="tipo_vehiculo_id" id="type" class="form-select" required>
                            <option value="">Seleccione tipo de vehículo</option>
                            @foreach ($tipos as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="entry_space" class="form-label">Espacio Disponible</label>
                        <select class="form-select" id="entry_space" name="espacio_id" required>

                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="entry_brand" class="form-label">Marca</label>
                                <select class="form-select" id="entry_brand" name="marca_id">
                                    <option value="">Selecciona una marca</option>
                                    @foreach($marcas as $marca)
                                    <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="entry_model" class="form-label">Modelo</label>
                                <input type="text" class="form-control" id="entry_model" name="model">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="entry_color" class="form-label">Color</label>
                        <input type="text" class="form-control" id="entry_color" name="color">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Objetos Dejados</label>
                        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; align-items: center;">
                            <div class="form-check m-0">
                                <input class="form-check-input" type="checkbox" id="item_casco" name="casco" value="1">
                                <label class="form-check-label" for="item_casco">Casco</label>
                            </div>
                            <div class="form-check m-0">
                                <input class="form-check-input" type="checkbox" id="item_chaleco" name="chaleco" value="1">
                                <label class="form-check-label" for="item_chaleco">Chaleco</label>
                            </div>
                            <div class="form-check m-0">
                                <input class="form-check-input" type="checkbox" id="item_llaves" name="llaves" value="1">
                                <label class="form-check-label" for="item_llaves">Llaves</label>
                            </div>
                            <div class="d-flex align-items-center m-0" style="gap: 8px; margin-top: 2px;">
                                <input class="form-check-input mt-0" type="checkbox" id="item_otro" name="otro" value="1">
                                <label class="form-check-label mb-0" for="item_otro">Otro</label>
                                <div class="hidden-box">
                                    <input type="text" class="form-control form-control-sm p-1"
                                        name="otro_texto"
                                        placeholder="¿Cuál?"
                                        style="width: 100px; height: 30px;">
                                </div>
                            </div>

                            <style>
                                .hidden-box {
                                    max-width: 0;
                                    opacity: 0;
                                    overflow: hidden;
                                    transition: all 0.4s ease;
                                }

                                .hidden-box.show {
                                    max-width: 120px;
                                    opacity: 1;
                                }
                            </style>

                            <script>
                                const checkboxOtro = document.getElementById('item_otro');
                                const hiddenBox = document.querySelector('.hidden-box');
                                const inputOtro = document.querySelector('input[name="otro_texto"]');

                                checkboxOtro.addEventListener('change', function() {
                                    if (this.checked) {
                                        hiddenBox.classList.add('show');
                                        setTimeout(() => inputOtro.focus(), 300);
                                    } else {
                                        hiddenBox.classList.remove('show');
                                        inputOtro.value = '';
                                    }
                                });
                            </script>
                        </div>
                    </div>



                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="is_mensualidad" name="is_mensualidad" value="1">
                        <label class="form-check-label fw-bold" for="is_mensualidad">
                            Vehículo bajo mensualidad
                        </label>
                    </div>

                    <!-- Contenedor de fechas oculto al inicio -->
                    <div id="mensualidadFechas" style="display: none;">
                        <div class="mb-3">
                            <label for="mensualidad_inicio" class="form-label">Inicio de mensualidad</label>
                            <input type="date" class="form-control" id="mensualidad_inicio" name="mensualidad_inicio">
                        </div>

                        <div class="mb-3">
                            <label for="mensualidad_fin" class="form-label">Fin de mensualidad</label>
                            <input type="date" class="form-control" id="mensualidad_fin" name="mensualidad_fin">
                        </div>
                    </div>


                    <button type="submit" class="btn btn-success w-100">
                        <i class="fas fa-plus me-2"></i>Registrar Entrada
                    </button>


                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card parking-card">
            <div class="card-header bg-danger text-white">
                <h5 class="mb-0"><i class="fas fa-sign-out-alt me-2"></i>Registrar Salida</h5>
            </div>
            <div class="card-body">
                <form id="exitForm">
                    @csrf
                    <div class="mb-3">
                        <label for="exit_plate" class="form-label">Placa del Vehículo</label>
                        <input type="text" class="form-control plate-input" id="exit_plate" name="plate"
                            placeholder="ABC123" pattern="[A-Z]{3}[0-9]{2}[0-9A-Z]" maxlength="6" required>
                        <div class="form-text">Formato: ABC123 o ABC12D</div>
                    </div>
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="fas fa-sign-out-alt me-2"></i>Registrar Salida
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Vehículos Actualmente Estacionados -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-car me-2"></i>Vehículos Estacionados ({{ $activeEntries->count() }})</h5>
            </div>

            <!-- Sección de Búsqueda -->
            <div class="card-body border-bottom">
                <div class="row mb-3">
                    <div class="col-md-8">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" id="searchInput" placeholder="Buscar por placa, marca, modelo, color...">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <button class="btn btn-outline-primary me-2" type="button" id="searchBtn">
                            <i class="fas fa-search"></i> Buscar
                        </button>
                        <button class="btn btn-outline-secondary" type="button" id="toggleAdvancedSearch">
                            <i class="fas fa-filter"></i> Filtros Avanzados
                        </button>
                    </div>
                </div>

                <!-- Búsqueda Avanzada (Inicialmente oculta) -->
                <div id="advancedSearchPanel" class="row" style="display: none;">
                    <div class="col-12">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title mb-3"><i class="fas fa-sliders-h"></i> Filtros Avanzados</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <label for="filterPlate" class="form-label">Placa</label>
                                        <input type="text" class="form-control form-control-sm" id="filterPlate" placeholder="Ej: ABC123">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="filterType" class="form-label">Tipo de Vehículo</label>
                                        <select class="form-select form-select-sm" id="filterType">
                                            <option value="">Todos los tipos</option>
                                            <option value="Automóvil">Automóvil</option>
                                            <option value="Motocicleta">Motocicleta</option>
                                            <option value="Camioneta">Camioneta</option>
                                            <option value="Camión">Camión</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="filterBrand" class="form-label">Marca</label>
                                        <input type="text" class="form-control form-control-sm" id="filterBrand" placeholder="Ej: Toyota">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="filterModel" class="form-label">Modelo</label>
                                        <input type="text" class="form-control form-control-sm" id="filterModel" placeholder="Ej: Corolla">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-3">
                                        <label for="filterColor" class="form-label">Color</label>
                                        <input type="text" class="form-control form-control-sm" id="filterColor" placeholder="Ej: Azul">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="filterZone" class="form-label">Zona</label>
                                        <select class="form-select form-select-sm" id="filterZone">
                                            <option value="">Todas las zonas</option>
                                            <option value="A">Zona A</option>
                                            <option value="B">Zona B</option>
                                            <option value="C">Zona C</option>
                                            <option value="VIP">Zona VIP</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="filterTimeFrom" class="form-label">Desde</label>
                                        <input type="datetime-local" class="form-control form-control-sm" id="filterTimeFrom">
                                    </div>
                                    <div class="col-md-3">
                                        <label for="filterTimeUntil" class="form-label">Hasta</label>
                                        <input type="datetime-local" class="form-control form-control-sm" id="filterTimeUntil">
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <button class="btn btn-primary btn-sm me-2" id="applyAdvancedFilters">
                                            <i class="fas fa-filter"></i> Aplicar Filtros
                                        </button>
                                        <button class="btn btn-secondary btn-sm" id="clearFilters">
                                            <i class="fas fa-times"></i> Limpiar Filtros
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if($activeEntries->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped" id="vehiclesTable">
                        <thead>
                            <tr>
                                <th>Placa</th>
                                <th>Tipo</th>
                                <th>Marca/Modelo</th>
                                <th>Color</th>
                                <th>Espacio</th>
                                <th>Fecha/Hora de Entrada</th>
                                <th>Tiempo Estacionado</th>
                                <th>Factura</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($activeEntries as $entry)
                            <tr>
                                <td><strong>{{ $entry->vehicle->plate }}</strong></td>
                                <td>
                                    <span class="badge bg-secondary">{{ $entry->vehicle->tipoVehiculo->nombre ?? 'Desconocido' }}</span>
                                </td>
                                <td>{{ $entry->vehicle->marca?->nombre }} - {{ $entry->vehicle->model }}</td>
                                <td>{{ $entry->vehicle->color }}</td>
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
                                <td>{{ $entry->entry_time->format('d/m/Y H:i') }}</td>
                                <td>
                                    <span class="badge bg-warning text-dark">
                                        {{ $entry->entry_time->diffForHumans(null, true) }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary btn-imprimir" data-entry-id="{{ $entry->id }}">
                                        <i class="fas fa-receipt"></i> Recibo Entrada
                                    </button>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger btn-exit-table" data-plate="{{ $entry->vehicle->plate }}">
                                        <i class="fas fa-sign-out-alt"></i> Salida
                                    </button>
                                    <button class="btn btn-sm btn-primary btn-imprimir" data-entry-id="{{ $entry->id }}">
                                        <i class="fas fa-receipt"></i> Recibo
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-4">
                    <i class="fas fa-car fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No hay vehículos estacionados actualmente</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const checkMensualidad = document.getElementById("is_mensualidad");
        const contenedorFechas = document.getElementById("mensualidadFechas");

        function toggleFechas() {
            contenedorFechas.style.display = checkMensualidad.checked ? "block" : "none";
        }

        // Detectar cambio en el check
        checkMensualidad.addEventListener("change", toggleFechas);

        // Inicializar por si viene activado al cargar la vista
        toggleFechas();
    });




    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchBtn = document.getElementById('searchBtn');
        const toggleAdvancedBtn = document.getElementById('toggleAdvancedSearch');
        const advancedPanel = document.getElementById('advancedSearchPanel');
        const applyFiltersBtn = document.getElementById('applyAdvancedFilters');
        const clearFiltersBtn = document.getElementById('clearFilters');
        const table = document.getElementById('vehiclesTable');

        // Toggle panel de búsqueda avanzada
        toggleAdvancedBtn.addEventListener('click', function() {
            if (advancedPanel.style.display === 'none') {
                advancedPanel.style.display = 'block';
                toggleAdvancedBtn.innerHTML = '<i class="fas fa-filter"></i> Ocultar Filtros';
                toggleAdvancedBtn.classList.remove('btn-outline-secondary');
                toggleAdvancedBtn.classList.add('btn-secondary');
            } else {
                advancedPanel.style.display = 'none';
                toggleAdvancedBtn.innerHTML = '<i class="fas fa-filter"></i> Filtros Avanzados';
                toggleAdvancedBtn.classList.remove('btn-secondary');
                toggleAdvancedBtn.classList.add('btn-outline-secondary');
            }
        });

        // Búsqueda simple
        function performSimpleSearch() {
            const searchTerm = searchInput.value.toLowerCase();
            filterTable(searchTerm);
        }

        // Búsqueda avanzada
        function performAdvancedSearch() {
            const filters = {
                plate: document.getElementById('filterPlate').value.toLowerCase(),
                type: document.getElementById('filterType').value.toLowerCase(),
                brand: document.getElementById('filterBrand').value.toLowerCase(),
                model: document.getElementById('filterModel').value.toLowerCase(),
                color: document.getElementById('filterColor').value.toLowerCase(),
                zone: document.getElementById('filterZone').value.toLowerCase(),
                timeFrom: document.getElementById('filterTimeFrom').value,
                timeUntil: document.getElementById('filterTimeUntil').value
            };

            filterTableAdvanced(filters);
        }

        // Función de filtrado simple
        function filterTable(searchTerm) {
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let found = false;

                // Buscar en todas las celdas
                for (let j = 0; j < cells.length - 1; j++) { // -1 para excluir la columna de acciones
                    const cellText = cells[j].textContent || cells[j].innerText;
                    if (cellText.toLowerCase().includes(searchTerm)) {
                        found = true;
                        break;
                    }
                }

                row.style.display = found || searchTerm === '' ? '' : 'none';
            }
        }

        // Función de filtrado avanzado
        function filterTableAdvanced(filters) {
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                let show = true;

                // Filtrar por placa
                if (filters.plate && !cells[0].textContent.toLowerCase().includes(filters.plate)) {
                    show = false;
                }

                // Filtrar por tipo
                if (filters.type && !cells[1].textContent.toLowerCase().includes(filters.type)) {
                    show = false;
                }

                // Filtrar por marca/modelo
                if (filters.brand && !cells[2].textContent.toLowerCase().includes(filters.brand)) {
                    show = false;
                }

                if (filters.model && !cells[2].textContent.toLowerCase().includes(filters.model)) {
                    show = false;
                }

                // Filtrar por color
                if (filters.color && !cells[3].textContent.toLowerCase().includes(filters.color)) {
                    show = false;
                }

                // Filtrar por zona
                if (filters.zone && !cells[4].textContent.toLowerCase().includes(filters.zone)) {
                    show = false;
                }

                // Aquí puedes agregar lógica para filtrar por fechas si es necesario

                row.style.display = show ? '' : 'none';
            }
        }

        // Limpiar filtros
        function clearAllFilters() {
            searchInput.value = '';
            document.getElementById('filterPlate').value = '';
            document.getElementById('filterType').value = '';
            document.getElementById('filterBrand').value = '';
            document.getElementById('filterModel').value = '';
            document.getElementById('filterColor').value = '';
            document.getElementById('filterZone').value = '';
            document.getElementById('filterTimeFrom').value = '';
            document.getElementById('filterTimeUntil').value = '';

            // Mostrar todas las filas
            const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
            for (let i = 0; i < rows.length; i++) {
                rows[i].style.display = '';
            }
        }

        // Event listeners
        searchBtn.addEventListener('click', performSimpleSearch);
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSimpleSearch();
            }
        });
        searchInput.addEventListener('input', function() {
            if (this.value === '') {
                clearAllFilters();
            }
        });

        applyFiltersBtn.addEventListener('click', performAdvancedSearch);
        clearFiltersBtn.addEventListener('click', clearAllFilters);
    });
</script>
<!-- Modal para la factura -->
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 400px;">
        <div class="modal-content" style="width: 380px; margin: auto;">
            <div class="modal-header">
                <h5 class="modal-title">Factura de Entrada</h5>
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

<!-- Contenedor oculto para impresión -->
<div id="printableArea" style="display:none;"></div>

<script>
    function printDiv(divId) {
        var content = document.getElementById(divId).innerHTML;
        var myWindow = window.open('', '', 'width=800,height=600');
        myWindow.document.write(`
    <html>
    <head>
      <title>Factura</title>
      <style>
        body { text-align: center; font-family: Arial, sans-serif; }
      </style>
    </head>
    <body>${content}</body>
    </html>
  `);
        myWindow.document.close();
        myWindow.focus();
        myWindow.print();
        myWindow.close();
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


@endsection

@section('styles')
<style>
    /* Área imprimible */
    #printableArea {
        font-family: monospace, monospace;
        font-size: 12px;
        width: 80mm;
        margin: 0 auto;
        text-align: center;
    }

    /* Forzar que el contenido del modal esté centrado */
    #invoiceContent {
        display: block;
        margin-left: auto;
        margin-right: auto;
        text-align: center;
        width: fit-content;
    }

    .modal-body {
        display: flex;
        justify-content: center;
    }

    @media print {
        body {
            text-align: center;
        }

        #printableArea {
            display: block !important;
            margin: 0 auto;
        }
    }
</style>
@endsection


@section('scripts')
<script>
    document.getElementById('btnPrint').addEventListener('click', function() {
        var printContents = document.getElementById('invoiceContent').innerHTML;
        var originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;

        window.print(); // Esto abrirá directamente la ventana de impresión

        document.body.innerHTML = originalContents; // Restauramos la página
        location.reload(); // Recargamos para que el modal funcione correctamente después de imprimir
    });


    $(document).ready(function() {

        $(document).ready(function() {
            function openInvoiceModal(entryId) {
                $('#invoiceContent').html('<div class="text-muted">Cargando factura...</div>');
                const modal = new bootstrap.Modal(document.getElementById('invoiceModal'));
                modal.show();

                // URL generada dinámicamente
                const url = "{{ url('factura-html') }}/" + entryId;

                $.get(url, function(html) {
                    $('#invoiceContent').html(html);
                }).fail(function() {
                    $('#invoiceContent').html('<div class="text-danger">No se pudo cargar la factura.</div>');
                });
            }

            $(document).on('click', '.btn-imprimir', function() {
                const entryId = $(this).data('entry-id');
                openInvoiceModal(entryId);
            });
        });

        // Botón de imprimir dentro del modal
        $(document).on('click', '#btnPrint', function() {
            const printContents = $('#invoiceContent').html();
            const myWindow = window.open('', '', 'width=800,height=600');
            myWindow.document.write(`
            <html>
            <head>
                <title>Factura</title>
                <style>
                    body { text-align: center; font-family: Arial, sans-serif; }
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



    $(document).ready(function() {
        // Convertir a mayúsculas automáticamente
        $('.plate-input').on('input', function() {
            this.value = this.value.toUpperCase();
        });

        $('#entryForm').on('submit', function(e) {
            e.preventDefault();

            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();

            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Procesando...');

            $.ajax({
                url: '{{ route("parking.entry") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        $('#entryForm')[0].reset();

                        if (response.data.ticket_html) {
                            $('#invoiceContent').html(response.data.ticket_html);
                            $('#printableArea').html(response.data.ticket_html);

                            const modal = new bootstrap.Modal(document.getElementById('invoiceModal'));
                            modal.show();

                            // Recargar solo cuando el modal se cierre
                            $('#invoiceModal').one('hidden.bs.modal', function() {
                                location.reload();
                            });
                        }
                    } else {
                        showAlert('danger', response.message);
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    let message = 'Error al registrar entrada';

                    if (response && response.message) {
                        message = response.message;
                    } else if (response && response.errors) {
                        message = Object.values(response.errors).flat().join(', ');
                    }

                    showAlert('danger', message);
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        $('#exitForm').on('submit', function(e) {
            e.preventDefault();

            const submitBtn = $(this).find('button[type="submit"]');
            const originalText = submitBtn.html();

            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Procesando...');

            $.ajax({
                url: '{{ route("parking.exit") }}',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        $('#exitForm')[0].reset();

                        if (response.data && response.data.duration_minutes) {
                            const hours = Math.floor(response.data.duration_minutes / 60);
                            const minutes = response.data.duration_minutes % 60;
                            const duration = hours > 0 ? `${hours}h ${minutes}m` : `${minutes}m`;

                            showAlert('info', `Tiempo estacionado: ${duration}`);
                        }

                        setTimeout(() => location.reload(), 2000);
                    } else {
                        showAlert('danger', response.message);
                    }
                },
                error: function(xhr) {
                    const response = xhr.responseJSON;
                    let message = 'Error al registrar salida';

                    if (response && response.message) {
                        message = response.message;
                    } else if (response && response.errors) {
                        message = Object.values(response.errors).flat().join(', ');
                    }

                    showAlert('danger', message);
                },
                complete: function() {
                    submitBtn.prop('disabled', false).html(originalText);
                }
            });
        });

        function showAlert(type, message) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;

            $('.container').prepend(alertHtml);

            setTimeout(() => {
                $('.alert').fadeOut();
            }, 5000);
        }


        $('#type').on('change', function() {
            const tipoVehiculoId = $(this).val();

            if (!tipoVehiculoId) {
                $('#entry_space').html('<option value="">Seleccione un tipo de vehículo primero</option>');
                return;
            }

            $.ajax({
                url: '/espacios-disponibles/' + tipoVehiculoId,
                method: 'GET',
                success: function(data) {
                    if (data.length === 0) {
                        $('#entry_space').html('<option value="">No hay espacios disponibles</option>');
                        return;
                    }

                    let options = '<option value="">Seleccione un espacio</option>';
                    data.forEach(function(espacio) {
                        options += `<option value="${espacio.id}">Zona ${espacio.zona} - Espacio ${espacio.numero}</option>`;
                    });

                    $('#entry_space').html(options);
                },
                error: function() {
                    $('#entry_space').html('<option value="">Error al cargar espacios</option>');
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Manejar salida desde el botón de la tabla con confirmación
        $(document).on('click', '.btn-exit-table', function() {
            const plate = $(this).data('plate');
            const button = $(this);
            const originalText = button.html();

            // Mostrar spinner mientras se obtiene la información del vehículo
            button.prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-2"></i>Procesando...');

            // Llamar a la API para obtener la imagen y datos del vehículo
            $.get(`http://localhost:8000/api/vehicle/plate/generate?number=${plate}&region=bogotad`, function(data) {
                console.log(data);

                const plateImageUrl = data.image || `http://localhost:8000/api/vehicle/plate/generate?number=${plate}&region=bogotad`;
                const vehicleBrand = data.brand || 'Marca desconocida';
                const vehicleModel = data.model || 'Modelo desconocido';
                const vehicleType = data.type || 'Tipo desconocido';

                Swal.fire({
                    title: `¿Confirmar salida del vehículo?`,
                    html: `
                    <div style="text-align:center">
                        <img src="${plateImageUrl}" alt="Placa" style="max-width:300px; margin-bottom:10px;">
                        <p><strong>Placa:</strong> ${plate}</p>
                        <p><strong>Marca:</strong> ${vehicleBrand}</p>
                        <p><strong>Modelo:</strong> ${vehicleModel}</p>
                        <p><strong>Tipo:</strong> ${vehicleType}</p>
                    </div>
                `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, sacar vehículo',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Ejecutar salida si confirma
                        $.ajax({
                            url: '{{ route("parking.register-exit") }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                plate: plate
                            },
                            success: function(response) {
                                if (response.success) {
                                    // Mostrar el ticket de salida en el mismo modal que la entrada
                                    if (response.data.ticket_html) {
                                        $('#invoiceContent').html(response.data.ticket_html);

                                        // Cambiar el título del modal para indicar que es un ticket de salida
                                        $('#invoiceModal .modal-title').text('Ticket de Salida');

                                        // Mostrar el modal
                                        const modal = new bootstrap.Modal(document.getElementById('invoiceModal'));
                                        modal.show();

                                        // Mostrar alerta de éxito
                                        showAlert('success', response.message);

                                        // Recargar página cuando se cierre el modal
                                        $('#invoiceModal').one('hidden.bs.modal', function() {
                                            // Restaurar el título original del modal
                                            $('#invoiceModal .modal-title').text('Factura de Entrada');
                                            location.reload();
                                        });
                                    } else {
                                        // Si no hay ticket_html, mostrar resumen tradicional
                                        showExitSummary(response.data);
                                        showAlert('success', response.message);
                                        setTimeout(() => location.reload(), 3000);
                                    }
                                } else {
                                    showAlert('danger', response.message);
                                }
                            },
                            error: function(xhr) {
                                const response = xhr.responseJSON;
                                let message = 'Error al registrar salida';

                                if (response && response.message) {
                                    message = response.message;
                                } else if (response && response.errors) {
                                    message = Object.values(response.errors).flat().join(', ');
                                }

                                showAlert('danger', message);
                            },
                            complete: function() {
                                button.prop('disabled', false).html(originalText);
                            }
                        });
                    } else {
                        // Si cancela, restaurar botón
                        button.prop('disabled', false).html(originalText);
                    }
                });
            }).fail(function() {
                showAlert('danger', 'No se pudo obtener la información del vehículo.');
                button.prop('disabled', false).html(originalText);
            });
        });

        // Función para mostrar resumen de salida con costo (mantener como fallback)
        function showExitSummary(data) {
            const hours = Math.floor(data.duration_minutes / 60);
            const minutes = data.duration_minutes % 60;
            const duration = hours > 0 ? `${hours}h ${minutes}m` : `${minutes}m`;

            Swal.fire({
                title: '¡Salida Registrada!',
                html: `
                <div class="text-center">
                    <div class="mb-3">
                        <h4 class="text-success">Vehículo: ${data.vehicle.plate}</h4>
                    </div>
                    <div class="row text-start">
                        <div class="col-6">
                            <p><strong>Entrada:</strong><br>${formatDateTime(data.entry_time)}</p>
                            <p><strong>Salida:</strong><br>${formatDateTime(data.exit_time)}</p>
                            <p><strong>Duración:</strong><br>${duration}</p>
                        </div>
                        <div class="col-6">
                            <p><strong>Espacio:</strong><br>${data.parking_space.numero_espacio}</p>
                            <p><strong>Zona:</strong><br>${data.zona.nombre}</p>
                            <p><strong>Tarifa:</strong><br>${data.tarifa_aplicada}</p>
                        </div>
                    </div>
                    <hr>
                    <div class="alert alert-success">
                        <h3 class="mb-0">Total: $${formatCurrency(data.costo_total)}</h3>
                    </div>
                </div>
            `,
                icon: 'success',
                confirmButtonText: 'Aceptar'
            });
        }

        // Función para formatear fecha y hora
        function formatDateTime(dateString) {
            const date = new Date(dateString);
            return date.toLocaleString('es-CO', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        // Función para formatear moneda
        function formatCurrency(amount) {
            return new Intl.NumberFormat('es-CO').format(amount);
        }

        // Función para mostrar alertas bootstrap
        function showAlert(type, message) {
            const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
            $('.container').prepend(alertHtml);
            setTimeout(() => {
                $('.alert').fadeOut();
            }, 5000);
        }

        // Mejorar la función de impresión del modal
        $('#btnPrint').off('click').on('click', function() {
            const printContents = $('#invoiceContent').html();

            // Crear ventana de impresión optimizada
            const printWindow = window.open('', '', 'width=800,height=600,scrollbars=yes,resizable=yes');

            printWindow.document.write(`
            <html>
            <head>
                <title>Ticket - Impresión</title>
                <style>
                    body { 
                        font-family: Arial, sans-serif; 
                        text-align: center; 
                        margin: 20px;
                        font-size: 12px;
                    }
                    @media print {
                        body { margin: 0; }
                        .no-print { display: none !important; }
                    }
                    .ticket-content {
                        max-width: 300px;
                        margin: 0 auto;
                        border: 1px solid #ddd;
                        padding: 10px;
                    }
                </style>
            </head>
            <body>
                <div class="ticket-content">
                    ${printContents}
                </div>
            </body>
            </html>
        `);

            printWindow.document.close();
            printWindow.focus();

            // Esperar a que cargue completamente antes de imprimir
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 500);
        });
    });
</script>


@endsection