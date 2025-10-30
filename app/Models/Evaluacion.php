<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Autoevaluacion extends Model
{
    protected $table = 'autoevaluaciones';
    protected $primaryKey = 'id_autoevaluacion';
    public $timestamps = false;

    protected $fillable = [
        'id_curso',
        'semana',
        'titulo',
        'fecha',
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }
}
