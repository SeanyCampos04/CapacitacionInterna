<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class solicitud_instructore extends Model
{
    use HasFactory;

    protected $table = 'solicitud_instructores';

    protected $fillable = [
        'diplomado_id',
        'instructore_id',
        'estatus',
        'carta_terminacion',
        'numero_registro',
    ];

    public function diplomado()
    {
        return $this->belongsTo(Diplomado::class, 'diplomado_id');
    }

    public function instructore()
    {
        return $this->belongsTo(Instructore::class, 'instructore_id');
    }
}
