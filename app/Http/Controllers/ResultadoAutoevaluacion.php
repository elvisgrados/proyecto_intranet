<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResultadoAutoevaluacion extends Model
{
    protected $table = 'resultados_autoevaluacion';
    protected $primaryKey = 'id_resultado';
    public $timestamps = false;

    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'id_alumno');
    }

    public function autoevaluacion()
    {
        return $this->belongsTo(Autoevaluacion::class, 'id_autoevaluacion');
    }
}
