<?php

namespace App\Http\Controllers;

use App\Models\cursos_participante;
use App\Models\Curso;
use App\Models\encuesta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CursoParticipanteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    public function cursando_index(Request $request)
    {
        $participante = $request->user()->participante->id;

        $cursosCursando = cursos_participante::where('participante_id', $participante)
            ->whereHas('curso', function ($query) {
                $query->where('estatus', '1');
            })
            ->with('curso.instructores') // Cargar los instructores junto con los cursos
            ->orderBy('id', 'desc')
            ->get();

        return view('vistas.cursos.docente.cursando.index', compact('cursosCursando'));
    }

    public function terminados_index(Request $request)
    {

        $participante = $request->user()->participante->id;

        $cursosTerminados = cursos_participante::where('participante_id', $participante)
            ->whereHas('curso', function ($query) {
                $query->where('estatus', '0');
            })
            ->with('curso.instructores') // Cargar los instructores junto con los cursos
            ->orderBy('id', 'desc')
            ->get();

        return view('vistas.cursos.docente.terminados.index', compact('cursosTerminados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'curso_id' => 'required|exists:cursos,id',
        ]);

        // Obtener el curso
        $curso = Curso::findOrFail($request->curso_id);

        // Verificar si el usuario es instructor del curso que está intentando tomar
        $user = $request->user();

        // Verificar si el usuario tiene rol de instructor (usando la tabla user_roles directamente)
        $user_role_ids = \Illuminate\Support\Facades\DB::table('user_roles')
            ->where('user_id', $user->id)
            ->pluck('role_id')
            ->toArray();

        $is_instructor = in_array(5, $user_role_ids); // 5 es el ID del rol Instructor

        if ($is_instructor) {
            // Verificar si este instructor imparte el curso al que se quiere inscribir
            $esInstructorDelCurso = $curso->instructores()
                ->whereHas('user', function ($query) use ($user) {
                    $query->where('id', $user->id);
                })
                ->exists();

            if ($esInstructorDelCurso) {
                return back()->with('error', 'No puedes inscribirte a un curso que tú mismo impartes.')->withInput();
            }
        }

        // Verificar el límite de participantes
        $inscritosActuales = $curso->participantes()->count(); // Asumiendo relación 'participantes' en el modelo Curso
        if ($inscritosActuales >= $curso->limite_participantes) {
            return back()->with('error', 'El curso ya ha alcanzado el límite máximo de participantes.')->withInput();
        }

        // Crear el registro en la tabla pivot
        $cursoParticipante = new cursos_participante();
        $cursoParticipante->participante_id = $request->user()->participante->id;
        $cursoParticipante->curso_id = $request->curso_id;

        $cursoParticipante->save();

        return back()->with('success', 'Inscripción realizada correctamente.');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = Auth::user()->participante;

        $curso_participante = cursos_participante::where('participante_id', $user->id)
            ->where('curso_id', $id)
            ->with('curso')
            ->first();

        if (!$curso_participante) {
            return redirect()->route('inicio')->with('error', 'No estás inscrito en este curso.');
        }

        $encuesta = encuesta::where('participante_id', $user->id)
            ->where('curso_id', $curso_participante->curso->id)
            ->first();

        if ($curso_participante->curso->estatus != 0) {
            return redirect()->route('inicio')->with('error', 'El curso no ha terminado aún.');
        }

        return view('vistas.cursos.docente.terminados.show', compact('curso_participante', 'encuesta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(cursos_participante $participanteInscrito)
    {
        if (!$participanteInscrito) {
            return back()->with('error', 'Registro no encontrado.');
        }

        $participanteInscrito->delete();

        return back()->with('success', 'Inscripción eliminada correctamente.');
    }
}
