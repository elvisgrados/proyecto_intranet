<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoDocente extends Model
{
    use HasFactory;

    protected $table = 'pagos_docentes';
    protected $primaryKey = 'id_pago_docente'; // tu PK real
    public $timestamps = true;

    protected $fillable = [
        'id_docente',
        'id_curso',
        'monto',
        'fecha_pago',
        'estado'
    ];

    // Relación con Docente
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'id_docente');
    }

    // Relación con Curso
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso');
    }
}
