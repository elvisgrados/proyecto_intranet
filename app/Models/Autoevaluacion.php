<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autoevaluacion extends Model
{
    use HasFactory;

    protected $table = 'autoevaluaciones';
    protected $primaryKey = 'id_autoevaluacion';
    public $timestamps = false;

    protected $fillable = [
        'id_curso',
        'titulo',
        'descripcion',
        'semana',
        'fecha_publicacion'
    ];

    // 🔗 Relación: una autoevaluación pertenece a un curso
    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }

    // 🔗 Relación: una autoevaluación tiene muchos resultados
    public function resultados()
    {
        return $this->hasMany(ResultadoAutoevaluacion::class, 'id_autoevaluacion', 'id_autoevaluacion');
    }
}
