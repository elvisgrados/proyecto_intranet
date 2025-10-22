<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Simulacro extends Model
{
    protected $table = 'simulacros';
    protected $primaryKey = 'id_simulacro';
    public $timestamps = false;

    protected $fillable = ['titulo', 'fecha', 'descripcion'];
}
?>