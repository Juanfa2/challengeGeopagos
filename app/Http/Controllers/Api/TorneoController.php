<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetTorneoRequest;
use App\Http\Requests\TorneoFemeninoRequest;
use App\Http\Requests\TorneoMasculinoRequest;
use App\Http\Resources\TorneoResource;
use App\Models\Femenino;
use App\Models\Masculino;
use App\Models\Torneo;
use App\Services\TorneoService;
use Exception;
use Illuminate\Http\Request;

class TorneoController extends Controller
{


    public function __construct(protected TorneoService $torneoService)
    {
    }
    /**
     * Display a listing of the resource.
     */
    public function index(GetTorneoRequest $request)
    {
        $fecha = $request->input('fecha') != null ?  \Carbon\Carbon::createFromFormat('d/m/Y', $request->input('fecha'))->format('Y-m-d') : null;
        $tipoTorneo = $request->input('tipo') != null ? $request->input('tipo') : '';
        $nombreGanador = $request->input('nombreGanador') != null ? $request->input('nombreGanador') : '';

        $torneos = Torneo::select('id','nombreGanador', 'tipoTorneo', 'cantidadJugadores', 'created_at')
            ->when($fecha, function ($query) use ($fecha){
                return $query->whereDate('created_at',$fecha);
            })->when($tipoTorneo, function($query) use ($tipoTorneo){
                return $query->where('tipoTorneo', $tipoTorneo);
            })->when($nombreGanador, function($query) use ($nombreGanador){
                return $query->where('nombreGanador', 'like', '%' . $nombreGanador . '%');
            })->get();
        return response()->json([
            'data' => TorneoResource::collection($torneos),
        ]);

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
    public function show(Torneo $torneo)
    {
        return response()->json([
            'data' => new TorneoResource($torneo),
        ]);
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

    public function torneoFemenino(TorneoFemeninoRequest $request){
        try {
            $jugadores = collect($request->validated())->map(function ($data) {
                return new Femenino(
                    $data['nombre'],
                    $data['habilidad'],
                    $data['reaccion']);
            });

            $ganadora = $this->torneoService->jugarTorneo($jugadores);

            $this->torneoService->saveTorneo($ganadora->nombre,$jugadores->count(),'Femenino');

            return response()->json([
                'mensaje' => 'Torneo finalizado',
                'ganadora' => $ganadora,
            ]);
        }catch (Exception $e){
            return response()->json([
                'error' => $e->getMessage()
            ],$e->getCode());
        }
    }


    public function torneoMasculino (TorneoMasculinoRequest $request){
        try {
            $jugadores = collect($request->validated())->map(function ($data) {
                return new Masculino(
                    $data['nombre'],
                    $data['habilidad'],
                    $data['velocidad'],
                    $data['fuerza']);
            });

            $ganador = $this->torneoService->jugarTorneo($jugadores);

            $this->torneoService->saveTorneo($ganador->nombre,$jugadores->count(),'Masculino');
            return response()->json([
                'mensaje' => 'Torneo finalizado',
                'ganador' => $ganador,
            ]);
        }catch (Exception $e){
            return response()->json([
                'error' => $e->getMessage()
            ],$e->getCode());
        }
    }


}
