<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modulo extends Model
{
    use HasFactory;

    protected $fillable = [
        'diplomado_id',
        'instructor_id',
        'numero',
        'nombre',
        'fecha_inicio',
        'fecha_termino'
    ];

    public function diplomado()
    {
        return $this->belongsTo(Diplomado::class);
    }

    public function instructore()
    {
        return $this->belongsTo(Instructore::class);
    }

    public function calificacionesModulos()
    {
        return $this->hasMany(Calificacion_modulo::class);
    }
}
