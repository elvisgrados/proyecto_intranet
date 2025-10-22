<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tema extends Model
{
    use HasFactory;

    protected $table = 'temas';
    protected $primaryKey = 'id_tema';
    public $timestamps = false;

    protected $fillable = [
        'id_curso',
        'semana',
        'titulo_tema',
        'contenido',
        'recurso_pdf',
        'recurso_video'
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'id_curso', 'id_curso');
    }
}
