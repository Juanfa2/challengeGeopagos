<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Masculino extends Jugador
{


    public function __construct(public $nombre, public $habilidad, public $velocidad, public $fuerza)
    {
        parent::__construct($nombre, $habilidad);
    }

    public function puntaje(): int
    {
        return ($this->habilidad * 0.5) + ($this->fuerza * 0.3) + ($this->velocidad * 0.2);
    }

}
