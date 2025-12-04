<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\EncuestaController;
use App\Http\Controllers\InicioController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\SolicitarCursoController;
use App\Http\Controllers\PeriodoController;
use App\Http\Controllers\CursoParticipanteController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\InstructorController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\DiplomadosController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\SolicitudesController;
use App\Http\Controllers\SolicitudInstructorController;
use App\Http\Controllers\ConstanciaCursoController;
use App\Http\Controllers\ConstanciaDiplomadoController;
use App\Http\Controllers\VerificacionPublicaController;
use App\Models\cursos_instructore;
use App\Models\Instructore;
use App\Models\User;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/inicio');
    }
    return view('auth.login');
});

Route::get('/inicio', [InicioController::class,'index'])->middleware('auth')->name('inicio');

Route::middleware('auth')->group(function () {

    // Perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Encuesta
    Route::get('/encuesta/{curso_id}', [EncuestaController::class, 'formulario'])->name('encuesta.formulario');
    Route::post('/encuesta', [EncuestaController::class, 'store'])->name('encuesta.store');

    // Cursos
    Route::get('/CursosDocente', [CursoController::class, 'docente_index'])->name('docente_cursos.index');

    // Cursos participantes
    Route::get('/CursosDisponibles', [CursoController::class, 'docente_index'])->name('cursos_disponibles.index');
    Route::get('/CursosCursando', [CursoParticipanteController::class, 'cursando_index'])->name('cursos_cursando.index');
    Route::get('/CursosTerminados', [CursoParticipanteController::class, 'terminados_index'])->name('cursos_terminados.index');
    Route::get('/CursoTerminado/{id}', [CursoParticipanteController::class, 'show'])->name('cursos_terminados.show');
    Route::post('/CursoParticipante', [CursoParticipanteController::class, 'store'])->name('curso_participante.store');

    Route::delete('/CursoParticipante/{participanteInscrito}', [CursoParticipanteController::class, 'destroy'])->name('curso_participante.destroy');

});

Route::middleware(['auth', 'role:admin,CAD'])->group(function (){

    // Usuarios
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register_user');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('store_user');
    Route::get('/usuario/editar/{id}', [UsuarioController::class, 'edit'])->name('usuario.edit');
    Route::put('/usuario/{usuario}', [UsuarioController::class, 'update'])->name('user.update');
    Route::put('/usuario/{id}/Activar', [UsuarioController::class, 'activar'])->name('usuario.activar');
    Route::put('/usuario/{id}/desactivar', [UsuarioController::class, 'desactivar'])->name('usuario.desactivar');

    // DNC
    Route::get('/Solicitudes', [SolicitarCursoController::class, 'admin_index'])->name('admin_solicitarcursos.index');
    Route::put('/solicitud/negar/{id}', [SolicitarCursoController::class, 'negar'])->name('negar_solicitud.update');

    // Cursos
    Route::get('/RegistrarCurso/{id}', [CursoController::class, 'create'])->name('cursos.create');
    Route::post('/Cursos', [CursoController::class, 'store'])->name('cursos.store');
    Route::get('/EditarCurso/{id}', [CursoController::class, 'edit'])->name('cursos.edit');
    Route::put('/Curso/{curso}', [CursoController::class, 'update'])->name('cursos.update');
    Route::put('/CursoTerminar/{curso}', [CursoController::class, 'terminar_curso'])->name('terminar_cursos.update');
    Route::put('/CursoIniciar/{curso}', [CursoController::class, 'iniciar_curso'])->name('iniciar_cursos.update');
    Route::delete('/Curso/{curso}', [CursoController::class, 'destroy'])->name('cursos.destroy');

    Route::get('/admin/entregar-calificacion/{id}', [CursoController::class, 'entregar_calificaciones'])->name('admin.entregar_calificacion');
    Route::get('/admin/devolver-calificacion/{id}', [CursoController::class, 'devolver_calificaciones'])->name('admin.devolver_calificacion');

    Route::get('/curso/{curso_id}/pdf', [CursoController::class, 'generarPDF'])->name('curso.pdf');
    Route::get('/cursos/estadisticas', [CursoController::class, 'estadisticas_index'])->name('cursos_estadisticas.index');
    Route::get('/cursos/estadisticas/{anio}', [CursoController::class, 'estadisticas_show'])->name('cursos_estadisticas.show');

    // Constancias de Cursos
    Route::get('/curso/{curso_id}/constancia/{participante_id}', [ConstanciaCursoController::class, 'generarPDF'])->name('curso.constancia');
    Route::get('/curso/{curso_id}/reconocimiento-instructor/{instructor_id}', [ConstanciaCursoController::class, 'generarReconocimientoInstructor'])->name('curso.reconocimiento.instructor');


    //Encuesta
    Route::get('/encuestas/resultados/{curso_id}', [EncuestaController::class, 'resultados'])->name('encuesta.resultados');
});

Route::middleware(['auth', 'role:Jefe Departamento,admin,CAD'])->group(function (){
    // DNC
    Route::get('/SolicitudCurso/{id}', [SolicitarCursoController::class, 'show'])->name('solicitarcursos.show');
});

Route::middleware(['auth', 'role:Jefe Departamento'])->group(function (){

    // DNC
    Route::get('/SolicitarCursos', [SolicitarCursoController::class, 'create'])->name('solicitarcursos.create');
    Route::post('/SolicitarCursos', [SolicitarCursoController::class, 'store'])->name('solicitarcursos.store');
    Route::get('/MisSolicitudes', [SolicitarCursoController::class, 'jefe_departamento_index'])->name('jefe_solicitarcursos.index');
    Route::delete('/SolicitarCursos/{id}', [SolicitarCursoController::class, 'destroy'])->name('solicitarcursos.destroy');
});

Route::middleware(['auth', 'role:admin,CAD,Jefe Departamento,Subdirector Academico'])->group(function () {

    // Usuarios
    Route::get('/usuarios', [UsuarioController::class, 'index'])->name('usuarios.index');
    Route::get('/usuarios/datos/{id}', [UsuarioController::class, 'show'])->name('usuario_datos.index');

    // Cursos
    Route::get('/Cursos', [CursoController::class, 'index'])->name('cursos.index');
    Route::get('/Cursos', [CursoController::class, 'index'])->name('cursos.index');
    Route::get('/Curso/{id}', [CursoController::class, 'show'])->name('cursos.show');

    // Periodos
    Route::resource('periodos', PeriodoController::class);

    // Departamentos
    Route::resource('departamentos', DepartamentoController::class);
});

Route::middleware(['auth', 'role:Instructor'])->group(function (){

    // Instructores
    //Route::resource('instructor', InstructorController::class);

    Route::get('/instructor/cursos', [InstructorController::class, 'index'])->name('instructor.index');
    Route::get('/instructor/curso/{id}', [InstructorController::class, 'show'])->name('instructor.show');
    Route::get('/instructor/calificar/{id}', [InstructorController::class, 'edit'])->name('instructor.edit');
    Route::put('/instructor/calificar/{cursos_participante}', [InstructorController::class, 'update'])->name('instructor.update');
    Route::get('/instructor/subir-calificacion/{id}', [InstructorController::class, 'subir_calificaciones'])->name('instructor.subir_calificacion');
    Route::post('/curso/{curso_id}/subir-ficha-tecnica', [InstructorController::class, 'subir_fichatecnica'])->name('curso.subir_fichatecnica');
});

// =====================================================
// RUTAS DEL MÓDULO DE CAPACITACIÓN EXTERNA
// =====================================================

Route::middleware('auth')->group(function () {
    // Rutas principales del módulo externo
    Route::prefix('externa')->name('externa.')->group(function () {
        // Vista principal del módulo externo
        Route::get('/', function () {
            return view('externa.dashboard');
        })->name('index');

        // Gestión de capacitaciones externas
        Route::get('/datos', [App\Http\Controllers\RegistroCapacitacionesExtController::class, 'index'])->name('datos');
        Route::get('/formulario', [App\Http\Controllers\RegistroCapacitacionesExtController::class, 'create'])->name('formulario');
        Route::post('/store', [App\Http\Controllers\RegistroCapacitacionesExtController::class, 'store'])->name('store');
        Route::get('/mis-capacitaciones', [App\Http\Controllers\RegistroCapacitacionesExtController::class, 'mis_capacitaciones'])->name('mis_capacitaciones');
        Route::get('/filtrar', [App\Http\Controllers\RegistroCapacitacionesExtController::class, 'filtrar'])->name('filtrar');
        Route::delete('/eliminar/{id}', [App\Http\Controllers\RegistroCapacitacionesExtController::class, 'destroy'])->name('destroy');
        Route::put('/actualizar-status/{id}', [App\Http\Controllers\RegistroCapacitacionesExtController::class, 'actualizarStatus'])->name('actualizar.status');
        Route::put('/actualizar-folio/{id}', [App\Http\Controllers\RegistroCapacitacionesExtController::class, 'actualizarFolio'])->name('actualizar.folio');
        Route::post('/actualizar-datos/{id}', [App\Http\Controllers\RegistroCapacitacionesExtController::class, 'actualizarDatos'])->name('actualizarDatos');

        // Constancias PDF
        Route::get('/constancia/{id}', [App\Http\Controllers\ConstanciaController::class, 'generarPDF'])->name('constancia');
    });
});

// =====================================================
// RUTAS DEL MÓDULO DE DIPLOMADOS
// =====================================================

Route::post('/diplomados/guardar-registro', [DiplomadosController::class, 'guardarRegistro'])
    ->name('diplomados.guardarRegistro');

Route::post('/guardar-numero-registro', [DiplomadosController::class, 'guardarNumeroRegistro'])
    ->name('guardar.numero.registro');

Route::middleware('auth')->group(function () {
    // Rutas principales del módulo de diplomados
    Route::prefix('diplomados')->name('diplomados.')->group(function () {
        // Vista principal del módulo de diplomados
        Route::get('/', function () {
            return view('diplomados.dashboard', [
                'user' => auth()->user()
            ]);
        })->name('index');

        // Ofertas de diplomados (visible para todos)
        Route::get('/oferta', [DiplomadosController::class, 'mostrarOferta'])->name('oferta');

        // Solicitudes generales
        Route::get('/solicitudes', [SolicitudesController::class, 'solicitudes'])->name('solicitudes');

        // Solicitudes para participantes (docentes)
        Route::post('/solicitar-docente/{diplomado}', [SolicitudesController::class, 'solicitar_Docente_Oferta'])->name('solicitar_docente_oferta.store');

        // Solicitudes para instructores
        Route::post('/solicitar-instructor/{diplomado}', [SolicitudesController::class, 'solicitar_instructor_Oferta'])->name('solicitar_instructor_oferta.store');

        // Diplomados en curso y terminados para participantes (docentes)
        Route::get('/en-curso/docente', [DiplomadosController::class, 'curso_docente'])->name('curso_docente');
        Route::get('/terminados/docente', [DiplomadosController::class, 'terminado_docente'])->name('terminado_docente');
        Route::get('/detalle/participante/{id}', [DiplomadosController::class, 'detalles_participante'])->name('detalles_participante');

        // Detalle general de diplomados (disponible para todos)
        Route::get('/detalle/{id}', [DiplomadosController::class, 'detalles'])->name('detalle');

        // Rutas para instructores
        Route::middleware(['role:Instructor'])->group(function () {
            Route::get('/en-curso/instructor', [DiplomadosController::class, 'curso_instructor'])->name('curso_instructor');
            Route::get('/terminados/instructor', [DiplomadosController::class, 'terminado_instructor'])->name('terminado_instructor');
            Route::get('/detalle/instructor/{id}', [DiplomadosController::class, 'detalles_instructor'])->name('detalles_instructor');
            Route::get('/modulo/detalle/{modulo}', [ModuloController::class, 'detalles_modulo'])->name('detalle.modulo.participantes');
            Route::post('/calificar/participante', [ModuloController::class, 'actualizar_calificacion_participante'])->name('actualizar.calificacion.modulo.participante');
        });

        // Rutas administrativas (admin, CAD, Jefe Departamento, Subdirector)
        Route::middleware(['role:admin,CAD,Jefe Departamento,Subdirector Academico'])->group(function () {
            // Gestión de diplomados
            Route::get('/diplomados', [DiplomadosController::class, 'index'])->name('diplomados.index');
            Route::get('/diplomados/crear', [DiplomadosController::class, 'create'])->name('diplomados.create');
            Route::post('/diplomados', [DiplomadosController::class, 'store'])->name('diplomados.store');
            Route::get('/diplomados/{id}/editar', [DiplomadosController::class, 'edit'])->name('diplomados.edit');
            Route::put('/diplomados/{id}', [DiplomadosController::class, 'update'])->name('diplomados.update');
            Route::delete('/diplomados/{id}', [DiplomadosController::class, 'destroy'])->name('diplomados.destroy');

            // Gestión de módulos
            Route::get('/modulos/crear/{id}', [ModuloController::class, 'create'])->name('modulos.create');
            Route::post('/modulos', [ModuloController::class, 'store'])->name('modulos.store');
            Route::get('/modulos/{id}/editar', [ModuloController::class, 'edit'])->name('modulos.edit');
            Route::put('/modulos/{id}', [ModuloController::class, 'update'])->name('modulos.update');

            // Gestión de solicitudes
            Route::get('/solicitudes/{id}', [SolicitudesController::class, 'index'])->name('solicitudes_diplomado.index');
            Route::put('/solicitud-docente/aceptar/{id}', [SolicitudesController::class, 'aceptar_docente'])->name('solicitudes_aceptar_docente');
            Route::put('/solicitud-docente/negar/{id}', [SolicitudesController::class, 'negar_docente'])->name('solicitudes_negar_docente');
            Route::put('/solicitud-instructor/aceptar/{id}', [SolicitudesController::class, 'aceptar_instructor'])->name('solicitudes_aceptar_instructor');
            Route::put('/solicitud-instructor/negar/{id}', [SolicitudesController::class, 'negar_instructor'])->name('solicitudes_negar_instructor');
        });

        // Rutas exclusivas para admin y CAD
        Route::middleware(['role:admin,CAD'])->group(function () {
            // Docentes inscritos por diplomado
            Route::get('/docentes-inscritos/{id}', [DiplomadosController::class, 'docentesInscritos'])->name('docentes_inscritos');

            // Constancias de diplomados
            Route::get('/constancia/{diplomado_id}/{participante_id}/{tipo}', [ConstanciaDiplomadoController::class, 'generarPDF'])->name('constancia');
        });

        // Vistas estáticas adicionales (temporal)
        Route::get('/en-curso', function () {
            return view('diplomados.participante.en_curso');
        })->name('en_curso');

        Route::get('/historial', function () {
            return view('historial');
        })->name('historial');

        Route::get('/progreso', function () {
            return view('progreso');
        })->name('progreso');

        Route::get('/calificar', function () {
            return view('calificar');
        })->name('calificar');
    });
});

// Rutas para usar los nombres originales manteniendo compatibilidad
Route::middleware('auth')->group(function () {
    Route::get('/capacitacionesext', [App\Http\Controllers\RegistroCapacitacionesExtController::class, 'index'])->name('capacitacionesext.index');
    Route::get('/capacitacionesext/crear', [App\Http\Controllers\RegistroCapacitacionesExtController::class, 'create'])->name('capacitacionesext.create');
    Route::post('/capacitacionesext', [App\Http\Controllers\RegistroCapacitacionesExtController::class, 'store'])->name('capacitacionesext.store');
    Route::get('/capacitacionesext/filtrar', [App\Http\Controllers\RegistroCapacitacionesExtController::class, 'filtrar'])->name('capacitacionesext.filtrar');
    Route::delete('/capacitacionesext/{id}', [App\Http\Controllers\RegistroCapacitacionesExtController::class, 'destroy'])->name('capacitacionesext.destroy');
    Route::put('/capacitacionesext/actualizar-status/{id}', [App\Http\Controllers\RegistroCapacitacionesExtController::class, 'actualizarStatus'])->name('capacitacionesext.actualizarStatus');
    Route::put('/capacitacionesext/actualizar-folio/{id}', [App\Http\Controllers\RegistroCapacitacionesExtController::class, 'actualizarFolio'])->name('capacitacionesext.actualizarFolio');
    Route::post('/capacitacionesext/actualizar-datos/{id}', [App\Http\Controllers\RegistroCapacitacionesExtController::class, 'actualizarDatos'])->name('capacitacionesext.actualizarDatos');
    Route::get('/capacitacionesext/constancia/{id}', [App\Http\Controllers\ConstanciaController::class, 'generarPDF'])->name('capacitacionesext.constancia');
});

// =====================================================
// RUTAS PÚBLICAS PARA VERIFICACIÓN DE DOCUMENTOS QR
// =====================================================

// Rutas públicas (sin middleware auth) para verificación de documentos
Route::get('/verificar-constancia/{numero}', [VerificacionPublicaController::class, 'verificarConstancia'])->name('verificacion.constancia')->where('numero', '.*');
Route::get('/verificar-reconocimiento/{numero}', [VerificacionPublicaController::class, 'verificarReconocimiento'])->name('verificacion.reconocimiento')->where('numero', '.*');
Route::get('/verificar-diploma/{numero}', [VerificacionPublicaController::class, 'verificarDiploma'])->name('verificacion.diploma')->where('numero', '.*');

// Ruta general de verificación (detecta automáticamente el tipo)
Route::get('/verificar/{numero}', [VerificacionPublicaController::class, 'verificarDocumento'])->name('verificacion.general')->where('numero', '.*');



require __DIR__.'/auth.php';

// Incluir rutas de prueba para QR (comentar en producción)
if (app()->environment('local')) {
    require __DIR__.'/test-qr.php';
}
