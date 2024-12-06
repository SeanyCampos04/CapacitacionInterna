<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class Curso extends Model
{
    use HasFactory;

    protected $fillable = [
        'departamento_id',
        'nombre',
        'fdi',
        'fdf',
        'objetivo',
        'modalidad',
        'lugar',
        'horario',
        'duracion',
        'no_registro',
        'periodo_id',
        'tipo',
        'es_tics',
        'es_tutorias',
        'clase',
        "limite_participantes",
        "estatus",
        "estado_calificacion"
    ];


    public function cursos_participantes()
    {
        return $this->hasMany(Cursos_Participante::class, 'curso_id');
    }
    public function cursos_instructores()
    {
        return $this->hasMany(cursos_instructore::class, 'curso_id');
    }
    public function participantes()
    {
        return $this->belongsToMany(Participante::class, 'cursos_participantes', 'curso_id', 'participante_id');
    }
    public function instructores()
    {
        return $this->belongsToMany(Instructore::class, 'cursos_instructores', 'curso_id', 'instructore_id');
    }
    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }
    public function periodo()
    {
        return $this->belongsTo(Periodo::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
