<?php

namespace App\Services;

use App\Models\Jugador;
use App\Models\Torneo;
use Exception;
use Illuminate\Support\Collection;

class TorneoService {

    public function jugarTorneo(Collection $personas){
        $jugadores = $personas->shuffle();
        while ($jugadores->count() > 1 ){
            $jugadores = $this->siguienteRonda($jugadores);
        }
        $ganador = $jugadores->first();
        return $ganador;
    }

    private function siguienteRonda(Collection $jugadores){
        return $jugadores->chunk(2)->map(function ($duelo) {
            return $duelo->sortByDesc(function ($competidor) {
                return $this->calcularPuntaje($competidor);
            })->first();
        });
    }


    private function calcularPuntaje(Jugador $jugador){
        $suerte = random_int(0, 10);
        $puntaje = $jugador->puntaje();
        return $puntaje + $suerte;
    }

    public function saveTorneo($nombreGanador, $cantidadJugadores, $tipoTorneo){
        try {

            $torneo = Torneo::create([
                "nombreGanador" => $nombreGanador,
                "cantidadJugadores"  => $cantidadJugadores,
                "tipoTorneo"  => $tipoTorneo
            ]);
            return $torneo;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 500);
        }
    }
}
