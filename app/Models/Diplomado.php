<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diplomado extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'objetivo',
        'tipo',
        'clase',
        'sede',
        'responsable',
        'correo_contacto',
        'inicio_oferta',
        'termino_oferta',
        'inicio_realizacion',
        'termino_realizacion',
    ];

    public function solicitudDocentes()
    {
        return $this->hasMany(solicitud_docente::class);
    }
    public function solicitud_instructor()
    {
        return $this->hasMany(solicitud_instructore::class, 'diplomado_id');
    }

    public function participante()
    {
        return $this->belongsToMany(Participante::class, 'solicitud_docente', 'diplomado_id', 'participante_id');
    }

    public function instructore()
    {
        return $this->belongsToMany(Instructore::class, 'solicitud_instructor', 'diplomado_id', 'intructore_id');
    }
    public function solicitudesParticipantes()
    {
        return $this->hasMany(solicitud_docente::class);
    }

    public function solicitudesInstructores()
    {
        return $this->hasMany(solicitud_instructore::class);
    }
    public function calificacionesModulos()
    {
        return $this->hasMany(Calificacion_modulo::class);
    }

    public function modulos()
    {
        return $this->hasMany(Modulo::class);
    }
}
