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
use OpenApi\Annotations as OA;


/**
 * @OA\Info(
 *     title="Challenge Geopagos Documentacion",
 *     version="1.0.0",
 *     description="Challenge para Geopagos. Torneo de tenis",
 *     @OA\Contact(
 *          email="juanfrancisco.m.sanchez@gmail.com"
 *      )
 * )
 * @OA\Server(url="http://localhost:8000")
 */
class TorneoController extends Controller
{


    public function __construct(protected TorneoService $torneoService)
    {
    }
    /**
     * @OA\Get(
     *     path="/api/torneos",
     *     summary="Get torneos",
     *     tags={"Torneos"},
     *     description="Obtiene los torneos segun los filtros (fecha,tipo,nombreGanador)",
     *     @OA\Parameter(
     *         name="fecha",
     *         in="query",
     *         description="Fecha del torneo en formato d/m/Y",
     *         required=false,
     *         @OA\Schema(type="string", example="20/12/2024")
     *     ),
     *     @OA\Parameter(
     *         name="tipo",
     *         in="query",
     *         description="Tipo de torneo (Masculino, Femenino)",
     *         required=false,
     *         @OA\Schema(type="string", example="Masculino")
     *     ),
     *     @OA\Parameter(
     *         name="nombreGanador",
     *         in="query",
     *         description="Nombre del ganador del torneo",
     *         required=false,
     *         @OA\Schema(type="string", example="Jose")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Lista de torneos",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  type="array",
     *                  property="data",
     *                  @OA\Items(
     *                      @OA\Property(property="id", type="number", example="1"),
     *                      @OA\Property(property="ganador", type="string", example="Jose"),
     *                      @OA\Property(property="tipo_torneo", type="string", example="Masculino"),
     *                      @OA\Property(property="cantidad_jugadores", type="string", example="8"),
     *                      @OA\Property(property="fecha_torneo", type="string", example="20/12/2024")
     *                  )
     *              )
     *          )
     *      )
     * )
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
     * @OA\Get(
     *     path="/api/torneos/{id}",
     *     summary="Detalles de un torneo",
     *     description="Devuelve los detalles de un torneo pasado como parametro.",
     *     tags={"Torneos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id del torneo",
     *         required=true,
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del torneo",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=5),
     *                 @OA\Property(property="ganador", type="string", example="Jose"),
     *                 @OA\Property(property="tipo_torneo", type="string", example="Masculino"),
     *                 @OA\Property(property="cantidad_jugadores", type="string", example="8"),
     *                 @OA\Property(property="fecha_torneo", type="string", example="20/12/2024", description="Fecha del torneo en formato d/m/Y")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Error: Not Found",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="El recurso solicitado no existe")
     *         )
     *     )
     * )
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


    /**
     * @OA\Get(
     *     path="/api/torneoFemenino",
     *     summary="Inicia Torneo Femenino",
     *     tags={"Torneo"},
     *     description="Recibe una lista de jugadoras con sus atributos(nombre,habilidad,reaccion) y devuelve la ganadora del torneo.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Lista de jugadoras con sus atributos, potencias de dos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 required={"nombre", "habilidad", "fuerza", "velocidad"},
     *                 @OA\Property(property="nombre", type="string", example="Lucia"),
     *                 @OA\Property(property="habilidad", type="integer", example=90),
     *                 @OA\Property(property="reaccion", type="integer", example=100),
     *             ),
     *             example={
     *                 {
     *                     "nombre": "Lucia",
     *                     "habilidad": 90,
     *                     "reaccion": 100
     *                 },
     *                 {
     *                     "nombre": "Maria",
     *                     "habilidad": 80,
     *                     "reaccion": 85
     *                 }
     *              }
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="Torneo finalizado con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="mensaje", type="string", example="Torneo finalizado"),
     *             @OA\Property(
     *                 property="ganadora",
     *                 type="object",
     *                 @OA\Property(property="nombre", type="string", example="Lucia"),
     *                 @OA\Property(property="habilidad", type="integer", example=90),
     *                 @OA\Property(property="reaccion", type="integer", example=100),
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *         response=400,
     *         description="Error en la solicitud",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Datos inválidos")
     *         )
     *     )
     * )
     *
     */
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


    /**
     * @OA\Get(
     *     path="/api/torneoMasculino",
     *     summary="Inicia Torneo Masculino",
     *     tags={"Torneo"},
     *     description="Recibe una lista de jugadores con sus atributos(nombre,habilidad,fuerza,velocidad) y devuelve el ganador del torneo.",
     *     @OA\RequestBody(
     *         required=true,
     *         description="Lista de jugadores con sus atributos, potencias de dos",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 required={"nombre", "habilidad", "fuerza", "velocidad"},
     *                 @OA\Property(property="nombre", type="string", example="Pedro"),
     *                 @OA\Property(property="habilidad", type="integer", example=90),
     *                 @OA\Property(property="fuerza", type="integer", example=100),
     *                 @OA\Property(property="velocidad", type="integer", example=30)
     *             ),
     *               example={
     *                 {
     *                     "nombre": "Pedro",
     *                     "habilidad": 90,
     *                     "fuerza": 100,
     *                     "velocidad": 30
     *                 },
     *                 {
     *                     "nombre": "Jose",
     *                     "habilidad": 90,
     *                     "fuerza": 60,
     *                     "velocidad": 85
     *                 }
     *             }
     *         )
     *     ),
     *      @OA\Response(
     *         response=200,
     *         description="Torneo finalizado con éxito",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="mensaje", type="string", example="Torneo finalizado"),
     *             @OA\Property(
     *                 property="ganador",
     *                 type="object",
     *                 @OA\Property(property="nombre", type="string", example="Jose"),
     *                 @OA\Property(property="habilidad", type="integer", example=90),
     *                 @OA\Property(property="velocidad", type="integer", example=60),
     *                 @OA\Property(property="fuerza", type="integer", example=85),
     *             )
     *         )
     *     ),
     *      @OA\Response(
     *         response=500,
     *         description="Error en la solicitud",
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="error", type="string", example="Server error")
     *         )
     *     )
     * )
     *
     */
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
