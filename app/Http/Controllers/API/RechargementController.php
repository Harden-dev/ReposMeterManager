<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Rechargement;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="API de Rechargement",
 *     version="1.0.0",
 *     description="Documentation de l'API pour la gestion des rechargements"
 * )
 */

/**
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Serveur local"
 * )
 */
class RechargementController extends Controller
{/**
 * @OA\Schema(
 *     schema="Rechargement",
 *     title="Rechargement",
 *     description="Details du rechargement",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="valeur_energ_dispo", type="string"),
 *     @OA\Property(property="valeur_energ_acheter", type="string"),
 *     @OA\Property(property="montant_recharge", type="integer"),
 *     @OA\Property(property="date_rechargement", type="string", format="date"),
 *     @OA\Property(property="heure_rechargement", type="string", format="time"),
 *     @OA\Property(property="compteur_id", type="integer"),
 *     @OA\Property(property="user_id", type="integer"),
 * )
 */

    /**
     * @OA\Get(
     *     path="/api/rechargements/user/{id}",
     * tags={"Rechargements"},
     *     summary="Obtenir les rechargements d'un utilisateur",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID de l'utilisateur"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des rechargements lié a un utilisateur",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="schemas/Rechargement")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Utilisateur non trouvé"
     *     )
     * )
     */
    public function index($id)
    {
        // Récupérer l'utilisateur avec ses rechargements
        $user = User::findOrFail($id);
        $rechargements = $user->rechargements;

        // Retourner les rechargements en tant que collection JSON
        return response()->json($rechargements);
    }

    /**
     * @OA\Post(
     *     path="/api/rechargements/new",
     * tags={"Rechargements"},
     *     summary="Créer un nouveau rechargement",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"valeur_energ_dispo", "valeur_energ_acheter", "montant_recharge", "date_rechargement", "heure_rechargement", "compteur_id", "user_id"},
     *             @OA\Property(property="valeur_energ_dispo", type="number", example=100.0),
     *             @OA\Property(property="valeur_energ_acheter", type="number", example=50.0),
     *             @OA\Property(property="montant_recharge", type="number", example=20.0),
     *             @OA\Property(property="date_rechargement", type="string", format="date", example="2023-01-01"),
     *             @OA\Property(property="heure_rechargement", type="string", format="time", example="12:00:00"),
     *             @OA\Property(property="compteur_id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Rechargement créé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="succès"),
     *             @OA\Property(property="data", ref="schemas/Rechargement")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation échouée",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Erreur de validation")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'valeur_energ_dispo' => 'required|numeric',
            'valeur_energ_acheter' => 'required|numeric',
            'montant_recharge' => 'required|numeric',
            'date_rechargement' => 'required|date',
            'heure_rechargement' => 'required|date_format:H:i:s',
            'compteur_id' => 'required|exists:compteurs,id',
            'user_id' => 'required|exists:users,id',
        ]);

        try {
            $rechargement = new Rechargement([
                'valeur_energ_dispo' => $request->input('valeur_energ_dispo'),
                'valeur_energ_acheter' => $request->input('valeur_energ_acheter'),
                'montant_recharge' => $request->input('montant_recharge'),
                'date_rechargement' => $request->input('date_rechargement'),
                'heure_rechargement' => $request->input('heure_rechargement'),
                'compteur_id' => $request->input('compteur_id'),
                'user_id' => $request->input('user_id'),
            ]);

            $rechargement->save();

            return response()->json(["message" => "succès", 'data' => $rechargement], 201);
        } catch (\Exception $e) {
            return response()->json(["error" => "Vous n'avez pas pu ajouter votre rechargement. " . $e->getMessage()], 400);
        }
    }
}
