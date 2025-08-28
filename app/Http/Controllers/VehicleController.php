<?php

namespace App\Http\Controllers;

use App\Models\TipoVehiculo;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class VehicleController extends Controller
{
    public function index()
    {
        $tipos = TipoVehiculo::all();
        $vehicles = Vehicle::with(['owner', 'tipoVehiculo'])->paginate(20);

        return view('vehicles.index', compact('vehicles', 'tipos'));
    }

    public function show(Vehicle $vehicle)
    {
        $vehicle->load('entries.parkingSpace');
        return view('vehicles.show', compact('vehicle'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'plate' => ['required', 'string', 'unique:vehicles', 'regex:/^[A-Z]{3}[0-9]{2}[0-9A-Z]$/'],
            'tipo_vehiculo_id' => 'required|exists:tipo_vehiculos,id',
            'brand' => 'nullable|string|max:50',
            'model' => 'nullable|string|max:50',
            'color' => 'nullable|string|max:30',
            'owner_id' => 'nullable|exists:users,id'
        ]);

        $vehicle = new Vehicle();
        $vehicle->plate = $request->input('plate');
        $vehicle->tipo_vehiculo_id = $request->input('tipo_vehiculo_id');
        $vehicle->brand = $request->input('brand');
        $vehicle->model = $request->input('model');
        $vehicle->color = $request->input('color');
        $vehicle->owner_id = $request->input('owner_id');
        $vehicle->save();

        return response()->json($vehicle, 201);
    }

    public function update(Request $request, Vehicle $vehicle)
{
    $request->validate([
        'plate' => ['required', 'string', 'regex:/^[A-Z]{3}[0-9]{2}[0-9A-Z]$/', 'unique:vehicles,plate,' . $vehicle->id],
        'tipo_vehiculo_id' => 'required|exists:tipo_vehiculos,id',
        'brand' => 'nullable|string|max:50',
        'model' => 'nullable|string|max:50',
        'color' => 'nullable|string|max:30',
        'owner_id' => 'nullable|exists:users,id',

        'is_mensualidad' => 'nullable|boolean',
        'mensualidad_inicio' => 'nullable|date',
        'mensualidad_fin' => 'nullable|date|after_or_equal:mensualidad_inicio',
    ]);

    $vehicle->update([
        'plate' => $request->plate,
        'tipo_vehiculo_id' => $request->tipo_vehiculo_id,
        'brand' => $request->brand,
        'model' => $request->model,
        'color' => $request->color,
        'owner_id' => $request->owner_id,

        'is_mensualidad' => $request->has('is_mensualidad'),
        'mensualidad_inicio' => $request->mensualidad_inicio,
        'mensualidad_fin' => $request->mensualidad_fin,
    ]);

    return redirect()->route('vehicles.index')->with('success', 'Vehículo actualizado correctamente.');
}


    public function generatePlate(Request $request)
    {
        $number = strtoupper($request->query('number', 'AAA000'));

        $templatePath = public_path('images/gCOL1.png');

        if (!file_exists($templatePath)) {
            return response()->json(['error' => 'Plantilla de placa no encontrada'], 404);
        }

        // Usar la nueva sintaxis de Intervention 3.x
        $manager = new ImageManager(new Driver());
        $img = $manager->read($templatePath);

        // Añadir texto de la placa
        $img->text($number, $img->width() / 2, $img->height() * 0.45, function ($font) {
            $font->filename(public_path('fonts/arialbd.ttf'));
            $font->size(70);
            $font->color('#000000');
            $font->align('center');
            $font->valign('middle');
        });

        // Retornar como PNG
        return response($img->toPng())
            ->header('Content-Type', 'image/png');
    }
    function datavehicle($id)
    {
        $vehicle = Vehicle::with('tipoVehiculo')->where('plate',$id)->first();

        if (!$vehicle) {
            return response()->json(['error' => 'Vehículo no encontrado'], 404);
        }

        return response()->json([
            'id' => $vehicle->id,
            'plate' => $vehicle->plate,
            'brand' => $vehicle->marca->nombre ?? null,
            'model' => $vehicle->model,
            'color' => $vehicle->color,
            'type' => $vehicle->tipoVehiculo->nombre ?? null,
        ]);
    }
}
