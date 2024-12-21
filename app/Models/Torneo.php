<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="torneo",
 *     type="object",
 *     title="Torneo",
 *     @OA\Property(property="id",type="integer",example=1),
 *     @OA\Property(property="nombreGanador",type="string",example="Jose"),
 *     @OA\Property(property="tipoTorneo",type="string",example="Masculino"),
 *     @OA\Property(property="cantidadJugadores",type="string",example="8"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2024-12-19 23:29:07"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-12-19 23:29:07")
 * )
 */
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
