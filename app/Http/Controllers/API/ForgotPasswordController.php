<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

/**
 * @OA\Info(
 *     title="API d'envoi de maail de  Réinitialisation de Mot de Passe",
 *     version="1.0.0",
 *     description="Documentation de l'API pour l'ennvoi de mail réinitialisation de mot de passe"
 * )
 */

/**
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Serveur local"
 * )
 */
class ForgotPasswordController extends Controller
{
    //

    /**
     * @OA\Post(
     *     path="/api/send-reset-link",
     * tags={"Users"},
     *     summary="Envoyer le lien de réinitialisation de mot de passe",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lien de réinitialisation envoyé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Password reset link sent to your email.")
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
     *         description="Utilisateur non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Aucun utilisateur trouvé.")
     *         )
     *     )
     * )
     */

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Get the user by email
        $user = User::where('email', $request->email)->first();

        // Check if the user exists
        if (!$user) {
            return response()->json(['error' => __('No such user found.')], 404);
        }

        // Generate a unique password reset token
        $token = Password::createToken($user);

        // Send the password reset link email with the token
        Password::sendResetLink([$user->email, $token]);

        // Return success response
        return response()->json(['message' => __('Password reset link sent to your email.')], 200);
    }
}
