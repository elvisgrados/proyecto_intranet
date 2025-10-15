<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model{
    use HasFactory;

    protected $table = 'cursos';
    protected $primaryKey = 'id_curso';
    public $timestamps = false;

    public function horarios(){
        return $this->hasMany(Horario::class, 'id_curso');
    }

    public function alumnos(){
        return $this->belongsToMany(Alumno::class, 'asignacion_alumno', 'id_curso', 'id_alumno');
    }
}
