<div style="font-family: monospace; width: 320px; border:1px solid #000; padding:10px; text-align:center;">

    <!-- Logo centrado -->
    <img src="{{ asset('images/logo.png') }}" alt="Logo Parqueadero" style="width:180px; height:auto; margin-bottom:15px;">

    <!-- Información de la empresa -->
    <p style="font-size:10px; margin:0;">Nit. 901075548</p>
    <p style="font-size:10px; margin:0;">Calle 11 No 7-98</p>
    <p style="font-size:10px; margin:0;">Horario de atención 7 AM a 7 PM</p>
    <p style="font-size:10px; margin:0 0 10px 0;">Domingos y festivos no hay atención</p>

    <hr style="border:1px dashed #000; margin:10px 0;">

    <table style="width:100%; font-size:12px; border-collapse: collapse; text-align:center;">

        <p style="font-size:15px; margin:5px 0;">
            <strong>Recibo Nº:</strong>  <strong>{{ str_pad($entrada->id ?? 0, 4, '0', STR_PAD_LEFT) }}</strong>
        </p>


        <!-- Información de entrada -->
        <tr>
            <td colspan="3" style="font-weight:bold; padding:5px 0; border-bottom:1px solid #ccc;">
                DATOS DE ENTRADA
            </td>
        </tr>

        <tr>
            <td><strong>Día:</strong> {{ $exitTime->format('d') }}</td>
            <td><strong>Mes:</strong> {{ $exitTime->format('m') }}</td>
            <td><strong>Año:</strong> {{ $exitTime->format('Y') }}</td>
        </tr>

        {{-- Placa --}}
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


        {{-- Hora de salida --}}
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
                {{ $durationHours }} hora(s) ({{ $remainingMinutes }} min)

            </td>
        </tr>
        <tr>
            <td colspan="3" style="padding:5px 0;">
                <strong>{{ $tarifaAplicada }}</strong>
            </td>
        </tr>
        <!-- <tr>
            <td colspan="3" style="font-size:16px; font-weight:bold; padding:5px 0; color:#d32f2f;">
                <strong>TOTAL A PAGAR: ${{ number_format($costoTotal, 0) }}</strong>
            </td>
        </tr> -->

        @if($entrada->vehicle?->is_mensualidad)
        <tr>
            <td colspan="3" style="padding:5px 0;">
                <strong>Mensualidad:</strong>
                {{ $entrada->vehicle?->mensualidad_inicio ? $entrada->vehicle->mensualidad_inicio->format('d/m/Y') : '-' }}
                hasta
                {{ $entrada->vehicle?->mensualidad_fin ? $entrada->vehicle->mensualidad_fin->format('d/m/Y') : '-' }}
            </td>
        </tr>
        @endif

        <tr>
           <td colspan="3" style="padding:10px 0;">
        <div style="text-align:center; 
                    font-size:16px; 
                    font-weight:bold; 
                    color:#000; 
                    border:1px dotted #999; 
                    padding:8px; 
                    display:inline-block;">
           TOTAL A PAGAR: {{ number_format($costoTotal, 0) }} $
        </div>
        </tr>

       <tr>
    <td colspan="3" style="padding:5px 0; text-align:center;">
        <form id="formUpdateExit" action="{{ route('vehicle-exit.update', $entrada->id) }}" method="POST" target="_blank">
            
        
        @csrf
            @method('PUT')

            <label for="costo_manual" style="font-size:12px; font-weight:bold;">
                Ingresar Costo Manual:
            </label><br>

            <input type="number" id="costo_manual" name="costo_manual"
                placeholder="Escriba el valor"
                value="{{ old('costo_manual', $entrada->costo_total) }}"
                style="width: 100%; padding:5px; font-size:14px; text-align:center; border:1px solid #aaa; border-radius:5px;">

            <br><br>
            <button  type="submit" 
                style="padding:8px 15px; background:#28a745; color:#fff; border:none; border-radius:5px; cursor:pointer;">
                Guardar y Actualizar
            </button>
        </form>
        
    </td>
</tr>
 <script>
        // Este script se ejecuta tan pronto como la página se carga
        window.onload = function() {
            // Lanza el diálogo de impresión
            window.print();
            
            // Cierra la ventana actual después de unos segundos para dar tiempo a la impresión
            setTimeout(function() {
                window.close();
            }, 1000); // 1000 milisegundos = 1 segundo
        };
    </script>

    </table>

    <p style="font-size:9px; text-align:center; margin-top:5px;">
        El vehículo ha sido entregado al portador de este recibo. No se aceptan reclamos posteriores por hurto, pérdida o daños causados por terceros o eventos fortuitos. Conserve este comprobante como constancia de salida.
    </p>

    {{-- QR Code --}}
    <div style="margin-top:10px; margin-bottom:10px;">
        <img src="data:image/png;base64,{{ $qr ?? '' }}" alt="QR Code" style="width:100px; height:100px; margin:5px;" />
    </div>


    <p style="font-size:10px; margin:5px 0; font-weight:bold;">
        Fecha de emisión: {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}
    </p>

</div>



