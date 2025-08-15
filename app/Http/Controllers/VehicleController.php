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

    public function generatePlate(Request $request)
    {
        $number = strtoupper($request->query('number', 'AAA000'));
        $region = strtoupper($request->query('region', 'BOGOTA'));

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

        // Añadir ciudad
        $img->text($region, $img->width() / 2, $img->height() * 0.85, function ($font) {
            $font->filename(public_path('fonts/arialbd.ttf'));
            $font->size(30);
            $font->color('#000000');
            $font->align('center');
            $font->valign('middle');
        });

        // Retornar como PNG
        return response($img->toPng())
            ->header('Content-Type', 'image/png');
    }
}
