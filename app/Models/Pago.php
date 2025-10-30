<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    protected $table = 'pagos'; 
    protected $primaryKey = 'id_pago'; 
    public $timestamps = false; 

    protected $fillable = [
        'id_alumno',
        'concepto',
        'monto',
        'fecha_vencimiento',
        'fecha_pago',
        'estado'
    ];

    // Relación con el alumno
    public function alumno()
    {
        return $this->belongsTo(\App\Models\Alumno::class, 'id_alumno');
    }

    // Propiedad auxiliar para mostrar si está pagado o pendiente
    public function getEstaPagadoAttribute()
    {
        return $this->estado === 'Cancelada';
    }

    // Propiedad auxiliar opcional para mostrar deuda (si lo necesitas)
    public function getDeudaAttribute()
    {
        return $this->estado === 'Cancelada' ? 0 : $this->monto;
    }
}