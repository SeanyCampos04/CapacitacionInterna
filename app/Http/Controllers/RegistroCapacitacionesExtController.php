<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistroCapacitacionesExt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class RegistroCapacitacionesExtController extends Controller
{
    public function store(Request $request)
    {
        // Validación de los datos
        $validatedData = $request->validate([
            'correo' => 'required|email',
            'nombre' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',
            'tipo_capacitacion' => 'required|string',
            'nombre_capacitacion' => 'required|string|max:255',
            'anio' => 'required|numeric|digits:4',
            'organismo' => 'required|string|max:255',
            'horas' => 'required|integer|min:30',
            'evidencia' => 'required|file|mimes:pdf|max:1024',
            'fecha_inicio' => 'required|date',
            'fecha_termino' => 'required|date',
        ]);

        // Manejo del archivo
        if ($request->hasFile('evidencia')) {
            $file = $request->file('evidencia');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('evidencias', $filename, 'public');
            $validatedData['evidencia'] = $path;
        }

        // Almacenar los datos
        RegistroCapacitacionesExt::create($validatedData);

        // Redirigir al dashboard con mensaje de éxito
        return redirect()->route('externa.datos')->with('success', 'La capacitación se registró correctamente.');
    }

    public function index()
    {
        // Iniciar la consulta
        $query = RegistroCapacitacionesExt::query();

        // Aplicar búsqueda si existe
        if (request()->has('q')) {
            $searchTerm = request('q');
            $query->where(function($q) use ($searchTerm) {
                $q->where('nombre', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('apellido_paterno', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('apellido_materno', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('tipo_capacitacion', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('nombre_capacitacion', 'LIKE', "%{$searchTerm}%")
                  ->orWhere('organismo', 'LIKE', "%{$searchTerm}%");
            });
        }

        // Obtener las capacitaciones con paginación
        $capacitaciones = $query->paginate(10);

        // Variables necesarias para la vista
        $user_roles = auth()->user()->user_roles->pluck('role.name')->toArray();
        $tipo_usuario = auth()->user()->tipo_usuario;

        return view('externa.datos', [
            'capacitaciones' => $capacitaciones,
            'user_roles' => $user_roles,
            'tipo_usuario' => $tipo_usuario,
            'search' => request('q')
        ]);
    }

    public function mis_capacitaciones()
{
    $user = auth()->user();
    $query = RegistroCapacitacionesExt::where('correo', $user->email);

    // Aplicar búsqueda si existe
    if (request()->has('q')) {
        $searchTerm = request('q');
        $query->where(function($q) use ($searchTerm) {
            $q->where('nombre', 'LIKE', "%{$searchTerm}%")
              ->orWhere('apellido_paterno', 'LIKE', "%{$searchTerm}%")
              ->orWhere('apellido_materno', 'LIKE', "%{$searchTerm}%")
              ->orWhere('tipo_capacitacion', 'LIKE', "%{$searchTerm}%")
              ->orWhere('nombre_capacitacion', 'LIKE', "%{$searchTerm}%")
              ->orWhere('organismo', 'LIKE', "%{$searchTerm}%");
        });
    }

    // Aplicar filtros adicionales si existen
    if (request()->filled('tipo_capacitacion')) {
        $query->where('tipo_capacitacion', request('tipo_capacitacion'));
    }

    if (request()->filled('anio')) {
        $query->where('anio', request('anio'));
    }

    // Obtener las capacitaciones con paginación
    $capacitaciones = $query->paginate(10);

    // Variables necesarias para la vista
    $user_roles = $user->user_roles->pluck('role.name')->toArray();
    $tipo_usuario = $user->tipo_usuario;

    return view('externa.datos', [
        'capacitaciones' => $capacitaciones,
        'user_roles' => $user_roles,
        'tipo_usuario' => $tipo_usuario,
        'search' => request('q'),
        'is_mis_capacitaciones' => true // Agregar este parámetro
    ]);
}
    public function destroy($id)
    {
        // Buscar la capacitación por ID
        $capacitacion = RegistroCapacitacionesExt::findOrFail($id);

        // Eliminar el archivo de evidencia del almacenamiento (si existe)
        if ($capacitacion->evidencia) {
            Storage::disk('public')->delete($capacitacion->evidencia);
        }

        // Eliminar el registro de la base de datos
        $capacitacion->delete();

        return redirect()->back()->with('success', 'Capacitación eliminada exitosamente');
    }

    public function filtrar(Request $request)
{
    $query = RegistroCapacitacionesExt::query();

    // DETECTAR SI ES "MIS CAPACITACIONES" por la URL
    $currentUrl = url()->current();
    $isMisCapacitaciones = str_contains($currentUrl, 'mis-capacitaciones') ||
                          str_contains($currentUrl, 'mis_capacitaciones');

    // Si es "Mis Capacitaciones", filtrar por el usuario actual
    if ($isMisCapacitaciones) {
        $user = auth()->user();
        $query->where('correo', $user->email);
    }

    // Aplicar búsqueda si existe
    if ($request->filled('q')) {
        $searchTerm = $request->q;
        $query->where(function($q) use ($searchTerm) {
            $q->where('nombre', 'LIKE', "%{$searchTerm}%")
              ->orWhere('apellido_paterno', 'LIKE', "%{$searchTerm}%")
              ->orWhere('apellido_materno', 'LIKE', "%{$searchTerm}%")
              ->orWhere('tipo_capacitacion', 'LIKE', "%{$searchTerm}%")
              ->orWhere('nombre_capacitacion', 'LIKE', "%{$searchTerm}%")
              ->orWhere('organismo', 'LIKE', "%{$searchTerm}%");
        });
    }

    // Aplicar filtro por tipo de capacitación si está seleccionado
    if ($request->filled('tipo_capacitacion')) {
        $query->where('tipo_capacitacion', $request->tipo_capacitacion);
    }

    // Aplicar filtro por año si está seleccionado
    if ($request->filled('anio')) {
        $query->where('anio', $request->anio);
    }

    // Obtener las capacitaciones con paginación
    $capacitaciones = $query->paginate(10);

    // Variables necesarias para la vista
    $user_roles = auth()->user()->user_roles->pluck('role.name')->toArray();
    $tipo_usuario = auth()->user()->tipo_usuario;

    return view('externa.datos', [
        'capacitaciones' => $capacitaciones,
        'user_roles' => $user_roles,
        'tipo_usuario' => $tipo_usuario,
        'search' => $request->q
    ]);
}
    public function create()
    {
        $userEmail = auth()->check() ? auth()->user()->email : null;
        return view('externa.formulario', compact('userEmail'));
    }

    public function actualizarStatus(Request $request, $id)
    {
        // Validar el número de registro si es necesario
        $request->validate([
        'comentario' => 'nullable|string|max:255',
        ]);

        // Obtener la capacitación por ID
        $capacitacion = RegistroCapacitacionesExt::findOrFail($id);

        // Actualizar el campo status dependiendo del botón seleccionado
        if ($request->has('action') && $request->action === 'comentario') {
            $capacitacion->status = $request->input('comentario');
        } elseif ($request->has('rechazado') && $request->rechazado === 'rechazado') {
            $capacitacion->status = 'Rechazado';
        }

        // Guardar los cambios en la base de datos
        $capacitacion->save();

        // Redirigir con un mensaje de éxito
        return redirect()->back()->with('success', 'El estado de la capacitación se ha actualizado correctamente.');
    }

    public function actualizarFolio(Request $request, $id)
    {
        // Validar el folio si es necesario
        $request->validate([
        'numero_folio' => 'nullable|string|max:255',
        ]);

        // Obtener la capacitación por ID
        $capacitacion = RegistroCapacitacionesExt::findOrFail($id);

        // Actualizar el campo status dependiendo del botón seleccionado
        if ($request->has('action') && $request->action === 'numero_folio') {
            // Concatenar el prefijo TNM-169- con la parte ingresada por el admin
            $numero_ingresado = $request->input('numero_folio');

            // Validar que no contenga formato de instructor (/I-)
            if (!empty($numero_ingresado) && strpos(strtoupper($numero_ingresado), '/I-') !== false) {
                return redirect()->back()->withErrors(['numero_folio' => 'No se permite el formato de instructor (/I-) en capacitaciones externas.']);
            }

            $capacitacion->folio = !empty($numero_ingresado) ? 'TNM-169-' . $numero_ingresado : null;
        } elseif ($request->has('rechazado') && $request->rechazado === 'rechazado') {
            $capacitacion->folio = 'Rechazado';
        }

        // Guardar los cambios en la base de datos
        $capacitacion->save();

        // Redirigir con un mensaje de éxito
        return redirect()->back()->with('success', 'El folio de la capacitación se ha actualizado correctamente.');
    }

    public function actualizarDatos(Request $request, $id)
    {
        // Validar los datos
        $request->validate([
            'comentario' => 'required|string|max:255',
            'numero_folio' => 'required|string|max:255',
        ]);

        // Buscar y actualizar la capacitación
        $capacitacion = RegistroCapacitacionesExt::findOrFail($id);
        $capacitacion->status = $request->comentario;
        $capacitacion->folio = $request->numero_folio;
        $capacitacion->save();

        // Redirigir con mensaje de éxito
        return redirect()->back()->with('success', 'Datos actualizados correctamente.');
    }
}
