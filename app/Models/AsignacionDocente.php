<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AsignacionDocente extends Model{
    use HasFactory;

    protected $table = 'asignacion_docente';
    protected $primaryKey = 'id_asignacion';
    public $timestamps = false;

    public function curso(){
        return $this->belongsTo(Curso::class, 'id_curso');
    }

    public function docente(){
        return $this->belongsTo(Docente::class, 'id_docente');
    }
}
