<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroCapacitacionesExt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class RegistroCapacitacionesExtController extends Controller
{
    public function store(Request $request)
    {
        // Validación de los datos
        $validatedData = $request->validate([
            'correo' => 'required|email',
            'nombre' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'tipo_capacitacion' => 'required|string',
            'nombre_capacitacion' => 'required|string|max:255',
            'anio' => 'required|numeric|digits:4',
            'organismo' => 'required|string|max:255',
            'horas' => 'required|integer|min:30',
            'evidencia' => 'required|file|mimes:pdf|max:1024',
            'fecha_inicio' => 'required|date',
            'fecha_termino' => 'required|date',
        ]);

        // Manejo del archivo
        if ($request->hasFile('evidencia')) {
            $file = $request->file('evidencia');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('evidencias', $filename, 'public');
            $validatedData['evidencia'] = $path;
        }

        // Almacenar los datos
        RegistroCapacitacionesExt::create($validatedData);

        // Redirigir al dashboard con mensaje de éxito
        return redirect()->route('externa.datos')->with('success', 'La capacitación se registró correctamente.');
    }

    public function index()
    {
        // Mostrar todas las capacitaciones para roles admin y CAD
        $capacitaciones = RegistroCapacitacionesExt::all();

        return view('externa.datos', compact('capacitaciones'));
    }

    public function mis_capacitaciones()
    {
        $user = auth()->user();

        $capacitaciones = RegistroCapacitacionesExt::where('correo', $user->email)->get();

        return view('externa.datos', compact('capacitaciones'));
    }

    public function destroy($id)
    {
        // Buscar la capacitación por ID
        $capacitacion = RegistroCapacitacionesExt::findOrFail($id);

        // Eliminar el archivo de evidencia del almacenamiento (si existe)
        if ($capacitacion->evidencia) {
            Storage::disk('public')->delete($capacitacion->evidencia);
        }

        // Eliminar el registro de la base de datos
        $capacitacion->delete();

        return redirect()->back()->with('success', 'Capacitación eliminada exitosamente');
    }

    public function filtrar(Request $request)
    {
        $query = DB::table('registrocapacitacionesext');

        // Aplicar filtro por tipo de capacitación si está seleccionado
        if ($request->filled('tipo_capacitacion')) {
            $query->where('tipo_capacitacion', $request->tipo_capacitacion);
        }

        // Aplicar filtro por año si está seleccionado
        if ($request->filled('anio')) {
            $query->where('anio', $request->anio);
        }

        $capacitaciones = $query->get();

        return view('externa.datos', ['capacitaciones' => $capacitaciones]);
    }

    public function create()
    {
        $userEmail = auth()->check() ? auth()->user()->email : null;
        return view('externa.formulario', compact('userEmail'));
    }

    public function actualizarStatus(Request $request, $id)
    {
        // Validar el número de registro si es necesario
        $request->validate([
        'comentario' => 'nullable|string|max:255',
        ]);

        // Obtener la capacitación por ID
        $capacitacion = RegistroCapacitacionesExt::findOrFail($id);

        // Actualizar el campo status dependiendo del botón seleccionado
        if ($request->has('action') && $request->action === 'comentario') {
            $capacitacion->status = $request->input('comentario');
        } elseif ($request->has('rechazado') && $request->rechazado === 'rechazado') {
            $capacitacion->status = 'Rechazado';
        }

        // Guardar los cambios en la base de datos
        $capacitacion->save();

        // Redirigir con un mensaje de éxito
        return redirect()->back()->with('success', 'El estado de la capacitación se ha actualizado correctamente.');
    }

    public function actualizarFolio(Request $request, $id)
    {
        // Validar el folio si es necesario
        $request->validate([
        'numero_folio' => 'nullable|string|max:255',
        ]);

        // Obtener la capacitación por ID
        $capacitacion = RegistroCapacitacionesExt::findOrFail($id);

        // Actualizar el campo status dependiendo del botón seleccionado
        if ($request->has('action') && $request->action === 'numero_folio') {
            $capacitacion->folio = $request->input('numero_folio');
        } elseif ($request->has('rechazado') && $request->rechazado === 'rechazado') {
            $capacitacion->folio = 'Rechazado';
        }

        // Guardar los cambios en la base de datos
        $capacitacion->save();

        // Redirigir con un mensaje de éxito
        return redirect()->back()->with('success', 'El folio de la capacitación se ha actualizado correctamente.');
    }

    public function actualizarDatos(Request $request, $id)
    {
        // Validar los datos
        $request->validate([
            'comentario' => 'required|string|max:255',
            'numero_folio' => 'required|string|max:255',
        ]);

        // Buscar y actualizar la capacitación
        $capacitacion = RegistroCapacitacionesExt::findOrFail($id);
        $capacitacion->status = $request->comentario;
        $capacitacion->folio = $request->numero_folio;
        $capacitacion->save();

        // Redirigir con mensaje de éxito
        return redirect()->back()->with('success', 'Datos actualizados correctamente.');
    }
}
