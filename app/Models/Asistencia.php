<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    protected $table = 'asistencias';
    protected $primaryKey = 'id_asistencia';
    public $timestamps = false;

    protected $fillable = [
        'id_alumno',
        'id_curso',
        'fecha',
        'estado',
        'observacion',
        'puntaje'
    ];
}
