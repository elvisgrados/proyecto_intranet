<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    protected $table = 'cursos';
    protected $primaryKey = 'id_curso';
    public $timestamps = false;

    protected $fillable = [
        'id_periodo',
        'nombre',
        'descripcion'
    ];

    public function horarios(){
        return $this->hasMany(Horario::class, 'id_curso');
    }

    public function temas(){
        return $this->hasMany(Tema::class, 'id_curso');
    }

    public function autoevaluaciones(){
        return $this->hasMany(Autoevaluacion::class, 'id_curso');
    }

    public function asignacionesDocente(){
        return $this->hasMany(AsignacionDocente::class, 'id_curso', 'id_curso');
    }

    public function docentes(){
        return $this->hasOneThrough(
            Docente::class,               // Modelo final
            AsignacionDocente::class,     // Tabla intermedia
            'id_curso',                   // FK en AsignacionDocente
            'id_docente',                 // FK en Docente
            'id_curso',                   // Local key en Curso
            'id_docente'                  // Local key en AsignacionDocente
        );
    }
    
    public function alumnos(){
        return $this->belongsToMany(Alumno::class, 'asignacion_alumno', 'id_curso', 'id_alumno');
    }

    public function alumnosMatriculados()
    {
        return $this->belongsToMany(Alumno::class, 'alumno_curso', 'id_curso', 'id_alumno', 'id_curso', 'id_alumno')
                    ->withPivot('id_matricula','id_periodo','fecha_matricula');
    }

    public function asistencias(){
        return $this->hasMany(Asistencia::class, 'id_curso', 'id_curso');
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class, 'id_periodo', 'id_periodo');
    }
}