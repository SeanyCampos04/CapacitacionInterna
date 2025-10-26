<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Calificacion_modulo;
use App\Models\Diplomado;
use App\Models\Instructore;
use App\Models\Modulo;
use App\Models\Participante;
use App\Models\solicitud_docente;
use App\Models\solicitud_instructore;
use Illuminate\Http\Request;

class ModuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function detalles_modulo(Modulo $modulo)
    {
        $diplomado = Diplomado::with('solicitudDocentes.participante.user.datos_generales')->find($modulo->diplomado_id);

        // Obtener todos los módulos del diplomado, ordenados por el campo 'numero' (campo de orden)
        $modulos = Modulo::where('diplomado_id', $diplomado->id)
            ->orderBy('numero')  // Ordenar por el campo 'numero'
            ->get();

        // Obtener todos los docentes del diplomado con sus calificaciones si existen
        $docentes = $diplomado->solicitudDocentes->map(function ($solicitud) use ($modulo, $diplomado, $modulos) {
            // Verificar si el participante tiene calificación reprobada en algún módulo anterior
            $reprobado = false;

            // Obtener todos los módulos anteriores al actual
            $modulosAnteriores = $modulos->filter(function ($moduloAnterior) use ($modulo) {
                return $moduloAnterior->fecha_termino < $modulo->fecha_inicio; // Módulos terminados antes de este módulo
            });

            foreach ($modulosAnteriores as $moduloAnterior) {
                // Obtener la calificación de un módulo anterior
                $calificacion = Calificacion_modulo::where('modulo_id', $moduloAnterior->id)
                    ->where('diplomado_id', $diplomado->id)
                    ->where('participante_id', $solicitud->participante->id)
                    ->first();

                // Si en cualquier módulo anterior la calificación es menor a 70, el participante está reprobado
                if ($calificacion && $calificacion->calificacion < 70) {
                    $reprobado = true;
                    break; // No necesitamos seguir buscando, ya sabemos que está reprobado
                }
            }

            // Si el participante está reprobado en cualquier módulo anterior, lo excluimos de la lista
            if ($reprobado) {
                return null; // Excluir este participante de la lista
            }

            // Obtener la calificación en el módulo actual
            $calificacionActual = Calificacion_modulo::where('modulo_id', $modulo->id)
                ->where('diplomado_id', $diplomado->id)
                ->where('participante_id', $solicitud->participante->id)
                ->first();

            return [
                'docente' => $solicitud->participante->user->datos_generales,
                'calificacion' => $calificacionActual->calificacion ?? null,
                'numero_modulo' => $modulo->numero, // Incluir el número del módulo en la respuesta si es necesario
            ];
        })->filter(); // Filtra los elementos nulos (participantes reprobados)

        return view('diplomados.instructor.detallemodulo', compact('diplomado', 'modulo', 'docentes'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $ulitmo_modulo = Modulo::where('diplomado_id', $id)->orderBy('numero', 'desc')->first();
        $modulo_numero = $ulitmo_modulo ? $ulitmo_modulo->numero + 1 : 1;

        $instructores = solicitud_instructore::where('diplomado_id', $id)->where('estatus', 2)->with('instructore')->get();

        return view('diplomados.modulo.admin.registrar_modulos', compact('id', 'instructores', 'modulo_numero'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'diplomado_id' => 'required|integer|exists:diplomados,id',
            'moduleNumber' => 'required|integer|min:1',
            'moduleName' => 'required|string|max:255',
            'startDate' => 'required|date|before_or_equal:endDate',
            'endDate' => 'required|date|after_or_equal:startDate',
            'instructor' => 'required',
        ]);

        $modulo = new Modulo();
        $modulo->diplomado_id = $validatedData['diplomado_id'];
        $modulo->numero = $validatedData['moduleNumber'];
        $modulo->nombre = $validatedData['moduleName'];
        $modulo->fecha_inicio = $validatedData['startDate'];
        $modulo->fecha_termino = $validatedData['endDate'];
        $modulo->instructore_id = $validatedData['instructor'];
        $modulo->save();

        $participantes = solicitud_docente::where('diplomado_id', $validatedData['diplomado_id'])->with('participante')->get();

        // Crear una calificación para cada participante
        foreach ($participantes as $participante) {
            Calificacion_modulo::create([
                'diplomado_id' => $validatedData['diplomado_id'],
                'modulo_id' => $modulo->id,
                'participante_id' => $participante->participante->id,
                'calificacion' => null,  // O establecer un valor predeterminado si es necesario
            ]);
        }

        return redirect()->route('diplomados.diplomados.index', $request->diplomado_id)
            ->with('success', 'Módulo registrado exitosamente.');
    }


    public function actualizar_calificacion_participante(Request $request)
    {
        // Validar la calificación
        $request->validate([
            'calificacion' => 'required|numeric|min:0|max:100',
        ]);

        // Buscar la calificación y actualizarla
        $calificacion = Calificacion_modulo::where('participante_id', $request->participante_id)
            ->where('modulo_id', $request->modulo_id)
            ->where('diplomado_id', $request->diplomado_id)
            ->first();

        if ($calificacion) {
            $calificacion->calificacion = $request->calificacion;
            $calificacion->save();
        }

        return back()->with('success', 'Calificación actualizada correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // Método para mostrar el formulario de edición
    public function edit($id)
    {
        // Buscar el módulo a editar
        $modulo = Modulo::findOrFail($id);

        // Obtener todos los instructores disponibles
        $instructores = solicitud_instructore::where('diplomado_id', $modulo->diplomado->id)->where('estatus', 2)->with('instructore')->get();

        // Retornar la vista de edición con los datos del módulo y los instructores
        return view('diplomados.modulo.admin.editar_modulos', compact('modulo', 'instructores'));
    }

    // Método para actualizar el módulo
    public function update(Request $request, $id)
    {
        // Validación de los datos
        $request->validate([
            'moduleName' => 'required|string|max:255',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'instructor' => 'required|exists:instructores,id', // Asegúrate de que el instructor exista
        ]);

        // Buscar el módulo a actualizar
        $modulo = Modulo::findOrFail($id);

        // Actualizar los campos del módulo
        $modulo->update([
            'nombre' => $request->moduleName,
            'fecha_inicio' => $request->startDate,
            'fecha_termino' => $request->endDate,
            'instructore_id' => $request->instructor,
        ]);

        // Redirigir con un mensaje de éxito
        return redirect()->route('diplomados.diplomados.detalle', $modulo->diplomado_id)->with('success', 'Módulo actualizado correctamente.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
