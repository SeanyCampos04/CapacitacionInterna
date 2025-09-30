<?php

namespace App\Http\Controllers;

use Carbon\Carbon;

use App\Models\Curso;
use App\Models\Periodo;
use App\Models\Instructore;
use Illuminate\Support\Facades\Auth;

use App\Models\cursos_participante;
use App\Models\Departamento;
use App\Models\dnc;
use App\Models\User;
use Barryvdh\DomPDF\PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Psy\CodeCleaner\FunctionReturnInWriteContextPass;

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('q');

        // Cargar cursos con relaciones necesarias y aplicar búsqueda
        $cursos = Curso::with([
                'periodo',
                'instructores.user.datos_generales',
                'departamento',
                'cursos_participantes'
            ])
            ->when($search, function($query, $search) {
                $query->where('nombre', 'like', "%{$search}%")
                      ->orWhereHas('instructores.user.datos_generales', function($q) use ($search) {
                          $q->where('nombre', 'like', "%{$search}%")
                            ->orWhere('apellido_paterno', 'like', "%{$search}%")
                            ->orWhere('apellido_materno', 'like', "%{$search}%");
                      })
                      ->orWhereHas('departamento', function($q) use ($search) {
                          $q->where('nombre', 'like', "%{$search}%");
                      });
            })
            ->orderBy('id', 'desc')
            ->get();

        return view('vistas.cursos.index', compact('cursos', 'search'));
    }

    public function docente_index(Request $request)
    {
        $participante = $request->user()->participante;

        // Obtener cursos donde el docente no está inscrito
        $cursos = Curso::whereDoesntHave('cursos_participantes', function ($query) use ($participante) {
            $query->where('participante_id', $participante->id);
        })->get();
        return view('vistas.cursos.docente.index', compact('cursos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($id)
    {
        $solicitarcurso = dnc::find($id);
        $departamentos = Departamento::all();
        $instructores = User::whereHas('roles', function ($query) {
            $query->where('nombre', 'instructor');
        })->where('estatus', 1)
            ->get();
        $periodos = Periodo::orderBy('created_at')->get();

        return view('vistas.cursos.admin.create', compact('solicitarcurso', 'periodos', 'departamentos', 'instructores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación de formularios
        $request->validate([
            'nombre' => 'required',
            'fecha_inicio' => 'required|date',
            'fecha_final' => 'required|date|after_or_equal:fecha_inicio',
            'objetivo' => 'required',
            'modalidad' => 'required',
            'lugar' => 'required',
            'horario' => 'required',
            'duracion' => 'required|integer|min:1',
            'no_registro' => 'required',
            'periodo' => 'required',
            'tipo' => 'required',
            'clase' => 'required',
            'limite_participantes' => 'required',
            'es_tics' => 'sometimes|boolean',
            'es_tutorias' => 'sometimes|boolean',
            'instructores' => 'required|array',
            'instructores.*' => 'exists:instructores,id'
        ]);

        // Crear un nuevo curso y asignar los valores
        $curso = new Curso();
        $curso->nombre = $request->nombre;
        $curso->fdi = $request->fecha_inicio;
        $curso->fdf = $request->fecha_final;
        $curso->objetivo = $request->objetivo;
        $curso->modalidad = $request->modalidad;
        $curso->lugar = $request->lugar;
        $curso->horario = $request->horario;
        $curso->duracion = $request->duracion;
        $curso->no_registro = $request->no_registro;
        $curso->periodo_id = $request->periodo;
        $curso->tipo = $request->tipo;
        $curso->clase = $request->clase;
        $curso->limite_participantes = $request->limite_participantes;
        $curso->es_tics = $request->es_tics;
        $curso->es_tutorias = $request->es_tutorias;
        $curso->departamento_id = $request->departamento;

        // Guardar el curso en la base de datos
        $curso->save();

        // Asocia los instructores seleccionados al curso
        $curso->instructores()->sync($request->input('instructores'));

        $solicitarcurso = dnc::find($request->dncId);
        $solicitarcurso->estatus = 2;
        $solicitarcurso->save();

        // Redireccionar a la vista de cursos
        return redirect(route('cursos.show', $curso->id))->with('success', 'Curso registrado exitosamente.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $curso = Curso::find($id);
        $ParticipantesInscritos = cursos_participante::where('curso_id', $id)->with(['participante'])->get();
        $ParticipantesOrdenados = $ParticipantesInscritos->sortBy(function ($cursoParticipante) {
            return $cursoParticipante->participante->user->datos_generales->apellido_paterno;
        });
        return view('vistas.cursos.show', compact('curso', 'ParticipantesOrdenados'));
    }

    // ... el resto de tus métodos (edit, update, destroy, etc.) se mantienen igual
}
