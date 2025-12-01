<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\models\Curso;
use App\models\CursoInscrito;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable

{
    
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'tipo',
        'estatus'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function cursosinscritos()
    {
        return $this->hasMany(cursos_participante::class);
    }
    public function departamento()
    {
        return $this->belongsTo(departamento::class);
    }
    public function participante()
    {
        return $this->hasOne(Participante::class, 'user_id');
    }
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id')->withTimestamps();
    }
    public function user_roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }
    public function instructor()
    {
        return $this->hasOne(Instructore::class);
    }
    public function datos_generales()
    {
        return $this->hasOne(Datos_generale::class);
    }

    public static function boot()
    {
        parent::boot();

        // Listener para el evento "created"
        static::created(function ($user) {
            // Crear un registro en historial_usuarios al crear un usuario
            DB::table('historial_usuarios')->insert([
                'user_id' => $user->id,
                'tipo' => $user->tipo,
                'estatus' => 1,
                'fecha_inicio' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        });

        // Listener para el evento "updating"
        static::updating(function ($user) {
            $dirty = $user->getDirty(); // Obtiene los campos modificados

            // Verificar si los campos 'tipo' o 'estatus' cambiaron
            if (array_key_exists('tipo', $dirty) || array_key_exists('estatus', $dirty)) {
                $actual = $user->fresh(); // Valores actuales antes de la actualización

                // Cerrar el registro activo actual
                DB::table('historial_usuarios')
                    ->where('user_id', $actual->id)
                    ->whereNull('fecha_fin')
                    ->update(['fecha_fin' => now()]);

                // Crear un nuevo registro en el historial
                DB::table('historial_usuarios')->insert([
                    'user_id' => $actual->id,
                    'tipo' => $user->tipo,
                    'estatus' => $user->estatus,
                    'fecha_inicio' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        });
    }
}
