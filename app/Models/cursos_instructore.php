<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class cursos_instructore extends Model
{
    use HasFactory;

    protected $fillable = [
        'instructore_id',
        'curso_id',
        'ficha_tecnica',
        'numero_registro'
    ];
    public $timestamps = true;

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    public function instructor()
    {
        return $this->belongsTo(instructore::class, 'instructore_id');
    }
}
