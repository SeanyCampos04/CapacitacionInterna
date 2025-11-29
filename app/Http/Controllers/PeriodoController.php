<?php

namespace App\Http\Controllers;

use App\Models\Periodo;
use App\Models\Curso;
use Illuminate\Http\Request;

class PeriodoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $periodos = Periodo::get();
        return view('vistas.periodos.index', compact('periodos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vistas.periodos.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación de entrada
        $request->validate([
            'periodo' => 'required|string|max:255', // El periodo es obligatorio y debe ser una cadena
            'anio' => 'required|integer|digits:4', // El año es obligatorio, debe ser un número de 4 dígitos
            'trimestre' => 'required|integer|in:1,2,3,4', // El trimestre debe ser un valor entre 1 y 4
            'archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5048',
        ]);

        // Crear y guardar el nuevo periodo
        $periodos = new Periodo();
        $periodos->periodo = $request->periodo;
        $periodos->anio = $request->anio;
        $periodos->trimestre = $request->trimestre;

        // Guardar archivo si se subió
        if ($request->hasFile('archivo')) {
            $ruta = $request->file('archivo')->store('periodos/'.$periodos->id, 'public');
            $periodos->archivo_fondo = $ruta;
        }

        $periodos->save();


        // Redirigir al listado de periodos
        return redirect(route('periodos.index'))->with('success', 'Periodo creado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Periodo $periodo)
    {
        // Obtener los cursos asociados al periodo
        $cursos = Curso::where('periodo_id', $periodo->id)->get();
        return view('vistas.periodos.cursos.index', compact('periodo', 'cursos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Periodo $periodo)
    {
        return view('vistas.periodos.edit', compact('periodo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Periodo $periodo)
    {

        // Validación de entrada y archivo
        $request->validate([
            'periodo' => 'required|string|max:255', // El periodo es obligatorio y debe ser una cadena
            'anio' => 'required|integer|digits:4', // El año es obligatorio, debe ser un número de 4 dígitos
            'trimestre' => 'required|integer|in:1,2,3,4', // El trimestre debe ser un valor entre 1 y 4
            'archivo' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5048',
        ]);

        // Actualizar el periodo
        $periodo->periodo = $request->periodo;
        $periodo->anio = $request->anio;
        $periodo->trimestre = $request->trimestre;

        // Guardar archivo si se envía
        if ($request->hasFile('archivo')) {
            $ruta = $request->file('archivo')->store(
                "periodos/" . $periodo->id,
                "public"
            );
            $periodo->archivo_fondo = $ruta;
        }

        $periodo->save();

        // Redirigir al listado de periodos con mensaje de éxito
        return redirect(route('periodos.index'))->with('success', 'Periodo actualizado exitosamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Periodo $periodo)
    {
        // Verificar si hay cursos asociados antes de eliminar
        if ($periodo->cursos()->count() > 0) {
            return redirect(route('periodos.index'))->with('error', 'No se puede eliminar este periodo porque tiene cursos asociados.');
        }

        // Eliminar el periodo
        $periodo->delete();

        // Redirigir con mensaje de éxito
        return redirect(route('periodos.index'))->with('success', 'Periodo eliminado exitosamente.');
    }
}
