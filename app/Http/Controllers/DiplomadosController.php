<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Diplomado;
use App\Models\Modulo;
use App\Models\Participante;
use App\Models\solicitud_docente;
use App\Models\solicitud_instructore;
use App\Models\cursos_instructore;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DiplomadosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
{
    $user = Auth::user();

    // Verificación de roles permitidos
    $roles_permitidos = ['admin', 'CAD', 'jefe_departamento', 'subdirector'];
    $user_roles = method_exists($user, 'getRoleNames') ? $user->getRoleNames()->toArray() : [];


    //  Filtros de búsqueda
    $query = Diplomado::query();

    if ($request->filled('nombre')) {
        $query->where('nombre', 'like', '%' . $request->nombre . '%');
    }

    if ($request->filled('tipo')) {
        $query->where('tipo', $request->tipo);
    }

    if ($request->filled('sede')) {
        $query->where('sede', 'like', '%' . $request->sede . '%');
    }

    if ($request->filled('inicio_oferta')) {
        $query->whereDate('inicio_oferta', '>=', $request->inicio_oferta);
    }

    if ($request->filled('termino_realizacion')) {
        $query->whereDate('termino_realizacion', '<=', $request->termino_realizacion);
    }

    $diplomados = $query->orderBy('inicio_oferta', 'desc')->get();

    return view('diplomados.admin.diplomadosregistrados', compact('diplomados', 'user_roles'));
}

    public function detalles($id)
    {
        $diplomado = Diplomado::find($id);
        $modulos = Modulo::where('diplomado_id', $id)->with('instructore', 'calificacionesModulos')->orderBy('numero')->get();

        return view('diplomados.detallediplomado', compact('diplomado', 'modulos'));
    }

    public function detalles_participante($id)
    {
        $diplomado = Diplomado::find($id);
        $user = Auth::user();
        $modulos = Modulo::where('diplomado_id', $id)->with('instructore')->orderBy('numero')->get();

        return view('diplomados.participante.detallediplomado', compact('diplomado', 'modulos', 'user'));
    }

    public function detalles_instructor($id)
    {
        $diplomado = Diplomado::find($id);

        // Obtener los módulos junto con el instructor y las calificaciones
        $modulos = Modulo::where('diplomado_id', $id)
            ->with(['instructore.user.datos_generales', 'calificacionesModulos'])
            ->orderBy('numero')
            ->get();

        return view('diplomados.instructor.detallediplomado', compact('diplomado', 'modulos'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('diplomados.admin.registrodiplomado');
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
            'nombre' => 'required|string|max:255',
            'objetivo' => 'required|string|max:255',
            'tipo' => 'required|string',
            'clase' => 'required|string',
            'sede' => 'required|string|max:255',
            'responsable' => 'required|string|max:255',
            'correo_contacto' => 'required|email',
            'inicio_oferta' => 'required|date',
            'termino_oferta' => 'required|date',
            'inicio_realizacion' => 'required|date',
            'termino_realizacion' => 'required|date',
        ]);

        Diplomado::create($validatedData);

        return redirect()->route('diplomados.diplomados.index')->with('success', 'Diplomado registrado con éxito');
    }

    public function edit($id)
    {
        $diplomado = Diplomado::findOrFail($id);
        return view('diplomados.admin.registrodiplomado', compact('diplomado'));
    }

    public function update(Request $request, $id)
    {
        $diplomado = Diplomado::findOrFail($id);
        $diplomado->update($request->all());

        return redirect()->route('diplomados.diplomados.index')->with('success', 'Diplomado actualizado exitosamente');
    }

    public function mostrarOferta()
    {
        // Filtra los diplomados que están dentro del rango de fecha de oferta
        $diplomados = Diplomado::whereDate('inicio_oferta', '<=', now())
            ->whereDate('termino_oferta', '>=', now())
            ->get();

        $user = Auth::user();

        // Verifica si el usuario tiene una relación de instructor
        $diplomadosConSolicitudInstructor = [];
        if ($user->instructor) {
            $diplomadosConSolicitudInstructor = $user->instructor->solicitudes()->pluck('diplomado_id')->toArray();
        }

        // Verifica si el usuario tiene una relación de participante
        $diplomadosConSolicitudParticipante = [];
        if ($user->participante) {
            $diplomadosConSolicitudParticipante = $user->participante->solicitudes()->pluck('diplomado_id')->toArray();
        }

        // Pasa las variables a la vista
        return view('diplomados.oferta', compact(
            'diplomados',
            'user',
            'diplomadosConSolicitudInstructor',
            'diplomadosConSolicitudParticipante'
        ));
    }


    public function curso_docente(Request $request)
{
    $user = Auth::user();
    $hoy = Carbon::today();

    $query = solicitud_docente::where('participante_id', $user->participante->id)
        ->where('estatus', 2)
        ->whereHas('diplomado', function ($q) use ($hoy) {
            $q->where('inicio_realizacion', '<=', $hoy)
              ->where('termino_realizacion', '>=', $hoy);
        })
        ->with('diplomado');

    // FILTRO: Nombre
    if ($request->filled('nombre')) {
        $query->whereHas('diplomado', function ($q) use ($request) {
            $q->where('nombre', 'LIKE', '%' . $request->nombre . '%');
        });
    }

    // FILTRO: Fecha inicio
    if ($request->filled('fecha_inicio')) {
        $query->whereHas('diplomado', function ($q) use ($request) {
            $q->whereDate('inicio_realizacion', '>=', $request->fecha_inicio);
        });
    }

    // FILTRO: Fecha fin
    if ($request->filled('fecha_fin')) {
        $query->whereHas('diplomado', function ($q) use ($request) {
            $q->whereDate('termino_realizacion', '<=', $request->fecha_fin);
        });
    }

    $diplomados = $query->get();

    return view('diplomados.participante.en_curso', compact('diplomados'));
}


    public function terminado_docente()
    {
        $user = Auth::user();
        $hoy = Carbon::today();

        // Obtener los diplomados en curso asociados al participante autenticado
        $diplomados = solicitud_docente::where('participante_id', $user->participante->id)
            ->where('estatus', 2)
            ->whereHas('diplomado', function ($query) use ($hoy) {
                $query->where('termino_realizacion', '<', $hoy);
            })
            ->with('diplomado')
            ->get();

        return view('diplomados.participante.terminado', compact('diplomados'));
    }

    public function curso_instructor(Request $request)
{
    $user = Auth::user();
    $hoy = Carbon::today();

    // Diplomados asociados al instructor actual que están en curso
    $query = solicitud_instructore::where('instructore_id', $user->instructor->id)
        ->where('estatus', 2)
        ->whereHas('diplomado', function ($q) use ($hoy) {
            $q->where('inicio_realizacion', '<=', $hoy)
              ->where('termino_realizacion', '>=', $hoy);
        })
        ->with('diplomado');

    // FILTRO: Nombre del diplomado
    if ($request->filled('nombre')) {
        $query->whereHas('diplomado', function ($q) use ($request) {
            $q->where('nombre', 'LIKE', '%' . $request->nombre . '%');
        });
    }

    // FILTRO: Fecha inicio
    if ($request->filled('fecha_inicio')) {
        $query->whereHas('diplomado', function ($q) use ($request) {
            $q->whereDate('inicio_realizacion', '>=', $request->fecha_inicio);
        });
    }

    // FILTRO: Fecha fin
    if ($request->filled('fecha_fin')) {
        $query->whereHas('diplomado', function ($q) use ($request) {
            $q->whereDate('termino_realizacion', '<=', $request->fecha_fin);
        });
    }

    $diplomados = $query->get();

    return view('diplomados.instructor.en_curso', compact('diplomados'));
}



    public function terminado_instructor()
    {
        $user = Auth::user();
        $hoy = Carbon::today();

        // Obtener los diplomados en curso asociados al instructor autenticado
        $diplomados = solicitud_instructore::where('instructore_id', $user->instructor->id)
            ->where('estatus', 2)
            ->whereHas('diplomado', function ($query) use ($hoy) {
                $query->where('termino_realizacion', '<', $hoy);
            })
            ->with('diplomado')
            ->get();

        return view('diplomados.instructor.terminado', compact('diplomados'));
    }


    public function destroy($id)
    {
        $diplomado = Diplomado::findOrFail($id);
        $diplomado->delete();

        return redirect()->route('diplomados.diplomados.index')->with('success', 'Diplomado eliminado correctamente.');
    }

    /**
     * Mostrar los docentes inscritos de un diplomado específico
     */
    public function docentesInscritos($id)
{
    // Obtener el diplomado específico con sus solicitudes aceptadas
    $diplomado = Diplomado::with([
        'solicitudesParticipantes' => function ($query) {
            $query->where('estatus', 2)
                  ->with([
                      'participante.user.datos_generales',
                      'participante.user' => function ($q) {
                          $q->select('id', 'email');
                      }
                  ]);
        },
        'solicitudesInstructores' => function ($query) {
            $query->where('estatus', 2)
                  ->with([
                      'instructore.user.datos_generales',
                      'instructore.user' => function ($q) {
                          $q->select('id', 'email');
                      }
                  ]);
        }
    ])->findOrFail($id);

    return view('diplomados.admin.docentes-inscritos', compact('diplomado'));
}


  public function guardarRegistro(Request $request)
{
    $request->validate([
        'id' => 'required',
        'tipo' => 'required|in:participante,instructor',
        'numero' => 'required|string'
    ]);

    $id = $request->id;
    $tipo = $request->tipo; // participante o instructor
    $numero = strtoupper($request->numero); // Convertimos a mayúsculas por consistencia

    // Regex según el tipo
    $regexInstructor = '/^[A-Z0-9]{2}-\d{4}\/I-[A-Z0-9]{2}$/';
    $regexParticipante = '/^[A-Z0-9]{2}-\d{4}\/[A-Z0-9]{3}$/';

    // Validación por tipo
    if ($tipo === "instructor" && !preg_match($regexInstructor, $numero)) {
        return response()->json([
            "success" => false,
            "message" => "El número de registro para INSTRUCTOR debe tener el formato: XX-YYYY/I-XX"
        ]);
    }

    if ($tipo === "participante" && !preg_match($regexParticipante, $numero)) {
        return response()->json([
            "success" => false,
            "message" => "El número de registro para PARTICIPANTE debe tener el formato: XX-YYYY/XX"
        ]);
    }

    // Agregar el prefijo TNM-169-
    $numeroCompleto = 'TNM-169-' . $numero;

    // Validar que NO exista en ninguna de las dos tablas
    $existeParticipante = solicitud_docente::where('numero_registro', $numeroCompleto)->exists();
    $existeInstructor   = solicitud_instructore::where('numero_registro', $numeroCompleto)->exists();

    if ($existeParticipante || $existeInstructor) {
        return response()->json([
            "success" => false,
            "message" => "El número de registro '{$numeroCompleto}' ya está asignado. Debes usar uno diferente."
        ]);
    }

    // Buscar solicitud según tipo
    if ($tipo === "participante") {
        $solicitud = solicitud_docente::find($id);
    } else {
        $solicitud = solicitud_instructore::find($id);
    }

    if (!$solicitud) {
        return response()->json([
            "success" => false,
            "message" => "No se encontró la solicitud."
        ]);
    }

    // Guardar en BD
    $solicitud->numero_registro = $numeroCompleto;
    $solicitud->save();

    return response()->json(["success" => true]);
}


}
