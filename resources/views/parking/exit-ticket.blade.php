<div style="font-family: monospace; width: 320px; border:1px solid #000; padding:10px; text-align:center;">

    <!-- Logo centrado -->
    <img src="{{ asset('images/logo.png') }}" alt="Logo Parqueadero" style="width:180px; height:auto; margin-bottom:15px;">

    <p style="font-size:10px; margin:0;">Nit. 901075548</p>
    <p style="font-size:10px; margin:0;">Calle 11 No 7-98</p>
    <p style="font-size:10px; margin:0;">Horario de atención 7 AM a 7 PM</p>
    <p style="font-size:10px; margin:0 0 10px 0;">Domingos y festivos no hay atención</p>

    <hr style="border:1px dashed #000; margin:10px 0;">

    <!-- TICKET DE SALIDA -->
    <p style="font-size:16px; margin:5px 0; font-weight:bold; color:#d32f2f;">
        TICKET DE SALIDA
    </p>

    <table style="width:100%; font-size:12px; border-collapse: collapse; text-align:center;">
        
        <p style="font-size:15px; margin:5px 0;">
            <strong>Recibo Nº:</strong> <strong>{{ str_pad($entrada->id ?? 0, 4, '0', STR_PAD_LEFT) }}</strong>
        </p>
        
        <!-- Información de entrada -->
        <tr>
            <td colspan="3" style="font-weight:bold; padding:5px 0; border-bottom:1px solid #ccc;">
                DATOS DE ENTRADA
            </td>
        </tr>
        <tr>
            <td><strong>Día:</strong> {{ $entrada->entry_time?->format('d') ?? 'N/A' }}</td>
            <td><strong>Mes:</strong> {{ $entrada->entry_time?->format('m') ?? 'N/A' }}</td>
            <td><strong>Año:</strong> {{ $entrada->entry_time?->format('Y') ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td colspan="3">
                <strong>No. Placa Vehículo:</strong> 
                {{ $entrada->vehicle?->plate ? substr($entrada->vehicle->plate, 0, 3) . '-' . substr($entrada->vehicle->plate, 3) : 'N/A' }}
            </td>
        </tr>
        <tr>
            <td colspan="3"><strong>Hora De Ingreso:</strong> {{ $entrada->entry_time?->format('g:i A') ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td colspan="3">
                <strong>Casco:</strong> {{ $entrada->casco ? '✔' : '☐' }} &nbsp;&nbsp;
                <strong>Chaleco:</strong> {{ $entrada->chaleco ? '✔' : '☐' }} &nbsp;&nbsp;
                <strong>Llaves:</strong> {{ $entrada->llaves ? '✔' : '☐' }} &nbsp;&nbsp;
                <strong>Otro:</strong> {{ $entrada->otro ? '✔' : '☐' }}
                @if($entrada->otro && $entrada->otro_texto)
                    ({{ $entrada->otro_texto }})
                @endif
            </td>
        </tr>

        <!-- Información de salida -->
        <tr>
            <td colspan="3" style="font-weight:bold; padding:10px 0 5px 0; border-top:2px solid #000; border-bottom:1px solid #ccc;">
                DATOS DE SALIDA
            </td>
        </tr>
        <tr>
            <td><strong>Día:</strong> {{ $exitTime->format('d') }}</td>
            <td><strong>Mes:</strong> {{ $exitTime->format('m') }}</td>
            <td><strong>Año:</strong> {{ $exitTime->format('Y') }}</td>
        </tr>
        <tr>
            <td colspan="3"><strong>Hora De Salida:</strong> {{ $exitTime->format('g:i A') }}</td>
        </tr>
        
        <!-- Duración y costo -->
        <tr>
            <td colspan="3" style="padding:5px 0;">
                <strong>Tiempo Total:</strong> 
                @if($durationDays > 0)
                    {{ $durationDays }} día(s), 
                @endif
                {{ $durationHours }} hora(s) ({{ $durationMinutes }} min)
            </td>
        </tr>
        <tr>
            <td colspan="3" style="padding:5px 0;">
                <strong>{{ $tarifaAplicada }}</strong>
            </td>
        </tr>
        <tr>
            <td colspan="3" style="font-size:16px; font-weight:bold; padding:5px 0; color:#d32f2f;">
                <strong>TOTAL A PAGAR: ${{ number_format($costoTotal, 0) }}</strong>
            </td>
        </tr>
    </table>

    <p style="font-size:9px; text-align:center; margin-top:10px;">
        El vehículo se entregará al portador del recibo. No se acepta órdenes telefónicas ni escritas. Retirado el vehículo no se acepta ningún tipo de reclamo. No respondemos por objetos dejados en el vehículo. No respondemos por hurto. No respondemos por la pérdida, deterioro o daños ocurridos como consecuencia de incendio, terremoto, asonada, protestas u otras causas similares. El conductor debe asegurarse que el vehículo esté bien asegurado (cerrado). No respondemos por daños al vehículo causados por terceros.
    </p>

    <div style="margin-top:10px; margin-bottom:10px;">
        <img src="data:image/png;base64,{{ $qr ?? '' }}" alt="QR Code" style="width:100px; height:100px; margin:5px;" />
    </div>

    <p style="font-size:10px; margin:5px 0; font-weight:bold;">
        Fecha de emisión: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
    </p>

</div>