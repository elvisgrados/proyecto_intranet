<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model{
    use HasFactory;

    protected $table = 'docentes';
    protected $primaryKey = 'id_docente';
    public $timestamps = false;

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    public function asignaciones()
    {
        return $this->hasMany(AsignacionDocente::class, 'id_docente');
    }

    public function cursos()
    {
        return $this->belongsToMany(Curso::class, 'asignacion_docente', 'id_docente', 'id_curso');
    }

}