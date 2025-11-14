<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public $timestamps = false;

    protected $fillable = [
        'id_tipo',
        'dni',
        'nombres',
        'apellidos',
        'email',
        'password',
        'telefono',
        'foto',
        'estado',
        'fecha_registro'
    ];

    protected $hidden = ['
        password'
    ];

    public function usuario(){
        return $this->BelongsTo(Usuario::class, 'id_usuario', 'id');
    }

    public function alumno(){
        return $this->hasOne(Alumno::class, 'id_usuario', 'id_usuario');
    }
}