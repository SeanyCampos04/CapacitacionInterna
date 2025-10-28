<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificacion_modulo extends Model
{
    use HasFactory;

    protected $fillable = [
        'participante_id',
        'modulo_id',
        'diplomado_id',
        'calificacion'
    ];

    public function participante()
    {
        return $this->belongsTo(Participante::class);
    }

    public function modulo()
    {
        return $this->belongsTo(Modulo::class);
    }

    public function diplomado()
    {
        return $this->belongsTo(Diplomado::class);
    }
}
