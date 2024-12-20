<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class TorneoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'ganador' => $this->nombreGanador,
            'tipo_torneo' => $this->tipoTorneo,
            'cantidad_jugadores' => $this->cantidadJugadores,
            'fecha_torneo'=> Carbon::parse($this->created_at)->format('d/m/Y')
        ];
    }
}
