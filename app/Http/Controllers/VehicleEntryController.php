<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleEntry;
use App\Models\Marca;
use App\Models\Tarifa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use App\Models\Compatibilidades;
use App\Models\Espacios_parqueadero;
use App\Models\TipoVehiculo;
use Barryvdh\DomPDF\Facade\Pdf;

class VehicleEntryController extends Controller
{
    /**
     * Mostrar dashboard
     */
    public function index()
    {
        $activeEntries = VehicleEntry::with(['vehicle.tipoVehiculo', 'espacio.zona'])
            ->whereNull('exit_time')
            ->latest('entry_time')
            ->get();

        $ocupados = VehicleEntry::whereNull('exit_time')->pluck('espacio_id');
        $availableSpaces = Espacios_parqueadero::whereNotIn('id', $ocupados)->count();
        $espaciosDisponibles = Espacios_parqueadero::whereNotIn('id', $ocupados)->with('zona')->get();
        $totalSpaces = Espacios_parqueadero::count();

        $tipos = TipoVehiculo::all();
        $marcas = Marca::all();

        return view('parking.dashboard', compact(
            'activeEntries',
            'availableSpaces',
            'totalSpaces',
            'tipos',
            'espaciosDisponibles',
            'marcas'
        ));
    }

    /**
     * Registrar entrada de vehículo
     */
    public function registerEntry(Request $request)
    {
        $request->validate([
            'plate' => ['required', 'string', 'regex:/^[A-Z]{3}[0-9]{2}[0-9A-Z]$/'],
            'tipo_vehiculo_id' => 'required|exists:tipo_vehiculos,id',
            'marca_id' => 'nullable|exists:marcas,id',
            'model' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:30',
            'is_mensualidad'      => 'nullable|boolean',
            'mensualidad_inicio'  => 'nullable|date',
            'mensualidad_fin'     => 'nullable|date|after_or_equal:mensualidad_inicio',
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $plate = strtoupper($request->plate);

                // Buscar o crear vehículo
                $vehicle = Vehicle::firstOrCreate(
                    ['plate' => $plate],
                    [
                        'tipo_vehiculo_id' => $request->tipo_vehiculo_id,
                        'brand' => $request->marca_id,
                        'model' => $request->model,
                        'color' => $request->color,

                    ]
                );

                $vehicleData = [
                    'tipo_vehiculo_id' => $request->tipo_vehiculo_id,
                    'brand' => $request->marca_id,
                    'model' => $request->model,
                    'color' => $request->color,
                ];

                if ($request->filled('is_mensualidad')) {
                    $vehicleData['is_mensualidad'] = $request->boolean('is_mensualidad');
                }

                if ($request->filled('mensualidad_inicio')) {
                    $vehicleData['mensualidad_inicio'] = Carbon::parse($request->mensualidad_inicio);
                }

                if ($request->filled('mensualidad_fin')) {
                    $vehicleData['mensualidad_fin'] = Carbon::parse($request->mensualidad_fin);
                }

                $vehicle->update($vehicleData);


                // Verificar mensualidad vigente
                $now = Carbon::now();
                $mensualidadVigente = $vehicle->is_mensualidad &&
                    $vehicle->mensualidad_inicio &&
                    $vehicle->mensualidad_fin &&
                    $now->between($vehicle->mensualidad_inicio, $vehicle->mensualidad_fin);

                // Verificar si ya está estacionado
                if ($vehicle->isParked()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El vehículo ya se encuentra estacionado'
                    ], 400);
                }

                // Buscar espacio compatible
                $tipo = TipoVehiculo::find($request->tipo_vehiculo_id);
                $zonasCompatibles = Compatibilidades::where('tipo_vehiculo_id', $tipo->id)->pluck('zona_id');
                $espaciosOcupados = VehicleEntry::whereNull('exit_time')->pluck('espacio_id');

                $espacio = Espacios_parqueadero::whereNotIn('id', $espaciosOcupados)
                    ->whereIn('zona_id', $zonasCompatibles)
                    ->first();

                if (!$espacio) {
                    return response()->json([
                        'success' => false,
                        'message' => 'No hay espacios disponibles para este tipo de vehículo'
                    ], 400);
                }

                // Elementos opcionales
                $casco = $request->has('casco');
                $chaleco = $request->has('chaleco');
                $llaves = $request->has('llaves');
                $otro = $request->has('otro');
                $otro_texto = $otro ? $request->input('otro_texto') : null;

                // Crear entrada
                $entry = VehicleEntry::create([
                    'vehicle_id' => $vehicle->id,
                    'espacio_id' => $espacio->id,
                    'brand' => $request->marca_id,
                    'entry_time' => Carbon::now(),
                    'ticket_code' => (string) Str::uuid(),
                    'casco' => $casco,
                    'chaleco' => $chaleco,
                    'llaves' => $llaves,
                    'otro' => $otro,
                    'otro_texto' => $otro_texto,
                    'tarifa_aplicada'  => $mensualidadVigente
                        ? "Mensualidad vigente ({$vehicle->mensualidad_inicio->format('d/m/Y')} - {$vehicle->mensualidad_fin->format('d/m/Y')})"
                        : null,
                    'costo_total'      => $mensualidadVigente ? 0 : null,
                ]);
                // if (!$entry) {
                //     throw new \Exception("No se pudo crear el registro de entrada.");
                // }

                // Generar QR
                $plateFormatted = preg_replace('/^([A-Za-z]+)(\d+)$/', '$1-$2', $vehicle->plate);

                $marcaNombre = 'N/A';
                if ($request->filled('marca_id')) {
                    $marca = Marca::find($request->marca_id);
                    if ($marca) {
                        $marcaNombre = $marca->nombre;
                    }
                }

                $qrData = "Placa: {$plateFormatted}\n";
                $qrData .= "Tipo: {$vehicle->tipoVehiculo->nombre}\n";
                $qrData .= "Marca/Modelo: {$marcaNombre} " . ($vehicle->model ?? 'N/A') . "\n";
                $qrData .= "Fecha - Hora Entrada: " . $entry->entry_time->format('Y-m-d H:i:s');

                $qr = base64_encode(
                    QrCode::format('png')->size(150)->generate($qrData)
                );

                // Renderizar ticket
                $ticketHtml = view('parking.ticket', [
                    'entrada' => $entry,
                    'qr' => $qr
                ])->render();

                return response()->json([
                    'success' => true,
                    'message' => 'Entrada registrada exitosamente',
                    'data' => [
                        'vehicle' => $vehicle,
                        'parking_space' => $espacio,
                        'entry' => $entry,
                        'ticket_html' => $ticketHtml,
                    ]
                ]);
            });
        } catch (\Exception $e) {
            Log::error("Error al registrar entrada: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al registrar entrada: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Registrar salida de vehículo
     */
    public function registerExit(Request $request)
    {
        $request->validate([
            'plate' => ['required', 'string', 'regex:/^[A-Z]{3}[0-9]{2}[0-9A-Z]$/']
        ]);

        try {
            return DB::transaction(function () use ($request) {
                $plate = strtoupper($request->plate);

                $vehicle = Vehicle::where('plate', $plate)->first();
                if (!$vehicle) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Vehículo no encontrado'
                    ], 404);
                }

                $entry = $vehicle->getCurrentEntry();
                if (!$entry) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El vehículo no se encuentra estacionado'
                    ], 400);
                }

                $exitTime = Carbon::now();
                $entryTime = $entry->entry_time;


                // Duración
                $durationMinutes = $entryTime->diffInMinutes($exitTime);
                $durationHours = floor($durationMinutes / 60);
                $remainingMinutes = $durationMinutes % 60;
                $durationDays = $entryTime->diffInDays($exitTime);

                // Tarifa
                $tarifa = Tarifa::where('zona_id', $entry->espacio->zona_id)
                    ->where('tipo_vehiculo_id', $vehicle->tipo_vehiculo_id)
                    ->first();

                $costoTotal = 0;
                $tarifaAplicada = 'Sin tarifa configurada';
                // Revisar si el vehículo tiene mensualidad activa
                $isMensualidadActiva = $vehicle->is_mensualidad
                    && $vehicle->mensualidad_inicio
                    && $vehicle->mensualidad_fin
                    && now()->between($vehicle->mensualidad_inicio, $vehicle->mensualidad_fin);

                if ($isMensualidadActiva) {
                    $mensualidadPagada = VehicleEntry::where('vehicle_id', $vehicle->id)
                        ->whereBetween('entry_time', [$vehicle->mensualidad_inicio, $vehicle->mensualidad_fin])
                        ->where('tarifa_aplicada', 'Mensualidad')
                        ->exists();

                    if (!$mensualidadPagada) {
                        $tarifaMensualidad = Tarifa::where('tipo_vehiculo_id', $vehicle->tipo_vehiculo_id)->where('zona_id', $entry->espacio->zona_id)->first();
                        $costoTotal = $tarifaMensualidad ? $tarifaMensualidad->mensualidad_diurna : 0;
                        $tarifaAplicada = "Mensualidad";
                    } else {
                        $costoTotal = 0;
                        $tarifaAplicada = "MENSUALIDAD VIGENTE";
                    }
                } elseif ($tarifa) {
                    [$costoTotal, $tarifaAplicada] = $tarifa->calcularCosto($entryTime, $exitTime, $vehicle->tipoVehiculo, $entry->espacio->zona_id);
                }


                // Actualizar entrada
                $entry->update([
                    'exit_time' => $exitTime,
                    'costo_total' => $costoTotal,
                    'duracion_minutos' => $durationMinutes,
                    'tarifa_aplicada' => $tarifaAplicada
                ]);

                // QR salida
                $plateFormatted = preg_replace('/^([A-Za-z]+)(\d+)$/', '$1-$2', $vehicle->plate);
                $marcaNombre = $vehicle->marca?->nombre ?? 'N/A';

                $qrData = "TICKET DE SALIDA\n";
                $qrData .= "Placa: {$plateFormatted}\n";
                $qrData .= "Tipo: {$vehicle->tipoVehiculo->nombre}\n";
                $qrData .= "Marca: {$marcaNombre}\n";
                $qrData .= "Entrada: " . $entryTime->format('d/m/Y H:i') . "\n";
                $qrData .= "Salida: " . $exitTime->format('d/m/Y H:i') . "\n";
                $qrData .= "Total: $" . number_format($costoTotal, 0);

                $qr = base64_encode(
                    QrCode::format('png')->size(150)->generate($qrData)
                );

                // Datos ticket salida
                $ticketData = [
                    'entrada' => $entry->fresh(['vehicle.tipoVehiculo', 'vehicle.marca', 'espacio.zona']),
                    'vehicle' => $vehicle,
                    'entryTime' => $entryTime,
                    'exitTime' => $exitTime,
                    'durationMinutes' => $durationMinutes,
                    'durationHours' => $durationHours,
                    'durationDays' => $durationDays,
                    'costoTotal' => $costoTotal,
                    'tarifaAplicada' => $tarifaAplicada,
                    'tarifa' => $tarifa,
                    'remainingMinutes' => $remainingMinutes,
                    'qr' => $qr
                ];

                $ticketHtml = view('parking.exit-ticket', $ticketData)->render();
                $pdf = Pdf::loadView('parking.exit-ticket', $ticketData)->setPaper('A4', 'portrait');

                $pdfBase64 = base64_encode($pdf->output());

                return response()->json([
                    'success' => true,
                    'message' => 'Salida registrada exitosamente',
                    'data' => [
                        'vehicle' => $vehicle,
                        'entry_time' => $entryTime,
                        'exit_time' => $exitTime,
                        'duration_minutes' => $durationMinutes,
                        'duration_hours' => $durationHours,
                        'duration_days' => $durationDays,
                        'costo_total' => $costoTotal,
                        'tarifa_aplicada' => $tarifaAplicada,
                        'parking_space' => $entry->espacio,
                        'zona' => $entry->espacio->zona,
                        'ticket_html' => $ticketHtml,
                        'pdf_base64' => $pdfBase64,
                        'filename' => "ticket_salida_{$plate}_{$exitTime->format('YmdHis')}.pdf"
                    ]
                ]);
            });
        } catch (\Exception $e) {
            Log::error("Error al registrar salida: " . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error al registrar salida: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generar PDF de recibo de salida
     */
    public function generateExitReceiptPdf($entryId)
    {
        try {
            $entry = VehicleEntry::with(['vehicle.tipoVehiculo', 'vehicle.marca', 'espacio.zona'])
                ->findOrFail($entryId);

            if (!$entry->exit_time) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta entrada no tiene registrada una salida'
                ], 400);
            }

            $durationMinutes = $entry->entry_time->diffInMinutes($entry->exit_time);
            $durationHours = ceil($durationMinutes / 60);
            $durationDays = $entry->entry_time->diffInDays($entry->exit_time);

            $reciboData = [
                'entry' => $entry,
                'vehicle' => $entry->vehicle,
                'entryTime' => $entry->entry_time,
                'exitTime' => $entry->exit_time,
                'durationMinutes' => $durationMinutes,
                'durationHours' => $durationHours,
                'durationDays' => $durationDays,
                'costoTotal' => $entry->costo_total ?? 0,
                'tarifaAplicada' => $entry->tarifa_aplicada ?? 'No disponible',
                'fechaHoy' => Carbon::now()->format('d/m/Y H:i:s')
            ];

            $plateFormatted = preg_replace('/^([A-Za-z]+)(\d+)$/', '$1-$2', $entry->vehicle->plate);
            $marcaNombre = $entry->vehicle->marca?->nombre ?? 'N/A';

            $qrData = "RECIBO DE SALIDA\n";
            $qrData .= "Placa: {$plateFormatted}\n";
            $qrData .= "Entrada: " . $entry->entry_time->format('d/m/Y H:i') . "\n";
            $qrData .= "Salida: " . $entry->exit_time->format('d/m/Y H:i') . "\n";
            $qrData .= "Total: $" . number_format($entry->costo_total ?? 0, 0);

            $qr = base64_encode(
                QrCode::format('png')->size(120)->generate($qrData)
            );

            $reciboData['qr'] = $qr;

            $pdf = Pdf::loadView('parking.exit-receipt', $reciboData);
            $pdf->setPaper('A4', 'portrait');

            $filename = "recibo_salida_{$entry->vehicle->plate}_{$entry->exit_time->format('YmdHis')}.pdf";

            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error("Error al generar PDF de recibo: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error al generar el recibo: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateExit(Request $request, $id)
    {
        $request->validate([
            'costo_manual' => ['nullable', 'numeric', 'min:0']
        ]);

        try {
            return DB::transaction(function () use ($request, $id) {
                $entry = VehicleEntry::with(['vehicle.tipoVehiculo', 'vehicle.marca', 'espacio.zona'])
                    ->findOrFail($id);

                if (!$entry->exit_time) {
                    return redirect()->back()->with('error', 'Esta entrada aún no tiene salida registrada');
                }

                //Si se mandó costo manual, sobrescribe el costo total
                if ($request->filled('costo_manual')) {
                    $entry->costo_total = (float) $request->costo_manual;
                    $entry->tarifa_aplicada = "Costo manual";
                    $entry->save();
                }

                // Recargar datos para el recibo
                $vehicle = $entry->vehicle;
                $entryTime = $entry->entry_time;
                $exitTime = $entry->exit_time;
                $durationMinutes = $entryTime->diffInMinutes($exitTime);
                $durationHours = floor($durationMinutes / 60);
                $remainingMinutes = $durationMinutes % 60;
                $durationDays = $entryTime->diffInDays($exitTime);

                // QR actualizado
                $plateFormatted = preg_replace('/^([A-Za-z]+)(\d+)$/', '$1-$2', $vehicle->plate);
                $marcaNombre = $vehicle->marca?->nombre ?? 'N/A';

                $qrData = "RECIBO ACTUALIZADO\n";
                $qrData .= "Placa: {$plateFormatted}\n";
                $qrData .= "Tipo: {$vehicle->tipoVehiculo->nombre}\n";
                $qrData .= "Marca: {$marcaNombre}\n";
                $qrData .= "Entrada: " . $entryTime->format('d/m/Y H:i') . "\n";
                $qrData .= "Salida: " . $exitTime->format('d/m/Y H:i') . "\n";
                $qrData .= "Total: $" . number_format($entry->costo_total, 0);

                $qr = base64_encode(
                    QrCode::format('png')->size(150)->generate($qrData)
                );

                // Datos para la vista
                $ticketData = [
                    'entrada' => $entry->fresh(['vehicle.tipoVehiculo', 'vehicle.marca', 'espacio.zona']),
                    'vehicle' => $vehicle,
                    'entryTime' => $entryTime,
                    'exitTime' => $exitTime,
                    'durationMinutes' => $durationMinutes,
                    'durationHours' => $durationHours,
                    'durationDays' => $durationDays,
                    'costoTotal' => $entry->costo_total,
                    'tarifaAplicada' => $entry->tarifa_aplicada,
                    'remainingMinutes' => $remainingMinutes,
                    'qr' => $qr
                ];

                return view('parking.exit-ticket', $ticketData)->with('success', 'Recibo actualizado correctamente');
            });
        } catch (\Exception $e) {
            Log::error("Error al actualizar salida: " . $e->getMessage());
            return redirect()->back()->with('error', 'Error al actualizar salida: ' . $e->getMessage());
        }
    }


    /**
     * Historial de entradas
     */
    public function history()
    {
        $entries = VehicleEntry::with(['vehicle.tipoVehiculo', 'espacio.zona'])
            ->orderBy('entry_time', 'desc')
            ->paginate(20);

        return view('parking.history', compact('entries'));
    }

    /**
     * Espacios disponibles por tipo de vehículo
     */
    public function espaciosDisponibles($tipoVehiculoId)
    {
        $espacios = DB::table('espacios_parqueadero')
            ->join('compatibilidades', 'espacios_parqueadero.zona_id', '=', 'compatibilidades.zona_id')
            ->join('zonas', 'espacios_parqueadero.zona_id', '=', 'zonas.id')
            ->leftJoin('vehicle_entries', function ($join) {
                $join->on('espacios_parqueadero.id', '=', 'vehicle_entries.espacio_id')
                    ->whereNull('vehicle_entries.exit_time');
            })
            ->where('compatibilidades.tipo_vehiculo_id', $tipoVehiculoId)
            ->whereNull('vehicle_entries.id')
            ->select(
                'espacios_parqueadero.id',
                'espacios_parqueadero.numero_espacio as numero',
                'zonas.nombre as zona'
            )
            ->get();

        return response()->json($espacios);
    }

    /**
     * Estado general de los espacios
     */
    public function estadoEspacios()
    {
        $espacios = Espacios_parqueadero::with('zona')->get();
        return view('parking.spaces', compact('espacios'));
    }

    /**
     * Generar HTML del ticket de ingreso
     */
    public function invoiceHtml($id)
    {
        $entrada = VehicleEntry::with(['vehicle.tipoVehiculo', 'espacio.zona', 'vehicle.marca'])
            ->findOrFail($id);

        $plateFormatted = $entrada->vehicle?->plate
            ? preg_replace('/^([A-Za-z]+)(\d+)$/', '$1-$2', $entrada->vehicle->plate)
            : 'N/A';

        $marcaNombre = $entrada->vehicle?->marca?->nombre ?? 'N/A';

        $qrData = "Placa: {$plateFormatted}\n";
        $qrData .= "Tipo: " . ($entrada->vehicle?->tipoVehiculo?->nombre ?? 'N/A') . "\n";
        $qrData .= "Marca/Modelo: {$marcaNombre} " . ($entrada->vehicle?->model ?? 'N/A') . "\n";
        $qrData .= "Fecha - Hora Entrada: " . ($entrada->entry_time?->format('Y-m-d H:i:s') ?? 'N/A');

        $qr = base64_encode(
            QrCode::format('png')->size(150)->generate($qrData)
        );

        return view('parking.ticket', compact('entrada', 'qr'));
    }
}
