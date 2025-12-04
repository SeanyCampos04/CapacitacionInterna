<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\Periodo;
use App\Models\cursos_participante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class InstructorController extends Controller
{
    /**
     * Display a listing of the resource.
     */



public function index(Request $request)
{
    // Obtener el instructor asociado al usuario autenticado
    $instructorId = $request->user()->instructor->id;

    // Iniciar la consulta de cursos donde el instructor está asignado
    $query = Curso::whereHas('instructores', function ($q) use ($instructorId) {
        $q->where('instructore_id', $instructorId);
    })->with('periodo');

    // Filtro de búsqueda (por nombre, modalidad, lugar, etc.)
    if ($request->filled('q')) {
        $q = $request->input('q');
        $query->where(function ($sub) use ($q) {
            $sub->where('nombre', 'like', "%$q%")
                ->orWhere('modalidad', 'like', "%$q%")
                ->orWhere('lugar', 'like', "%$q%");
        });
    }

    // Filtro por periodo 
    if ($request->filled('periodo_id')) {
        $query->where('periodo_id', $request->periodo_id);
    }

    // Paginar y mantener query string para que links conserven filtros
    $cursos = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();

    // Cargar todos los periodos para el select
    $periodos = Periodo::orderBy('periodo', 'desc')->get();

    // Valor seleccionado (opcional, para mantener selección en el select)
    $periodoFiltro = $request->input('periodo_id');

    // Pasar variables a la vista
    return view('vistas.cursos.instructor.index', compact('cursos', 'periodos', 'periodoFiltro'));
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
        // Agregar validaciones si es necesario
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
        return view('vistas.cursos.instructor.show', compact('curso', 'ParticipantesOrdenados'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Obtener el registro de cursos_participante con relación a curso y participante
        $curso_participante = cursos_participante::with('curso', 'participante')->find($id);

        // Retornar vista para calificar al participante
        return view('vistas.cursos.instructor.edit', compact('curso_participante'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, cursos_participante $cursos_participante)
    {
        // Validación de entrada
        $request->validate([
            'calificacion' => 'required|numeric|min:0|max:100',
            'comentarios' => 'nullable|string|max:1000', // Agregar restricción de longitud si es necesario
        ]);

        // Determinar si está acreditado
        $acreditado = $request->calificacion >= 70 ? 2 : 1;

        // Actualizar la información en el modelo
        $cursos_participante->update([
            'calificacion' => $request->calificacion,
            'comentarios' => $request->comentarios,
            'acreditado' => $acreditado
        ]);

        return redirect()->route('instructor.show', $cursos_participante->curso_id)->with('status', 'Calificación actualizada correctamente');
    }

    /**
     * Subir calificaciones para un curso.
     */
    public function subir_calificaciones($id)
    {
        $curso = Curso::find($id);

        // Validación de existencia de curso
        if (!$curso) {
            return back()->withErrors(['error' => 'El curso no existe.']);
        }

        $curso->estado_calificacion = 1;
        $curso->save();

        return redirect()->route('instructor.show', $id)->with('success', 'Calificaciónes subidas correctamente.');
    }

    /**
     * Subir ficha técnica de un curso.
     */
    public function subir_fichatecnica(Request $request, $curso_id)
    {
        // Validar el archivo
        $request->validate([
            'ficha_tecnica' => 'required|file|mimes:pdf|max:2048', // Máximo 2MB y debe ser PDF
        ]);

        // Obtener el curso por ID
        $curso = Curso::find($curso_id);

        // Validación si el curso no existe
        if (!$curso) {
            return back()->withErrors(['error' => 'El curso no existe.']);
        }

        // Manejar el archivo de la ficha técnica
        if ($request->hasFile('ficha_tecnica') && $request->file('ficha_tecnica')->isValid()) {
            // Eliminar la ficha técnica anterior si existe
            if ($curso->ficha_tecnica && file_exists(public_path('uploads/' . $curso->ficha_tecnica))) {
                // Eliminar el archivo anterior
                unlink(public_path('uploads/' . $curso->ficha_tecnica));
            }

            $path = $request->file('ficha_tecnica')->store('/archivos/fichas_tecnicas', 'custom_public'); // Guardar en storage/app/public/archivos/fichas_tecnicas

            // Actualizar el campo en el modelo
            $curso->ficha_tecnica = $path;
            $curso->save();
        }

        return back()->with('success', 'Ficha técnica subida correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
