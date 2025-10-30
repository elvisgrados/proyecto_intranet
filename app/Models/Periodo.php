<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periodo extends Model
{
    protected $table = 'periodos';
    protected $primaryKey = 'id_periodo';
    public $timestamps = false; // Porque tu tabla no usa timestamps obligatorios

    protected $fillable = [
        'nombre',
        'estado'
    ];
}
