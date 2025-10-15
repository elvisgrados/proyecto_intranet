<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumno extends Model{
    use HasFactory;

    protected $table = 'alumnos';
    protected $primaryKey = 'id_alumno';
    public $timestamps = false;

    protected $fillable = [
        'id_usuario',
        'colegio_procedencia',
        'id_modalidad',
        'id_carrera'
    ];

    // 🔹 Relación con Usuario (cada alumno pertenece a un usuario)
    public function usuario(){
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    // 🔹 Relación con Cursos (muchos a muchos)
    public function cursos(){
        return $this->belongsToMany(Curso::class, 'asignacion_alumno', 'id_alumno', 'id_curso');
    }

    // 🔹 Relación con Modalidad
    public function modalidad(){
        return $this->belongsTo(Modalidad::class, 'id_modalidad');
    }

    // 🔹 Relación con Carrera
    public function carrera(){
        return $this->belongsTo(Carrera::class, 'id_carrera');
    }
}
