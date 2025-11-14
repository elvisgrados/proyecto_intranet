<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    protected $table = 'notas';
    protected $primaryKey = 'id_nota';
    public $timestamps = false;
    protected $fillable = ['id_alumno','id_curso','evaluacion','puntaje','fecha_registro'];

    public function alumno()
    {
        return $this->belongsTo(Alumno::class,'id_alumno','id_alumno');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class,'id_curso','id_curso');
    }
}
