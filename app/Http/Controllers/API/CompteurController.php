<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Compteur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Info(
 *     title="API de compteur",
 *     version="1.0.0",
 *     description="Documentation de l'API pour la gestion des compteurs"
 * )
 */

/**
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Serveur local"
 * )
 */
class CompteurController extends Controller
{

    /**
     * @OA\Schema(
     *     schema="Compteur",
     *     title="Compteur",
     *     description="Details du compteur",
     *     @OA\Property(property="id", type="integer"),
     *     @OA\Property(property="numero_compteur", type="string"),
     *     @OA\Property(property="localisation", type="string"),
     *     @OA\Property(property="type_local", type="string"),
     *     @OA\Property(property="frequence_moy_rechargement", type="integer"),
     *     @OA\Property(property="montant_moy_rechargement", type="integer"),
     *     @OA\Property(property="user_id", type="integer"),
     * )
     */

    /**
     * @OA\Get(
     *     path="/api/compteurs/user/{id}",
     * tags={"Compteurs"},
     *     summary="Obtenir les compteurs d'un utilisateur",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string"),
     *         description="ID de l'utilisateur"
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Liste des compteurs lié a un utilisateur",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="Compteur")
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
        // Récupérer l'utilisateur avec ses compteurs
        $user = User::findOrFail($id);
        $compteurs = $user->compteurs;

        // Retourner les compteurs en tant que collection JSON
        return response()->json($compteurs);
    }

    // public function show($id)
    // {
    //     $compteur = Compteur::findOrFail($id);

    //     if(!$compteur)
    //     {
    //         return response()->json(["error"=>"le compteur n'existe dans notre base de donné"]);
    //     }
    // }

    // fonction pour enregistrer un nouveau compteur

    /**
     * @OA\Post(
     *     path="/api/compteurs/new",
     * tags={"Compteurs"},
     *     summary="Ajoute un nouveau compteur",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nummero_compteur", "localisation", "type_local", "frequence_moy_rechargement", "user_id"},
     *             @OA\Property(property="nummero_compteur", type="string", example="123456"),
     *             @OA\Property(property="localisation", type="string", example="123 Rue Example"),
     *             @OA\Property(property="type_local", type="string", example="Appartement"),
     *             @OA\Property(property="frequence_moy_rechargement", type="integer", example=30),
     *             @OA\Property(property="montant_moy_rechargement", type="number", format="float", example=50.5),
     *             @OA\Property(property="user_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Compteur créé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="succès"),
     *             @OA\Property(property="data", ref="schemas/Compteur")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erreur de validation",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Erreur de validation")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'numero_compteur' => ['required', 'unique:compteurs'],
            'localisation' => 'required',
            'type_local' => 'required',
            'frequence_moy_rechargement' => 'required',
            'montant_moy_rechargement',
            'user_id' => 'required',

        ]);

        try {
            $compteurs = new Compteur(
                [
                    'numero_compteur' => Request('numero_compteur'),
                    'localisation' => Request('localisation'),
                    'type_local' => Request('type_local'),
                    'frequence_moy_rechargement' => Request('frequence_moy_rechargement'),
                    'montant_moy_rechargement' => Request('montant_moy_rechargement'),
                    'user_id' => Auth::id(),
                ]
            );

            $compteurs->save();

            return response()->json(["message" => "succès", 'data' => $compteurs], 201);
        } catch (\Exception $e) {
            return response()->json(["error" => "vous n'avez pas pu ajouter votre compteur." . $e->getMessage()]);
        }

    }

    //fonction pour modifier un compteur

    /**
     * @OA\Put(
     *     path="/api/compteurs/update/{id}",
     * tags={"Compteurs"},
     *     summary="Met à jour un compteur existant",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer"),
     *         description="ID du compteur à mettre à jour"
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="numero_compteur", type="string", example="123456"),
     *             @OA\Property(property="localisation", type="string", example="123 Rue Example"),
     *             @OA\Property(property="type_local", type="string", example="Appartement"),
     *             @OA\Property(property="frequence_moy_rechargement", type="integer", example=30),
     *             @OA\Property(property="montant_moy_rechargement", type="number", format="float", example=50.5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Compteur mis à jour avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Mise à jour réussie"),
     *             @OA\Property(property="data", ref="schemas/Compteur")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erreur de validation",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Erreur de validation")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Compteur non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Compteur non trouvé")
     *         )
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        $request->validate(
            [

            ]
        );

        $compteurs = Compteur::findOrFail($id);

        $compteurs->numero_compteur = $request->input('numero_compteur');

        $compteurs->localisation = $request->input('localisation');

        $compteurs->type_local = $request->input('type_local');

        $compteurs->frequence_moy_rechargement = $request->input('frequence_moy_rechargement');

        $compteurs->montant_moy_rechargement = $request->input('montant_moy_rechargement');

        $compteurs->save();
    }
    // fonction pour récupérer les compteurs lié à l'utilisateur

    // public function recupererCompteurUser($userId)
    // {
    //     $user_compteurs = User::findOrFail($userId);

    //     $compteurs = $user_compteurs->compteurs;

    //     return response()->json(['user' => $compteurs]);
    // }

}
