<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Femenino;
use App\Models\Jugador;
use App\Models\Masculino;
use App\Services\TorneoService;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;

class TorneoController extends Controller
{


    public function __construct(protected TorneoService $torneoService)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        echo 'hola';
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function torneoFemenino(Request $request){
        /*VALIDAR LOS DATOS*/

        $jugadores = collect($request->request)->map(function ($data) {
            return new Femenino(
                $data['nombre'],
                $data['habilidad'],
                $data['reaccion']);
        });

        $ganadora = $this->torneoService->jugarTorneo($jugadores);

        return response()->json([
            'message' => 'Torneo finalizado',
            'ganadora' => $ganadora,
        ]);
    }


    public function torneoMasculino (Request $request){
        /*VALIDAR LOS DATOS*/
        $jugadores = collect($request->request)->map(function ($data) {
            return new Masculino(
                $data['nombre'],
                $data['habilidad'],
                $data['velocidad'],
                $data['fuerza']);
        });

        $ganador = $this->torneoService->jugarTorneo($jugadores);

        return response()->json([
            'message' => 'Torneo finalizado',
            'ganador' => $ganador,
        ]);
    }


}
