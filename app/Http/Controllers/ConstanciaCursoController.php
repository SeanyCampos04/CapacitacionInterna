<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\cursos_participante;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ConstanciaCursoController extends Controller
{
    private function calcularNumeroDelCurso($curso)
    {
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

        return $numeroDelCurso + 1;
    }

    private function generarNumeroRegistroParticipante($curso, $participanteId)
    {
        $numeroDelCurso = $this->calcularNumeroDelCurso($curso);

        // Obtener participantes acreditados del curso ordenados por ID
        $participantesAcreditados = cursos_participante::where('curso_id', $curso->id)
            ->where('acreditado', 2)
            ->orderBy('id')
            ->get();

        // Verificar si existe el participante en los acreditados
        $participanteEncontrado = $participantesAcreditados->where('id', $participanteId)->first();

        if (!$participanteEncontrado) {
            $idsDisponibles = $participantesAcreditados->pluck('id')->toArray();
            throw new \Exception('Participante no encontrado entre los acreditados. ID buscado: ' . $participanteId . '. IDs disponibles: ' . implode(', ', $idsDisponibles) . '. Total acreditados: ' . $participantesAcreditados->count());
        }

        $numeroParticipante = $participantesAcreditados->search(function ($p) use ($participanteId) {
            return $p->id == $participanteId;
        });

        return sprintf('TNM-169-%02d-%s/%03d',
            $numeroDelCurso,
            $curso->periodo->anio,
            $numeroParticipante + 1
        );
    }

    private function generarNumeroRegistroInstructor($curso, $instructorId)
    {
        $numeroDelCurso = $this->calcularNumeroDelCurso($curso);

        // Obtener instructores del curso ordenados por ID
        $instructores = $curso->instructores->sortBy('id');

        // Verificar si existe el instructor en el curso
        $instructorEncontrado = $instructores->where('id', $instructorId)->first();

        if (!$instructorEncontrado) {
            $idsDisponibles = $instructores->pluck('id')->toArray();
            throw new \Exception('Instructor no encontrado en el curso. ID buscado: ' . $instructorId . '. IDs disponibles: ' . implode(', ', $idsDisponibles) . '. Total instructores: ' . $instructores->count());
        }

        $numeroInstructor = $instructores->search(function ($i) use ($instructorId) {
            return $i->id == $instructorId;
        });

        return sprintf('TNM-169-%02d-%s/I-%02d',
            $numeroDelCurso,
            $curso->periodo->anio,
            $numeroInstructor + 1
        );
    }

    public function generarPDF($curso_id, $participante_id)
    {
        // Recuperar los datos del curso
        $curso = Curso::with([
            'instructores.user.datos_generales',
            'departamento',
            'periodo'
        ])->findOrFail($curso_id);

        // Recuperar los datos del participante inscrito
        $participanteInscrito = cursos_participante::with([
            'participante.user.datos_generales'
        ])->where('curso_id', $curso_id)
          ->where('id', $participante_id)
          ->where('acreditado', 2) // Solo participantes acreditados
          ->firstOrFail();

        // Verificar que las relaciones existan
        if (!$participanteInscrito->participante) {
            throw new \Exception('No se encontraron datos del participante.');
        }
        if (!$participanteInscrito->participante->user) {
            throw new \Exception('No se encontraron datos del usuario del participante.');
        }
        if (!$participanteInscrito->participante->user->datos_generales) {
            throw new \Exception('No se encontraron datos generales del participante.');
        }

        // Generar número de registro
        $numeroRegistro = $this->generarNumeroRegistroParticipante($curso, $participante_id);

        // Actualizar el número de registro en la base de datos
        $participanteInscrito->update(['numero_registro' => $numeroRegistro]);

        // Generar código QR descargando imagen de API externa
        $urlVerificacion = route('verificacion.constancia', $numeroRegistro);
        $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=' . urlencode($urlVerificacion);

        // Descargar la imagen QR y convertirla a base64
        try {
            $qrImageData = file_get_contents($qrApiUrl);
            $codigoQR = 'data:image/png;base64,' . base64_encode($qrImageData);
        } catch (\Exception $e) {
            $codigoQR = null; // Si falla, no mostrar QR
        }

        // Obtener imagen de fondo del periodo asociado al curso
        $imagenFondo = null;
        if ($curso->periodo && $curso->periodo->archivo_fondo) {
            $imagenFondo = public_path('storage/' . $curso->periodo->archivo_fondo);
        }

        // Datos para la constancia
        $datos = [
            'curso' => $curso,
            'participante' => $participanteInscrito, // Para compatibilidad con la plantilla
            'calificacion' => $participanteInscrito->calificacion,
            'fecha_actual' => Carbon::now(),
            'tipoUsuario' => 'Participante', // En cursos siempre son participantes
            'numeroRegistro' => $numeroRegistro,
            'codigoQR' => $codigoQR,
            'imagenFondo' => $imagenFondo
        ];

        // Generar el PDF con la vista y los datos
        $pdf = Pdf::loadView('vistas.cursos.pdf.constancia', $datos);

        // Nombre del archivo con verificación segura
        $nombreParticipante = $participanteInscrito->participante->user->datos_generales->nombre ?? 'Participante';
        $nombreArchivo = 'Constancia_' .
            str_replace(' ', '_', $curso->nombre) . '_' .
            str_replace(' ', '_', $nombreParticipante) . '.pdf';

        // Retornar el PDF para descargar o visualizar
        return $pdf->stream($nombreArchivo);
    }

    public function generarReconocimientoInstructor($curso_id, $instructor_id)
    {
        // Recuperar los datos del curso
        $curso = Curso::with([
            'instructores.user.datos_generales',
            'departamento',
            'periodo'
        ])->findOrFail($curso_id);

        // Recuperar los datos del instructor específico
        $instructorCurso = $curso->instructores->where('id', $instructor_id)->first();

        if (!$instructorCurso) {
            abort(404, 'Instructor no encontrado en este curso');
        }

        // Verificar que las relaciones del instructor existan
        if (!$instructorCurso->user) {
            throw new \Exception('No se encontraron datos del usuario del instructor.');
        }
        if (!$instructorCurso->user->datos_generales) {
            throw new \Exception('No se encontraron datos generales del instructor.');
        }

        // Generar número de registro
        $numeroRegistro = $this->generarNumeroRegistroInstructor($curso, $instructor_id);

        // Buscar la relación curso-instructor y actualizar el número de registro
        $cursoInstructor = \App\Models\cursos_instructore::where('curso_id', $curso_id)
            ->where('instructore_id', $instructor_id)
            ->first();

        if ($cursoInstructor) {
            $cursoInstructor->update(['numero_registro' => $numeroRegistro]);
        }

        // Generar código QR descargando imagen de API externa
        $urlVerificacion = route('verificacion.reconocimiento', $numeroRegistro);
        $qrApiUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=100x100&data=' . urlencode($urlVerificacion);

        // Descargar la imagen QR y convertirla a base64
        try {
            $qrImageData = file_get_contents($qrApiUrl);
            $codigoQR = 'data:image/png;base64,' . base64_encode($qrImageData);
        } catch (\Exception $e) {
            $codigoQR = null; // Si falla, no mostrar QR
        }

        // Obtener imagen de fondo del periodo asociado al curso
        $imagenFondo = null;
        if ($curso->periodo && $curso->periodo->archivo_fondo) {
            $imagenFondo = public_path('storage/' . $curso->periodo->archivo_fondo);
        }

        // Datos para el reconocimiento
        $datos = [
            'curso' => $curso,
            'participante' => $instructorCurso, // Para compatibilidad con la plantilla (instructor como "participante")
            'fecha_actual' => Carbon::now(),
            'tipoUsuario' => 'Instructor', // Para instructores es reconocimiento
            'numeroRegistro' => $numeroRegistro,
            'codigoQR' => $codigoQR,
            'imagenFondo' => $imagenFondo
        ];

        // Generar el PDF con la vista y los datos
        $pdf = Pdf::loadView('vistas.cursos.pdf.constancia', $datos);

        // Nombre del archivo con verificación segura
        $nombreInstructor = $instructorCurso->user->datos_generales->nombre ?? 'Instructor';
        $nombreArchivo = 'Reconocimiento_' .
            str_replace(' ', '_', $curso->nombre) . '_' .
            str_replace(' ', '_', $nombreInstructor) . '.pdf';

        // Retornar el PDF para descargar o visualizar
        return $pdf->stream($nombreArchivo);
    }
}
