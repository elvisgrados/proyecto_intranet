<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $table = 'horarios';
    protected $primaryKey = 'id_horario';
    public $timestamps = false;

    public function curso(){
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }

    // Si quieres acceder directamente al docente desde horario:
    public function docente()
    {
        return $this->hasOneThrough(
            Docente::class,
            AsignacionDocente::class,
            'id_curso',     // Foreign key en AsignacionDocente
            'id_docente',   // Foreign key en Docente
            'id_curso',     // Local key en Horario
            'id_docente'    // Local key en AsignacionDocente
        );
    }
}
