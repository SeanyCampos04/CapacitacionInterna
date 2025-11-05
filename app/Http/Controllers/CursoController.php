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
    // Texto ingresado en el buscador
    $search = $request->input('q');

    // filtro por periodo
    $periodoFiltro = $request->input('periodo_id');

    // Obtener todos los periodos para el <select> en la vista
    $periodos = Periodo::orderByDesc('anio')->orderByDesc('trimestre')->get();

    // Obtener el periodo actual
    $periodo = Periodo::latest()->first();

    // Control de acceso por roles
    $user = $request->user();
    $allowedRoles = [
        'admin',
        'cad',
        'jefe_departamento',
        'instructor',
        'subdirector_academico'
    ];

    $canView = false;

    if ($user && method_exists($user, 'hasAnyRole')) {
        $canView = $user->hasAnyRole($allowedRoles);
    }

    if (!$canView && $user && method_exists($user, 'roles')) {
        try {
            $roleNames = $user->roles()->pluck('nombre')->map(fn($r) => strtolower($r))->toArray();
            foreach ($allowedRoles as $r) {
                if (in_array(strtolower($r), $roleNames)) {
                    $canView = true;
                    break;
                }
            }
        } catch (\Throwable $e) {}
    }

    if (!$canView && $user) {
        $userRoleAttr = strtolower($user->role ?? '');
        if (in_array($userRoleAttr, $allowedRoles)) {
            $canView = true;
        }
    }

    // Query principal
    $query = Curso::with([
        'periodo',
        'instructores.user.datos_generales',
        'departamento',
        'cursos_participantes'
    ]);

    // Filtro por texto (nombre, modalidad, instructor)
    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('nombre', 'like', "%{$search}%")
                ->orWhere('no_registro', 'like', "%{$search}%")
                ->orWhere('modalidad', 'like', "%{$search}%")
                ->orWhere('lugar', 'like', "%{$search}%")
                ->orWhereHas('instructores.user.datos_generales', function ($iq) use ($search) {
                    $iq->where('nombre', 'like', "%{$search}%")
                       ->orWhere('apellido_paterno', 'like', "%{$search}%")
                       ->orWhere('apellido_materno', 'like', "%{$search}%");
                })
                ->orWhereHas('departamento', function ($dq) use ($search) {
                    $dq->where('nombre', 'like', "%{$search}%");
                })
                // buscar por nombre del periodo
                ->orWhereHas('periodo', function ($pq) use ($search) {
                    $pq->where('nombre', 'like', "%{$search}%")
                       ->orWhere('anio', 'like', "%{$search}%")
                       ->orWhere('trimestre', 'like', "%{$search}%");
                });
        });
    }

    // filtro por periodo_id seleccionado en la vista
    if ($periodoFiltro) {
        $query->where('periodo_id', $periodoFiltro);
    }

    // Ordenar y paginar resultados
    $cursos = $query->orderByRaw("COALESCE(fdi, id) DESC")
                    ->paginate(10)
                    ->appends([
                        'q' => $search,
                        'periodo_id' => $periodoFiltro
                    ]);

    // Pasar los datos a la vista
    return view('vistas.cursos.index', compact('cursos', 'periodo', 'periodos', 'search', 'periodoFiltro'));
}


    public function docente_index(Request $request)
    {
        $participante = $request->user()->participante;

        // Obtener cursos donde el docente no está inscrito, que estén activos y tengan cupo disponible
        $cursos = Curso::whereDoesntHave('cursos_participantes', function ($query) use ($participante) {
            $query->where('participante_id', $participante->id);
        })
        ->where('estatus', 1) // Solo cursos activos
        ->whereRaw('(SELECT COUNT(*) FROM cursos_participantes WHERE curso_id = cursos.id) < limite_participantes') // Con cupo disponible
        ->get();

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
        if ($solicitarcurso) {
            $solicitarcurso->estatus = 2;
            $solicitarcurso->save();
        }

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

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $curso = Curso::with(['instructores', 'periodo'])->find($id);
        $departamentos = Departamento::all();
        $instructores = User::whereHas('roles', function ($query) {
            $query->where('nombre', 'instructor');
        })->where('estatus', 1)
            ->with('instructor')
            ->get();
        $periodos = Periodo::orderBy('created_at')->get();
        return view('vistas.cursos.admin.edit', compact('curso', 'periodos', 'instructores', 'departamentos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Curso $curso)
    {
        // Validación de la solicitud
        $request->validate([
            'nombre' => 'required|string|max:255',
            'fecha_inicio' => 'required|date',
            'fecha_final' => 'required|date|after_or_equal:fecha_inicio',
            'objetivo' => 'required|string|max:500',
            'modalidad' => 'required|string',
            'lugar' => 'required|string|max:255',
            'horario' => 'required|string|max:255',
            'duracion' => 'required|string|min:1|max:100',
            'no_registro' => 'required|string|max:100',
            'periodo' => 'required|exists:periodos,id',
            'departamento' => 'required|exists:departamentos,id',
            'tipo' => 'required|string',
            'clase' => 'required|string|max:100',
            'limite_participantes' => 'required|integer|min:1',
            'es_tics' => 'sometimes|boolean',
            'es_tutorias' => 'sometimes|boolean',
            'instructores' => 'required|array',
            'instructores.*' => 'exists:instructores,id',
        ]);

        // Actualizar el curso con los datos proporcionados
        $curso->update([
            'nombre' => $request->nombre,
            'fdi' => $request->fecha_inicio,
            'fdf' => $request->fecha_final,
            'departamento_id' => $request->departamento,
            'objetivo' => $request->objetivo,
            'modalidad' => $request->modalidad,
            'lugar' => $request->lugar,
            'horario' => $request->horario,
            'duracion' => $request->duracion,
            'no_registro' => $request->no_registro,
            'periodo_id' => $request->periodo,
            'tipo' => $request->tipo,
            'clase' => $request->clase,
            'limite_participantes' => $request->limite_participantes,
            'es_tics' => $request->has('es_tics'),
            'es_tutorias' => $request->has('es_tutorias'),
        ]);

        // Sincronizar la relación con los instructores
        $curso->instructores()->sync($request->instructores);

        // Redirigir con un mensaje de éxito
        return redirect(route('cursos.show', $curso->id))->with('success', 'Curso actualizado correctamente.');
    }

    public function terminar_curso(Curso $curso)
    {
        try {
            $curso->estatus = '0';
            $curso->save();
            return back()->with('success', 'Curso terminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('inicio')->with('error', 'Ocurrió un error al terminar el curso.');
        }
    }

    public function iniciar_curso(Curso $curso)
    {
        try {
            $curso->estatus = '1';
            $curso->save();
            return back()->with('success', 'Curso iniciado exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('inicio')->with('error', 'Ocurrió un error al iniciar el curso.');
        }
    }

    public function generarPDF($curso_id)
    {
        try {
            $curso = Curso::with(['instructores.user.datos_generales', 'cursos_participantes.participante.user.datos_generales'])
                ->findOrFail($curso_id);

            if (!$curso->created_at instanceof Carbon) {
                $curso->created_at = Carbon::parse($curso->created_at);
            }

            $cursosDelAnio = Curso::whereYear('created_at', $curso->created_at->format('Y'))
                ->orderBy('created_at')
                ->get();

            $numeroDelCurso = $cursosDelAnio->search(function ($c) use ($curso) {
                return $c->id === $curso->id;
            });

            if ($numeroDelCurso === false) {
                throw new \Exception('No se pudo calcular el número del curso dentro del año.');
            }

            $numeroDelCurso += 1;

            $data = [
                'curso' => $curso,
                'instructores' => $curso->instructores,
                'participantes' => $curso->cursos_participantes,
                'numeroDelCurso' => $numeroDelCurso,
            ];

            $pdf = app(PDF::class)->loadView('vistas.cursos.pdf.curso_detalle', $data);

            return $pdf->download('curso-' . $curso->nombre . '.pdf');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('cursos.index')->with('error', 'El curso especificado no existe.');
        } catch (\Exception $e) {
            return redirect()->route('cursos.index')->with('error', 'Error al generar el PDF: ' . $e->getMessage());
        }
    }

    public function estadisticas_index()
    {
        $periodos = Periodo::all()->groupBy('anio');
        return view('vistas.cursos.admin.estadisticas.index', compact('periodos'));
    }

    public function estadisticas_show($anio)
    {
        $cursos = Curso::whereHas('periodo', function ($query) use ($anio) {
            $query->where('anio', $anio);
        })->get();

        $estadisticas = [
            'trimestre_1' => ['total_participantes'=>0,'total_docente'=>0,'total_profesional'=>0,'total_tics'=>0,'total_tutorias'=>0],
            'trimestre_2' => ['total_participantes'=>0,'total_docente'=>0,'total_profesional'=>0,'total_tics'=>0,'total_tutorias'=>0],
            'trimestre_3' => ['total_participantes'=>0,'total_docente'=>0,'total_profesional'=>0,'total_tics'=>0,'total_tutorias'=>0],
            'trimestre_4' => ['total_participantes'=>0,'total_docente'=>0,'total_profesional'=>0,'total_tics'=>0,'total_tutorias'=>0],
        ];

        foreach ($cursos as $curso) {
            $trimestre = $curso->periodo->trimestre;
            if ($curso->clase == 'Docente') {
                $estadisticas["trimestre_$trimestre"]['total_docente'] += $curso->participantes->count();
            } elseif ($curso->clase == 'Profesional') {
                $estadisticas["trimestre_$trimestre"]['total_profesional'] += $curso->participantes->count();
            }
            if ($curso->es_tics) {
                $estadisticas["trimestre_$trimestre"]['total_tics'] += $curso->participantes->count();
            }
            if ($curso->es_tutorias) {
                $estadisticas["trimestre_$trimestre"]['total_tutorias'] += $curso->participantes->count();
            }
        }

        for ($i = 1; $i <= 4; $i++) {
            $fecha_inicio_trimestre = sprintf('%d-%02d-01', $anio, (($i - 1) * 3) + 1);
            $fecha_fin_trimestre = sprintf('%d-%02d-%02d', $anio, $i * 3, cal_days_in_month(CAL_GREGORIAN, $i * 3, $anio));

            $usuariosTipo1Estatus1Trimestre = DB::table('users')
                ->join('historial_usuarios', 'users.id', '=', 'historial_usuarios.user_id')
                ->where('historial_usuarios.tipo', 1)
                ->where('historial_usuarios.estatus', 1)
                ->where(function ($query) use ($fecha_inicio_trimestre, $fecha_fin_trimestre) {
                    $query->where(function ($subQuery) use ($fecha_inicio_trimestre, $fecha_fin_trimestre) {
                        $subQuery->whereNotNull('historial_usuarios.fecha_fin')
                            ->whereDate('historial_usuarios.fecha_inicio', '<=', $fecha_fin_trimestre)
                            ->whereDate('historial_usuarios.fecha_fin', '>=', $fecha_inicio_trimestre);
                    })
                    ->orWhere(function ($subQuery) use ($fecha_fin_trimestre) {
                        $subQuery->whereNull('historial_usuarios.fecha_fin')
                            ->whereDate('historial_usuarios.fecha_inicio', '<=', $fecha_fin_trimestre);
                    });
                })
                ->distinct('users.id')
                ->count();

            $estadisticas["trimestre_$i"]['total_participantes'] += $usuariosTipo1Estatus1Trimestre;
        }

        return view('vistas.cursos.admin.estadisticas.show', compact('estadisticas', 'anio'));
    }

    public function entregar_calificaciones($id)
    {
        try {
            $curso = Curso::findOrFail($id);
            $curso->estado_calificacion = 2;
            $curso->save();

            return redirect()->route('cursos.show', $id)->with('success', 'Calificaciones subidas a participantes correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('cursos.index')->with('error', 'Error al subir las calificaciones: ' . $e->getMessage());
        }
    }

    public function devolver_calificaciones($id)
    {
        try {
            $curso = Curso::findOrFail($id);
            $curso->estado_calificacion = 0;
            $curso->save();

            return redirect()->route('cursos.show', $id)->with('success', 'Calificaciones devueltas al instructor correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('cursos.index')->with('error', 'Error al devolver las calificaciones: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Curso $curso)
    {
        try {
            $curso->delete();
            return redirect()->route('cursos.index')->with('success', 'Curso eliminado correctamente.');
        } catch (\Exception $e) {
            return redirect()->route('cursos.index')->with('error', 'Error al eliminar el curso: ' . $e->getMessage());
        }
    }
}
