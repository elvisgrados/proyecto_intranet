<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modalidad extends Model
{
    protected $table = 'modalidades_ingreso'; // 👈 tu tabla real
    protected $primaryKey = 'id_modalidad';
    public $timestamps = false;

    protected $fillable = ['nombre_modalidad'];
}