<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo de Salida - {{ $vehicle->plate }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            background: white;
        }
        
        .receipt-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background: white;
        }
        
        .header {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid #007bff;
        }
        
        .header h1 {
            color: #007bff;
            font-size: 24px;
            margin-bottom: 8px;
            font-weight: bold;
        }
        
        .header h2 {
            color: #666;
            font-size: 16px;
            font-weight: normal;
        }
        
        .receipt-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 20px;
        }
        
        .info-section {
            background: white;
            padding: 15px;
            border-radius: 6px;
            border: 1px solid #dee2e6;
        }
        
        .info-section h3 {
            color: #495057;
            font-size: 14px;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-weight: bold;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 5px;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 4px 0;
        }
        
        .info-row:last-child {
            margin-bottom: 0;
        }
        
        .info-label {
            font-weight: bold;
            color: #666;
        }
        
        .info-value {
            color: #333;
        }
        
        .vehicle-info {
            background: #e3f2fd;
            border-left-color: #2196f3;
        }
        
        .parking-info {
            background: #fff3e0;
            border-left-color: #ff9800;
        }
        
        .time-info {
            background: #f3e5f5;
            border-left-color: #9c27b0;
        }
        
        .cost-info {
            background: #e8f5e8;
            border-left-color: #4caf50;
        }
        
        .cost-summary {
            background: #28a745;
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        
        .cost-summary h3 {
            font-size: 18px;
            margin-bottom: 10px;
        }
        
        .total-amount {
            font-size: 32px;
            font-weight: bold;
            margin: 10px 0;
        }
        
        .tarifa-detail {
            font-size: 14px;
            opacity: 0.9;
            margin-top: 8px;
        }
        
        .qr-section {
            text-align: center;
            margin: 25px 0;
            padding: 20px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .qr-code {
            margin: 15px 0;
        }
        
        .qr-code img {
            max-width: 120px;
            height: auto;
        }
        
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #666;
            font-size: 11px;
        }
        
        .timestamp {
            background: #6c757d;
            color: white;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 11px;
            display: inline-block;
            margin-top: 10px;
        }
        
        .duration-highlight {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            padding: 12px;
            border-radius: 6px;
            margin: 15px 0;
            text-align: center;
        }
        
        .duration-highlight .duration-text {
            font-size: 16px;
            font-weight: bold;
            color: #856404;
        }
        
        @media print {
            body {
                font-size: 11px;
            }
            
            .receipt-container {
                max-width: none;
                margin: 0;
                padding: 10px;
            }
            
            .cost-summary {
                background: #333 !important;
                -webkit-print-color-adjust: exact;
                color-adjust: exact;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="header">
            <h1>RECIBO DE SALIDA</h1>
            <h2>Sistema de Parqueaderos</h2>
        </div>

        <div class="receipt-info">
            <strong>Recibo No:</strong> {{ $entry->id }}<br>
            <strong>Código de Ticket:</strong> {{ $entry->ticket_code }}<br>
            <strong>Fecha de Emisión:</strong> {{ $fechaHoy }}
        </div>

        <div class="info-grid">
            <div class="info-section vehicle-info">
                <h3>Información del Vehículo</h3>
                <div class="info-row">
                    <span class="info-label">Placa:</span>
                    <span class="info-value">{{ $vehicle->plate }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Tipo:</span>
                    <span class="info-value">{{ $vehicle->tipoVehiculo->nombre ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Marca:</span>
                    <span class="info-value">{{ $vehicle->marca->nombre ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Modelo:</span>
                    <span class="info-value">{{ $vehicle->model ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Color:</span>
                    <span class="info-value">{{ $vehicle->color ?? 'N/A' }}</span>
                </div>
            </div>

            <div class="info-section parking-info">
                <h3>Información de Parqueo</h3>
                <div class="info-row">
                    <span class="info-label">Espacio:</span>
                    <span class="info-value">{{ $entry->espacio->numero_espacio ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Zona:</span>
                    <span class="info-value">{{ $entry->espacio->zona->nombre ?? 'N/A' }}</span>
                </div>
                @if($entry->casco)
                <div class="info-row">
                    <span class="info-label">Casco:</span>
                    <span class="info-value">✓ Sí</span>
                </div>
                @endif
                @if($entry->chaleco)
                <div class="info-row">
                    <span class="info-label">Chaleco:</span>
                    <span class="info-value">✓ Sí</span>
                </div>
                @endif
                @if($entry->llaves)
                <div class="info-row">
                    <span class="info-label">Llaves:</span>
                    <span class="info-value">✓ Sí</span>
                </div>
                @endif
                @if($entry->otro && $entry->otro_texto)
                <div class="info-row">
                    <span class="info-label">Otro:</span>
                    <span class="info-value">{{ $entry->otro_texto }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="info-grid">
            <div class="info-section time-info">
                <h3>Tiempos</h3>
                <div class="info-row">
                    <span class="info-label">Entrada:</span>
                    <span class="info-value">{{ $entryTime->format('d/m/Y H:i:s') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Salida:</span>
                    <span class="info-value">{{ $exitTime->format('d/m/Y H:i:s') }}</span>
                </div>
            </div>

            <div class="info-section cost-info">
                <h3>Detalle de Tiempo</h3>
                <div class="info-row">
                    <span class="info-label">Minutos:</span>
                    <span class="info-value">{{ $durationMinutes }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Horas:</span>
                    <span class="info-value">{{ $durationHours }}</span>
                </div>
                @if($durationDays > 0)
                <div class="info-row">
                    <span class="info-label">Días:</span>
                    <span class="info-value">{{ $durationDays }}</span>
                </div>
                @endif
            </div>
        </div>

        <div class="duration-highlight">
            <div class="duration-text">
                Tiempo Total: {{ $durationDays > 0 ? $durationDays . ' día(s) y ' : '' }}{{ $durationHours }} hora(s) ({{ $durationMinutes }} minutos)
            </div>
        </div>

        <div class="cost-summary">
            <h3>TOTAL A PAGAR</h3>
            <div class="total-amount">${{ number_format($costoTotal, 0) }}</div>
            <div class="tarifa-detail">{{ $tarifaAplicada }}</div>
        </div>

        <div class="qr-section">
            <h3>Código QR - Recibo</h3>
            <div class="qr-code">
                <img src="data:image/png;base64,{{ $qr }}" alt="Código QR del recibo">
            </div>
            <p>Escanea este código para verificar la información del recibo</p>
        </div>

        <div class="footer">
            <p><strong>¡Gracias por utilizar nuestro servicio!</strong></p>
            <p>Conserve este recibo como comprobante de pago</p>
            <div class="timestamp">
                Generado el {{ $fechaHoy }}
            </div>
        </div>
    </div>
</body>
</html>