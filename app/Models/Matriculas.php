<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matriculas extends Model
{
    use HasFactory;

    protected $table = 'matriculas'; // si no lo infiere automáticamente
    protected $primaryKey = 'id_matricula'; // si tu PK no es id
    protected $fillable = [
        'id_alumno', 'colegio_procedencia', 'id_turno', 'id_carrera',
        'id_horario', 'fecha_matricula', 'estado'
    ];

    // RELACIÓN CON ALUMNO
    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'id_alumno', 'id_alumno');
    }

    // RELACIÓN CON TURNO
    public function turno()
    {
        return $this->belongsTo(Turno::class, 'id_turno', 'id_turno');
    }

    // RELACIÓN CON CARRERA
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'id_carrera', 'id_carrera');
    }

    // RELACIÓN CON HORARIO
    public function horario()
    {
        return $this->belongsTo(Horario::class, 'id_horario', 'id_horario');
    }

    // OPCIONAL: tipos de usuario si aplica
    public function tipos()
    {
        return $this->belongsTo(TipoUsuario::class, 'id_tipo', 'id_tipo');
    }
}
