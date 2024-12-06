<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instructore extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'cvu',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'cursos_instructores', 'instructore_id', 'curso_id');
    }
}
