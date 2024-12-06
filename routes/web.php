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

require __DIR__.'/auth.php';
