<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class solicitud_docente extends Model
{
    use HasFactory;

    protected $fillable = [
        'diplomado_id',
        'participante_id',
        'estatus',
        'carta_compromiso',
        'numero_registro',];

    public function diplomado()
    {
        return $this->belongsTo(Diplomado::class);
    }

    public function participante()
    {
        return $this->belongsTo(Participante::class);
    }
}
