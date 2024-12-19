<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

abstract class Jugador
{
    public function __construct(public $nombre, public $habilidad)
    {

    }

    abstract public function puntaje() : int;
}
