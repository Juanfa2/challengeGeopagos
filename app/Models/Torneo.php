<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Torneo extends Model
{
    use HasFactory;

    public $timestamps = true;
    public $table = 'torneo';
    protected $fillable = [
        'nombreGanador', 'tipoTorneo', 'cantidadJugadores'
    ];

    protected $hidden = [
        'updated_at'
    ];

}
