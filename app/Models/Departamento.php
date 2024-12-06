<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function cursos()
    {
        return $this->hasMany(Curso::class);
    }

    // Relación uno a muchos con el modelo DatosGenerales
    public function datosGenerales()
    {
        return $this->hasMany(Datos_generale::class);
    }
}
