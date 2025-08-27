<?php

namespace App\Http\Controllers;

use App\Models\Tarifa;
use App\Models\Zona;
use App\Models\TipoVehiculo;
use Illuminate\Http\Request;

class TarifaController extends Controller
{
    public function index()
    {
        $tarifas = Tarifa::with(['zona', 'tipoVehiculo'])->paginate(10);
        return view('tarifas.index', compact('tarifas'));
    }

    public function create()
    {
        $zonas = Zona::all();
        $tiposVehiculo = TipoVehiculo::all();
        return view('tarifas.create', compact('zonas', 'tiposVehiculo'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'zona_id' => 'required|exists:zonas,id',
            'tipo_vehiculo_id' => 'required|exists:tipo_vehiculos,id',
            'fraccion_hora' => 'nullable|string',
            'hora_adicional' => 'nullable|string',
            'media_jornada' => 'nullable|string',
            'jornada_completa' => 'nullable|string',
            'mensualidad_diurna' => 'nullable|string',
        ]);

        // Convertir los precios al formato numérico válido para Laravel
        $data = $request->all();

        $data['fraccion_hora']      = $this->parseCurrency($data['fraccion_hora'] ?? null);
        $data['hora_adicional']     = $this->parseCurrency($data['hora_adicional'] ?? null);
        $data['media_jornada']      = $this->parseCurrency($data['media_jornada'] ?? null);
        $data['jornada_completa']   = $this->parseCurrency($data['jornada_completa'] ?? null);
        $data['mensualidad_diurna'] = $this->parseCurrency($data['mensualidad_diurna'] ?? null);

        $existe = Tarifa::where('zona_id', $data['zona_id'])
            ->where('tipo_vehiculo_id', $data['tipo_vehiculo_id'])
            ->exists();

        if ($existe) {
            return back()->withErrors(['duplicado' => 'Ya existe esta tarifa.'])->withInput();
        }

        Tarifa::create($data);

        return redirect()->route('tarifas.index')->with('success', 'Tarifa creada correctamente.');
    }


    public function edit(Tarifa $tarifa)
    {
        $zonas = Zona::all();
        $tiposVehiculo = TipoVehiculo::all();
        return view('tarifas.edit', compact('tarifa', 'zonas', 'tiposVehiculo'));
    }

    public function update(Request $request, Tarifa $tarifa)
    {
        $request->validate([
            'zona_id' => 'required|exists:zonas,id',
            'tipo_vehiculo_id' => 'required|exists:tipo_vehiculos,id',
            'fraccion_hora' => 'nullable|string',
            'hora_adicional' => 'nullable|string',
            'media_jornada' => 'nullable|string',
            'jornada_completa' => 'nullable|string',
            'mensualidad_diurna' => 'nullable|string',
        ]);

        $data = $request->all();


        $data['fraccion_hora']     = $this->parseCurrency($data['fraccion_hora']);
        $data['hora_adicional']    = $this->parseCurrency($data['hora_adicional']);
        $data['media_jornada']     = $this->parseCurrency($data['media_jornada']);
        $data['jornada_completa']  = $this->parseCurrency($data['jornada_completa']);
        $data['mensualidad_diurna'] = $this->parseCurrency($data['mensualidad_diurna']);

        $tarifa->update($data);

        return redirect()->route('tarifas.index')->with('success', 'Tarifa actualizada.');
    }


    private function parseCurrency($value)
    {
        if ($value === null) return null;

        // quita todo lo que no sea número
        $number = preg_replace('/[^\d]/', '', $value);

        return $number === '' ? null : (int) $number;
    }

    public function destroy(Tarifa $tarifa)
    {
        $tarifa->delete();
        return redirect()->route('tarifas.index')->with('success', 'Tarifa eliminada.');
    }
}
