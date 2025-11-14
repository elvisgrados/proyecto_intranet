<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    use HasFactory;

    protected $table = 'tutores';
    protected $primaryKey = 'id_tutor';
    public $timestamps = false;

    protected $fillable = ['id_usuario'];

    public function alumnos()
    {
        return $this->belongsToMany(Alumno::class, 'asignacion_tutor', 'id_tutor', 'id_alumno');
    }
}
