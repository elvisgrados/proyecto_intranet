<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultadoSimulacro extends Model
{
    use HasFactory;

    protected $table = 'resultados_simulacro';
    protected $primaryKey = 'id_resultado';
    public $timestamps = false;

    protected $fillable = [
        'id_simulacro',
        'id_alumno',
        'puntaje',
        'fecha',
    ];

    // 🔗 Relación con alumno
    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'id_alumno');
    }

    // 🔗 Relación con simulacro
    public function simulacro()
    {
        return $this->belongsTo(Simulacro::class, 'id_simulacro');
    }
}