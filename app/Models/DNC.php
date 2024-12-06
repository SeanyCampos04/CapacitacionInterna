<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class dnc extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'departamento_id',
        'nombre',
        'prioridad',
        'objetivo',
        'instructor_propuesto',
        'contacto_propuesto',
        'num_participantes',
        'origen',
        'requerimientos'
    ];

    public function user()
    {
        return $this->hasMany(User::class, 'user_id');
    }

    public function departamento()
    {
        return $this->hasMany(Departamento::class, 'departamento_id');
    }
}
