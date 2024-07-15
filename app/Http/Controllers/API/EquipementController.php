<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Compteur;
use App\Models\Equipement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Info(
 *     title="API d'equipements",
 *     version="1.0.0",
 *     description="Documentation de l'API pour la gestion des equipements"
 * )
 */

/**
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Serveur local"
 * )
 */
class EquipementController extends Controller
{

    /**
     * @OA\Schema(
     *     schema="Equipement",
     *     title="Equipement",
     *     description="Details de l'équipement",
     *     @OA\Property(property="id", type="integer"),
     *     @OA\Property(property="nom_appareil", type="string"),
     *     @OA\Property(property="puissance", type="integer"),
     *     @OA\Property(property="user_id", type="integer"),
     *     @OA\Property(property="compteur_id", type="integer"),
     * )
     */

    /**
     * @OA\Get(
     *     path="/api/equipements/user/{id}/compteur/{id}",
     * tags={"Equipements"},
     *     summary="Obtenir les equipements d'un utilisateur",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID de l'utilisateur"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des equipements lié a un utilisateur",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="schemas/Equipement")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Utilisateur non trouvé"
     *     )
     * )
     */
    // fonction pour l'affichage  des equipement de l'user

    public function index($userId, $compteurId)
    {
        // Récupérer l'utilisateur et le compteur
        $user = User::findOrFail($userId);
        $compteur = Compteur::findOrFail($compteurId);

        // Récupérer les équipements associés à l'utilisateur et au compteur
        $equipements = $user->equipements()->where('compteur_id', $compteurId)->get();

        // Retourner les équipements en tant que JSON
        return response()->json($equipements);
    }

    // fonction pour voir les détails d'un équipement

    /**
     * @OA\Get(
     *     path="/api/equipements/show/{id}",
     * tags={"Equipements"},
     *     summary="Afficher les détails d'un équipement",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID de l'équipement à afficher"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de l'équipement",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="schemas/Equipement")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Équipement non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Cet équipement ne figure pas dans notre base de données")
     *         )
     *     )
     * )
     */
    public function show($id)
    {
        $equipement = Equipement::findOrFail($id);
        if (!$equipement) {
            return response()->json(["error" => "cet equipement ne figure pas dans notre base de donnée"]);
        }
    }

    // fonction pour l'enregistrement d'un équipement

    /**
     * @OA\Post(
     *     path="/api/equipements/new",
     * tags={"Equipements"},
     *     summary="Enregistrer un nouvel équipement",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nom_appareil", "puissance", "compteur_id"},
     *             @OA\Property(property="nom_appareil", type="string", example="Réfrigérateur"),
     *             @OA\Property(property="puissance", type="number", format="float", example=1200.5),
     *             @OA\Property(property="compteur_id", type="integer", example=1),
     *             @OA\Property(property="user_id", type="integer", example=1),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Équipement enregistré avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Succès"),
     *             @OA\Property(property="data", ref="schemas/Equipement")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erreur de validation",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Erreur lors de l'enregistrement de votre équipement")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate(
            [
                'nom_appareil' => 'required',
                'puissance' => 'required',
                'user_id' => 'required',
                'compteur_id' => 'required',
            ]
        );
        try {
            $equipement = new Equipement(
                [
                    'nom_appareil' => Request('nom_appareil'),
                    'puissance' => Request('puissance'),
                    'user_id' => Auth::id(),
                    'compteur_id' => Request('compteur_id'),
                ]
            );

            $equipement->save();

            return response()->json(["message" => "succès", 'data' => $equipement], 201);

        } catch (\Exception $e) {

            return response()->json(["error" => "erreur lors de l'enregistrement de votre équipement" . $e->getMessage()]);
        }

    }

    /**
     * @OA\Get(
     *     path="/api/equipements/{id}/edit",
     * tags={"Equipements"},
     *     summary="Afficher le formulaire d'édition d'un équipement",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID de l'équipement à éditer"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Formulaire d'édition de l'équipement",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", ref="schemas/Equipement")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Équipement non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Équipement non trouvé dans notre base de données")
     *         )
     *     )
     * )
     */
    public function edit($id)
    {
        $equipement = Equipement::findOrFail($id);

        if (!$equipement) {
            return response()->json(["error" => "Équipement non trouvé dans notre base de données"], 404);
        }

        return response()->json(['data' => $equipement], 200);
    }

    /**
     * @OA\Put(
     *     path="/api/equipements/update{id}",
     * tags={"Equipements"},
     *     summary="Modifier un équipement",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID de l'équipement à modifier"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="nom_appareil", type="string", example="Nouveau nom de l'appareil"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Équipement modifié avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string", example="Votre équipement a été modifié avec succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Équipement non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="L'équipement est introuvable")
     *         )
     *     )
     * )
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nom_appareil' => 'required|string',
        ]);

        $equipement = Equipement::findOrFail($id);

        if (!$equipement) {
            return response()->json(["error" => "L'équipement est introuvable"], 404);
        }

        $equipement->nom_appareil = $request->input('nom_appareil');
        $equipement->save();

        return response()->json(["success" => "Votre équipement a été modifié avec succès"], 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/equipements/delete/{id}",
     * tags={"Equipements"},
     *     summary="Supprimer un équipement",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID de l'équipement à supprimer"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Équipement supprimé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="string", example="Équipement supprimé avec succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Équipement non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Votre équipement est introuvable")
     *         )
     *     )
     * )
     */
    public function destroy($id)
    {
        $equipement = Equipement::findOrFail($id);

        if (!$equipement) {
            return response()->json(["error" => "votre equipement est inrtrouvable"]);
        }
        $equipement->delete();
    }
}
