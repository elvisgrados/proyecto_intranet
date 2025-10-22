<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;



class Curso extends Model
{
    protected $table = 'cursos';
    protected $primaryKey = 'id_curso';
    public $timestamps = false;

    public function horarios()
    {
        return $this->hasMany(Horario::class, 'id_curso');
    }

    public function temas()
    {
        return $this->hasMany(Tema::class, 'id_curso');
    }

    public function autoevaluaciones()
    {
        return $this->hasMany(Autoevaluacion::class, 'id_curso');
    }

    public function docentes()
    {
        return $this->belongsToMany(Docente::class, 'asignacion_docente', 'id_curso', 'id_docente');
    }
    
    public function alumnos(){
        return $this->belongsToMany(Alumno::class, 'asignacion_alumno', 'id_curso', 'id_alumno');
    }
}

