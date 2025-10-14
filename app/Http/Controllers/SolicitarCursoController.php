<?php

namespace App\Http\Controllers;

use App\Models\dnc;
use App\Models\Periodo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SolicitarCursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function admin_index(Request $request)
{
    if (Auth::id()) {
        $query = dnc::query();

        // Filtro por texto libre
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($subquery) use ($q) {
                $subquery->where('nombre', 'like', "%{$q}%")
                         ->orWhere('objetivo', 'like', "%{$q}%")
                         ->orWhere('instructor_propuesto', 'like', "%{$q}%")
                         ->orWhere('contacto_propuesto', 'like', "%{$q}%");
            });
        }

        // Filtro por prioridad
        if ($request->filled('prioridad')) {
            $query->where('prioridad', $request->prioridad);
        }

        // 🟢 Filtro por estatus
        if ($request->filled('estatus')) {
            $query->where('estatus', $request->estatus);
        }

        // 🏢 Filtro por departamento
        if ($request->filled('departamento')) {
            $query->where('departamento_id', $request->departamento);
        }

        // Contadores
        $totalSolicitudes = dnc::count();
        $totalFiltradas = $query->count();

        // Orden descendente
        $solicitarCursos = $query->orderBy('id', 'desc')->get();

        // Para el select de departamentos
        $departamentos = \App\Models\Departamento::all();

        return view('vistas.DNC.admin.index', compact(
            'solicitarCursos',
            'departamentos',
            'totalSolicitudes',
            'totalFiltradas'
        ));
    }
}



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('vistas.DNC.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'objetivo' => 'required',
            'instructor' => 'required',
            'contacto_instructor' => 'required',
            'participantes' => 'required|integer|min:1',
            'prioridad' => 'required',
            'origen' => 'required',
            'requerimientos' => 'required',
        ]);
        $solicitud = new dnc();
        $solicitud->user_id = auth()->user()->id;
        $solicitud->departamento_id = 1;
        $solicitud->nombre = $request->nombre;
        $solicitud->objetivo = $request->objetivo;
        $solicitud->instructor_propuesto = $request->instructor;
        $solicitud->contacto_propuesto = $request->contacto_instructor;
        $solicitud->num_participantes = $request->participantes;
        $solicitud->prioridad = $request->prioridad;
        $solicitud->origen = $request->origen;
        $solicitud->requerimientos = $request->requerimientos;



        $solicitud->save();
        return redirect(route('jefe_solicitarcursos.index'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $solicitarcurso = dnc::find($id);

        return view('vistas.dnc.show', compact('solicitarcurso'));
    }

    public function negar($id)
    {
        $solicitarcurso = dnc::find($id);
        $solicitarcurso->estatus = 1;
        $solicitarcurso->save();

        return redirect(route('admin_solicitarcursos.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        $solicitarcurso = dnc::find($id);

        $solicitarcurso->delete();
        return redirect(route('jefe_solicitarcursos.index'));
    }
}
