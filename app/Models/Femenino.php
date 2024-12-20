<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Femenino extends Jugador
{
    public function __construct(public $nombre, public $habilidad, public $reaccion)
    {
        parent::__construct($nombre, $habilidad);
    }

    public function puntaje(): int
    {
        $suerte = random_int(0, 10);
        return ($this->habilidad * 0.5) + ($this->reaccion * 0.4) + $suerte;
    }


}
