<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alerta extends Model
{
    use HasFactory;

    protected $table = 'alertas';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_usuario',
        'titulo',
        'descripcion',
        'tipo',
        'fecha',
        'estado',
    ];

    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'id_usuario', 'id_usuario');
    }
}
