<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'anio',
        'trimestre',
        'archivo_fondo'
    ];

    public function cursos()
    {
        return $this->hasMany(Curso::class);
    }
}
