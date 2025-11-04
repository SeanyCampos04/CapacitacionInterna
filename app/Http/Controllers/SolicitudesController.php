<?php

namespace App\Http\Controllers;

use App\Models\Calificacion_modulo;
use App\Models\Diplomado;
use App\Models\Instructore;
use App\Models\Participante;
use Illuminate\Http\Request;
use App\Models\solicitud_docente;
use App\Models\solicitud_instructore;

class SolicitudesController extends Controller
{
    public function index($id)
    {
        $solicitudes = Diplomado::with([
            'solicitudesParticipantes',
            'solicitudesInstructores'
        ])->where('id', $id)->first();
        // Pasar las solicitudes con los datos relacionados a la vista
        return view('diplomados.admin.solicitudes', compact('solicitudes'));
    }

    public function solicitudes(Request $request)
    {
        $user = auth()->user();

        // Inicializar las variables para evitar errores de referencia
        $solicitudesParticipante = collect();
        $solicitudesInstructor = collect();

        // Obtener términos de búsqueda y filtros
        $search = $request->get('q');
        $estatus = $request->get('estatus');
        $tipo = $request->get('tipo');
        $rol = $request->get('rol');

        // Función para aplicar filtros comunes
        $applyCommonFilters = function($query) use ($search, $estatus, $tipo) {
            if ($search) {
                $query->whereHas('diplomado', function($q) use ($search) {
                    $q->where('nombre', 'LIKE', "%{$search}%")
                      ->orWhere('tipo', 'LIKE', "%{$search}%")
                      ->orWhere('sede', 'LIKE', "%{$search}%");
                });
            }

            if ($estatus !== null && $estatus !== '') {
                $query->where('estatus', $estatus);
            }

            if ($tipo) {
                $query->whereHas('diplomado', function($q) use ($tipo) {
                    $q->where('tipo', $tipo);
                });
            }
        };

        // Verificar si el usuario es participante y si el filtro de rol lo permite
        if ($user->participante && (!$rol || $rol === 'participante')) {
            $query = solicitud_docente::where('participante_id', $user->participante->id)
                ->with('diplomado');

            $applyCommonFilters($query);
            $solicitudesParticipante = $query->paginate(10)->withQueryString();
        }

        // Verificar si el usuario es instructor y si el filtro de rol lo permite
        if ($user->instructor && (!$rol || $rol === 'instructor')) {
            $query = solicitud_instructore::where('instructore_id', $user->instructor->id)
                ->with('diplomado');

            $applyCommonFilters($query);
            $solicitudesInstructor = $query->paginate(10)->withQueryString();
        }

        // Pasar las solicitudes con los datos relacionados a la vista
        return view('diplomados.solicitudes', compact('solicitudesParticipante', 'solicitudesInstructor', 'user', 'search', 'estatus'));
    }

    public function store(Request $request) {}

    public function solicitar_Docente_Oferta(Request $request, $id)
    {
        // Obtener el ID del usuario autenticado
        $userId = auth()->user()->id;

        // Obtener el participante correspondiente al usuario autenticado
        $participante = Participante::where('user_id', $userId)->first();

        if (!$participante) {
            // Si no se encuentra un participante asociado al usuario, redirigir con un error
            return redirect()->route('dashboard')->with('error', 'No se encontró un participante para este usuario.');
        }

        // Obtener el diplomado con el ID proporcionado
        $diplomado = Diplomado::findOrFail($id);

        // Verificar si la solicitud ya existe para evitar duplicados
        $existingSolicitud = solicitud_docente::where('diplomado_id', $diplomado->id)
            ->where('participante_id', $participante->id)
            ->first();

        if ($existingSolicitud) {
            return redirect()->back()->with('info', 'Ya has solicitado este diplomado.');
        }

        // Validar el archivo PDF
        $request->validate([
            'pdf' => 'required|file|mimes:pdf|max:10240',  // 10MB como máximo y debe ser PDF
        ]);

        // Verificar si se ha subido el archivo PDF
        if ($request->hasFile('pdf') && $request->file('pdf')->isValid()) {
            // Obtener el archivo PDF
            $pdf = $request->file('pdf');

            // Guardar el archivo PDF en una carpeta 'cartas/compromiso' en el almacenamiento público
            $path = $pdf->store('cartas/compromiso', 'custom_public');  // Guardar en storage/app/public/cartas/compromiso

            // Crear la solicitud de inscripción con el archivo PDF
            solicitud_docente::create([
                'diplomado_id' => $diplomado->id,
                'participante_id' => $participante->id,
                'carta_compromiso' => $path,  // Almacenar la ruta del archivo en la base de datos
            ]);

            return redirect()->back()->with('success', 'Solicitud realizada correctamente.');
        } else {
            return redirect()->back()->with('error', 'No se ha subido un archivo PDF válido.');
        }
    }



    // Método para aceptar una solicitud
    public function aceptar_docente($id)
    {
        // Encuentra la solicitud por su ID
        $solicitud = solicitud_docente::findOrFail($id);

        // Cambia el estado de la solicitud a "aceptada"
        $solicitud->estatus = 2;
        $solicitud->save();

        // Obtén los módulos relacionados al diplomado
        $diplomado = $solicitud->diplomado; // Asume que hay una relación entre solicitud y diplomado
        $modulos = $diplomado->modulos; // Asume que un diplomado tiene muchos módulos

        // Itera sobre los módulos y crea registros en la tabla calificacion_modulo
        foreach ($modulos as $modulo) {
            Calificacion_modulo::create([
                'participante_id' => $solicitud->participante_id, // ID del docente aceptado
                'modulo_id' => $modulo->id,             // ID del módulo
                'diplomado_id' => $diplomado->id,
                'calificacion' => null,                 // Inicializa con NULL o el valor predeterminado
            ]);
        }

        // Redirige con un mensaje de éxito
        return redirect()->route('diplomados.solicitudes_diplomado.index', $solicitud->diplomado_id)
            ->with('success', 'Solicitud aceptada correctamente y calificaciones inicializadas.');
    }

    // Método para negar una solicitud
    public function negar_docente($id)
    {
        // Encuentra la solicitud por su ID
        $solicitud = solicitud_docente::findOrFail($id);

        // Cambia el estado de la solicitud a "negada"
        $solicitud->estatus = 1;
        $solicitud->save();

        return redirect()->route('diplomados.solicitudes_diplomado.index', $solicitud->diplomado_id)->with('success', 'Solicitud negada correctamente.');
    }


    public function solicitar_instructor_Oferta(Request $request, $id)
    {
        // Obtener el ID del usuario autenticado
        $userId = auth()->user()->id;

        // Obtener el instructor correspondiente al usuario autenticado
        $instructor = Instructore::where('user_id', $userId)->first();

        if (!$instructor) {
            // Si no se encuentra un instructor asociado al usuario, redirigir con un error
            return redirect()->route('profile.edit')->with('error', 'No se encontró un instructor para este usuario.');
        }

        // Obtener el diplomado con el ID proporcionado
        $diplomado = Diplomado::findOrFail($id);

        // Verificar si la solicitud ya existe para evitar duplicados
        $existingSolicitud = solicitud_instructore::where('diplomado_id', $diplomado->id)
            ->where('instructore_id', $instructor->id)
            ->first();

        if ($existingSolicitud) {
            return redirect()->back()->with('info', 'Ya has solicitado este diplomado.');
        }

        // Subir el archivo PDF (utilizando la misma lógica que en 'solicitar_Docente_Oferta')
        if ($request->hasFile('pdf')) {
            // Obtener el archivo PDF
            $pdf = $request->file('pdf');

            // Validar que el archivo sea un PDF
            $request->validate([
                'pdf' => 'required|mimes:pdf|max:10240',  // 10MB como máximo
            ]);

            // Guardar el archivo PDF en la carpeta 'cartas/terminacion' en el almacenamiento público
            $path = $pdf->store('cartas/terminacion', 'custom_public');

            // Crear la solicitud de inscripción con el archivo PDF
            solicitud_instructore::create([
                'diplomado_id' => $diplomado->id,
                'instructore_id' => $instructor->id,
                'carta_terminacion' => $path,  // Almacenar la ruta del archivo en la base de datos
            ]);

            return redirect()->back()->with('success', 'Solicitud realizada correctamente. Archivo guardado en: ' . $path);
        } else {
            return redirect()->back()->with('error', 'No se ha subido un archivo PDF.');
        }
    }


    // Método para aceptar una solicitud
    public function aceptar_instructor($id)
    {
        // Encuentra la solicitud por su ID
        $solicitud = solicitud_instructore::findOrFail($id);

        // Cambia el estado de la solicitud a "aceptada"
        $solicitud->estatus = 2;
        $solicitud->save();

        return redirect()->route('diplomados.solicitudes_diplomado.index', $solicitud->diplomado_id)->with('success', 'Solicitud aceptada correctamente.');
    }

    // Método para negar una solicitud
    public function negar_instructor($id)
    {
        // Encuentra la solicitud por su ID
        $solicitud = solicitud_instructore::findOrFail($id);

        // Cambia el estado de la solicitud a "negada"
        $solicitud->estatus = 1;
        $solicitud->save();

        return redirect()->route('diplomados.solicitudes_diplomado.index', $solicitud->diplomado_id)->with('success', 'Solicitud negada correctamente.');
    }
}
