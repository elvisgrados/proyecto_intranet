<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AlumnoCurso extends Model
{
    protected $table = 'alumno_curso';
    protected $primaryKey = 'id_matricula';
    public $timestamps = false;
    protected $fillable = ['id_alumno','id_curso','fecha_matricula','id_periodo'];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class,'id_alumno','id_alumno');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class,'id_curso','id_curso');
    }

    public function periodo()
    {
        return $this->belongsTo(Periodo::class,'id_periodo','id_periodo');
    }
}
