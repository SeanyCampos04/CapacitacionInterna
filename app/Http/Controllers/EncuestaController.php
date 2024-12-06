<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\cursos_participante;
use App\Models\encuesta;
use App\Models\Participante;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EncuestaController extends Controller
{
    // Muestra el formulario de la encuesta
    public function formulario($curso_id)
    {
        if (Auth::id()) {
            // Obtener los datos necesarios (si es necesario) o simplemente pasar los IDs a la vista
            $curso = Curso::find($curso_id);
            $user = Auth::user();
            $participante = Participante::find($user->participante->id);

            // Verificar si ya existe una encuesta para el participante y el curso
            $encuesta = encuesta::where('participante_id', $participante->id)
                                ->where('curso_id', $curso_id)
                                ->first();
            if ($encuesta) {
                return redirect()->route('cursos_terminados.show', $curso_id)->with('success', 'Encuesta ya contestada');
            }

            return view('vistas.encuesta.formulario', compact('curso', 'participante'));
        }
    }

    public function store(Request $request)
    {
        // Validación de las respuestas
        $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'participante_id' => 'required|exists:participantes,id',
            // Validar las respuestas de las secciones
            'evento_expectativas' => 'required|integer|between:1,5',
            'evento_objetivo' => 'required|integer|between:1,5',
            'instructor_dudas' => 'required|integer|between:1,5',
            'contenidos_material' => 'required|integer|between:1,5',
            'contenidos_curso' => 'required|integer|between:1,5',
            'condiciones_adecuadas' => 'required|integer|between:1,5',
            'personal_organizador' => 'required|integer|between:1,5',
            'instructor_habilidad' => 'required|integer|between:1,5',
            'instructor_exposicion' => 'required|integer|between:1,5',
            'instructor_aclara' => 'required|integer|between:1,5',
            'competencias_desarrolladas' => 'required|integer|between:1,5',
            'competencias_adquiridas' => 'required|integer|between:1,5',
            'competencias_comprension' => 'required|integer|between:1,5',
            'participacion' => 'required|boolean',
            'comentarios' => 'nullable|string',
        ]);


        // Crear un nuevo registro de encuesta
        $encuesta = new Encuesta();
        $encuesta->curso_id = $request->curso_id;
        $encuesta->participante_id = $request->participante_id;

        // Guardar las respuestas de las secciones
        $encuesta->evento_expectativas = $request->evento_expectativas;
        $encuesta->evento_objetivo = $request->evento_objetivo;
        $encuesta->instructor_dudas = $request->instructor_dudas;
        $encuesta->contenidos_material = $request->contenidos_material;
        $encuesta->contenidos_curso = $request->contenidos_curso;
        $encuesta->condiciones_adecuadas = $request->condiciones_adecuadas;
        $encuesta->personal_organizador = $request->personal_organizador;

        // Sección 2
        $encuesta->instructor_habilidad = $request->instructor_habilidad;
        $encuesta->instructor_exposicion = $request->instructor_exposicion;
        $encuesta->instructor_aclara = $request->instructor_aclara;

        // Sección 3
        $encuesta->competencias_desarrolladas = $request->competencias_desarrolladas;
        $encuesta->competencias_adquiridas = $request->competencias_adquiridas;
        $encuesta->competencias_comprension = $request->competencias_comprension;

        // Participación
        $encuesta->participacion = $request->participacion;

        // Comentario (opcional)
        $encuesta->comentario = $request->comentarios;

        // Guardar el registro en la base de datos
        $encuesta->save();

        // Retornar una respuesta o redirigir al administrador
        return redirect()->route('cursos_terminados.index')->with('success', 'Encuesta contestada correctamente');
    }

    public function resultados($curso_id)
    {
        // Obtener las encuestas para un curso específico
        $encuestas = Encuesta::where('curso_id', $curso_id)->get();

        // Inicializar los resultados para cada pregunta
        $resultados = $this->inicializarResultados();

        // Contar las respuestas de las encuestas
        foreach ($encuestas as $encuesta) {
            $this->contarRespuestas($resultados, $encuesta);
        }

        // Obtener los IDs de los participantes inscritos en el curso
        $participantesIds = cursos_participante::where('curso_id', $curso_id)->pluck('participante_id');

        // Obtener los usuarios con sus departamentos
        $usuarios = Participante::with(['user.datos_generales.departamento'])->whereIn('id', $participantesIds)->get();

        $departamentos = $usuarios->groupBy(function ($usuario) {
            // Verifica si las relaciones están correctamente cargadas
            if ($usuario->user->datos_generales && $usuario->user->datos_generales->departamento) {
                return $usuario->user->datos_generales->departamento->nombre;
            }
            return 'Sin departamento';
        })->map(function ($group) {
            return $group->count();
        });

        // Obtener información del curso
        $curso = Curso::findOrFail($curso_id);

        // Pasar los resultados, curso y departamentos a la vista
        return view('vistas.encuesta.resultados', compact('resultados', 'curso', 'departamentos', 'encuestas'));
    }

    /**
     * Inicializar los resultados para cada pregunta.
     */
    private function inicializarResultados()
    {
        return [
            'evento_expectativas' => [0, 0, 0, 0, 0],
            'evento_objetivo' => [0, 0, 0, 0, 0],
            'instructor_dudas' => [0, 0, 0, 0, 0],
            'contenidos_material' => [0, 0, 0, 0, 0],
            'contenidos_curso' => [0, 0, 0, 0, 0],
            'condiciones_adecuadas' => [0, 0, 0, 0, 0],
            'personal_organizador' => [0, 0, 0, 0, 0],
            'instructor_habilidad' => [0, 0, 0, 0, 0],
            'instructor_exposicion' => [0, 0, 0, 0, 0],
            'instructor_aclara' => [0, 0, 0, 0, 0],
            'competencias_desarrolladas' => [0, 0, 0, 0, 0],
            'competencias_adquiridas' => [0, 0, 0, 0, 0],
            'competencias_comprension' => [0, 0, 0, 0, 0],
            'participacion' => [0, 0],
        ];
    }

    /**
     * Contar las respuestas de una encuesta y acumular los resultados.
     */
    private function contarRespuestas(&$resultados, $encuesta)
    {
        $resultados['evento_expectativas'][$encuesta->evento_expectativas - 1]++;
        $resultados['evento_objetivo'][$encuesta->evento_objetivo - 1]++;
        $resultados['instructor_dudas'][$encuesta->instructor_dudas - 1]++;
        $resultados['contenidos_material'][$encuesta->contenidos_material - 1]++;
        $resultados['contenidos_curso'][$encuesta->contenidos_curso - 1]++;
        $resultados['condiciones_adecuadas'][$encuesta->condiciones_adecuadas - 1]++;
        $resultados['personal_organizador'][$encuesta->personal_organizador - 1]++;
        $resultados['instructor_habilidad'][$encuesta->instructor_habilidad - 1]++;
        $resultados['instructor_exposicion'][$encuesta->instructor_exposicion - 1]++;
        $resultados['instructor_aclara'][$encuesta->instructor_aclara - 1]++;
        $resultados['competencias_desarrolladas'][$encuesta->competencias_desarrolladas - 1]++;
        $resultados['competencias_adquiridas'][$encuesta->competencias_adquiridas - 1]++;
        $resultados['competencias_comprension'][$encuesta->competencias_comprension - 1]++;
        $resultados['participacion'][$encuesta->participacion == '1' ? 0 : 1]++;
    }

}
