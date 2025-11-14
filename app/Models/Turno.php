<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    use HasFactory;

    protected $table = 'turnos';
    protected $primaryKey = 'id_turno';
    protected $fillable = ['nombre_turno'];

    // Relación con matriculas
    public function matriculas()
    {
        return $this->hasMany(Matriculas::class, 'id_turno', 'id_turno');
    }
}
