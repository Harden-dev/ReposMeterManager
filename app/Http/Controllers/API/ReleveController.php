<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Compteur;
use App\Models\Releve;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="API de rélévé",
 *     version="1.0.0",
 *     description="Documentation de l'API pour la gestion des releves"
 * )
 */

/**
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Serveur local"
 * )
 */
class ReleveController extends Controller
{

    /**
     * @OA\Schema(
     *     schema="Releve",
     *     title="Releve",
     *     description="Details du Releve",
     *     @OA\Property(property="id", type="string"),
     *     @OA\Property(property="valeur_energ_dispo", type="string"),
     *     @OA\Property(property="date_releve", type="date"),
     *     @OA\Property(property="heure_releve", type="time"),
     *     @OA\Property(property="compteur_id", type="string"),
     * )
     */

    //

    /**
     * @OA\Get(
     *     path="/api/releves/compteurs/{id}",
     * tags={"Relevés"},
     *     summary="Obtenir les releves d'un compteurs",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="ID du compteur"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des releves lié a un compteur",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="schemas/Releve")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="compteur non trouvé"
     *     )
     * )
     */

    public function index($userId, $compteurId)
    {
        // Récupérer l'utilisateur et le compteur
        $user = User::findOrFail($userId);
        $compteur = Compteur::findOrFail($compteurId);

        // Récupérer les équipements associés à l'utilisateur et au compteur
        $releves = $user->releves()->where('compteur_id', $compteurId)->get();

        // Retourner les équipements en tant que JSON
        return response()->json($releves);
    }

    /**
     * @OA\Post(
     *     path="/api/releves",
     * tags={"Releves"},
     *     summary="Créer un nouveau relevé",
     *     tags={"Relevés"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données requises pour créer un nouveau relevé",
     *         @OA\JsonContent(
     *             required={"valeur_energ_dispo", "date_releve", "heure_releve", "compteur_id"},
     *             @OA\Property(property="valeur_energ_dispo", type="string", example="100 kWh"),
     *             @OA\Property(property="date_releve", type="string", format="date", example="2024-06-18"),
     *             @OA\Property(property="heure_releve", type="string", format="time", example="13:30:00"),
     *             @OA\Property(property="compteur_id", type="integer", example="1"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Relevé créé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="succès"),
     *             @OA\Property(property="data", ref="schemas/Releve"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Erreur lors de la création du relevé",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="error"),
     *         ),
     *     ),
     * )
     */

    public function store(Request $request)
    {
        $request->validate([
            "valeur_energ_dispo" => "required",
            "date_releve" => "required",
            "heure_releve" => "required",
            "compteur_id" => "required",
            "user_id" => "required",
        ]);
        try {
            $releves = new Releve([
                "valeur_energ_dispo" => Request('valeur_energ_dispo'),
                "date_releve" => Request('date_releve'),
                "heure_releve" => Request('heure_releve'),
                "compteur_id" => Request('compteur_id'),
                "user_id" => Request('user_id'),

            ]);
            $releves->save();

            return response()->json(["message" => "succès", 'data' => $releves], 201);
        } catch (\Exception $e) {
            return response()->json(["message" => "error", $e->getMessage()], 401);
        }

    }
}
