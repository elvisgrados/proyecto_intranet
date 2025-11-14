<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoMatricula extends Model
{
    use HasFactory;

    protected $table = 'pagos_matriculas';
    protected $primaryKey = 'id_pago'; // tu PK real
    public $timestamps = true; // usa timestamps porque sí tienes created_at/updated_at

    protected $fillable = [
        'id_alumno',
        'concepto',
        'monto',
        'tipo',          // ejemplo: 'matricula', 'mensualidad'
        'fecha_vencimiento',
        'fecha_pago',
        'estado'         // 'pagado', 'pendiente', etc.
    ];

    // Relación con Alumno
    public function alumno()
    {
        return $this->belongsTo(Alumno::class, 'id_alumno');
    }
}
