<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RegistroCapacitacionesExt extends Model
{
    protected $table = 'registrocapacitacionesext';

    protected $fillable = [
        'correo',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'tipo_capacitacion',
        'nombre_capacitacion',
        'anio',
        'organismo',
        'horas',
        'evidencia',
        'fecha_inicio',
        'fecha_termino',
        'status',
        'folio',
    ];
}
