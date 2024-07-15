<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

/**
 * @OA\Info(
 *     title="API de Réinitialisation de Mot de Passe",
 *     version="1.0.0",
 *     description="Documentation de l'API pour la réinitialisation de mot de passe"
 * )
 */

/**
 * @OA\Server(
 *     url="http://localhost:8000",
 *     description="Serveur local"
 * )
 */
class ResetPasswordController extends Controller
{
    //

    /**
     * @OA\Post(
     *     path="/api/reset",
     * tags={"Users"},
     *     summary="Réinitialisation du mot de passe",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password", "password_confirmation", "token"},
     *             @OA\Property(property="email", type="string", format="email", example="user@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="newpassword"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="newpassword"),
     *             @OA\Property(property="token", type="string", example="token_from_email"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mot de passe réinitialisé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Le mot de passe a été réinitialisé avec succès !")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Erreur de réinitialisation de mot de passe",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Message d'erreur détaillé")
     *         )
     *     )
     * )
     */

    public function reset(Request $request)
    {

        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);
// Check if the token is valid
        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
            $user->password = Hash::make($password);
            $user->save();

            Auth::login($user);

            event(new PasswordReset($user));
        });

// Return appropriate response based on the status
        return $status === Password::PASSWORD_RESET ? response()->json(['message' => __('Password has been reset!')], 200) : response()->json(['error' => $status], 400);
    }
}
