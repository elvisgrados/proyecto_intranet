<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoUsuario extends Model
{
    use HasFactory;

    // Si tu tabla se llama diferente a 'tipo_usuarios', descomenta y cambia:
    protected $table = 'tipo_usuario';

    protected $primaryKey = 'id_tipo'; // tu clave primaria

    public $timestamps = false; // si tu tabla no tiene created_at / updated_at

    protected $fillable = [
        'nombre_tipo',
        'descripcion',
    ];
}
