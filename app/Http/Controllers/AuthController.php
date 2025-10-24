<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use OpenApi\Annotations as OA;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Connexion d’un utilisateur",
     *     description="Permet à un utilisateur existant de se connecter et d’obtenir un token d’accès.",
     *     tags={"Authentification"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", example="yassine@gmail.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(response=200,
     *                  description="Connexion réussie.",
     *                  @OA\JsonContent()),
     *     @OA\Response(response=401, description="Identifiants invalides.")
     * )
     */
    public function login(Request $request){
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }
        $user = Auth::user();
        $token = $user->createToken('api-token')->plainTextToken;
        return response()->json([
            'user' => $user,
            'token' => $token
        ]);
    }

        /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Créer un nouveau compte utilisateur",
     *     description="Permet à un utilisateur de s'inscrire en fournissant son nom, email, mot de passe et rôle.",
     *     tags={"Authentification"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password","role_id"},
     *             @OA\Property(property="name", type="string", example="Yassine Elbahi"),
     *             @OA\Property(property="email", type="string", example="yassine@gmail.com"),
     *             @OA\Property(property="password", type="string", example="password123"),
     *             @OA\Property(property="role_id", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Utilisateur créé avec succès.",
     *         @OA\JsonContent()
     *     ),
     *     @OA\Response(response=422, description="Erreur de validation des données.")
     * )
     */

    public function register(Request $request){
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'required'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], Response::HTTP_CREATED);
    }

    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Déconnexion de l’utilisateur",
     *     description="Supprime le token d’accès courant de l’utilisateur connecté.",
     *     tags={"Authentification"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Déconnexion réussie.", @OA\JsonContent())
     * )
     */
    public function legout(Request $request){
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/users",
     * summary="Liste de tous les utilisateurs",
     * description="Récupère la liste complète des utilisateurs.",
     * tags={"Utilisateurs"},
     * security={{"bearerAuth":{}}},
     * @OA\Response(
     * response=200, 
     * description="Liste des utilisateurs récupérée avec succès.",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="name", type="string", example="Utilisateur A"),
     * @OA\Property(property="email", type="string", format="email", example="user.a@test.com"),
     * @OA\Property(property="role_id", type="integer", example=2)
     * )
     * )
     * )
     * )
     */
    public function index(){
        return response()->json(User::all());
    }

    /**
     * @OA\Get(
     * path="/api/users/{id}",
     * summary="Afficher un utilisateur spécifique",
     * description="Récupère les détails d'un utilisateur par son ID.",
     * tags={"Utilisateurs"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID de l'utilisateur à afficher.",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200, 
     * description="Détails de l'utilisateur récupérés avec succès.",
     * @OA\JsonContent(
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="name", type="string", example="Yassine Elbahi"),
     * @OA\Property(property="email", type="string", format="email", example="yassine@gmail.com"),
     * @OA\Property(property="role_id", type="integer", example=2),
     * @OA\Property(property="created_at", type="string", format="date-time"),
     * @OA\Property(property="updated_at", type="string", format="date-time")
     * )
     * ),
     * @OA\Response(response=404, description="Utilisateur non trouvé.")
     * )
     */
    public function show($id){
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }
        return response()->json($user);
    }

    /**
     * @OA\Put(
     * path="/api/users/{id}",
     * summary="Mettre à jour un utilisateur",
     * description="Met à jour les informations d'un utilisateur par son ID. Seuls les champs fournis sont mis à jour.",
     * tags={"Utilisateurs"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID de l'utilisateur à mettre à jour.",
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * @OA\Property(property="name", type="string", example="Nouveau Nom"),
     * @OA\Property(property="email", type="string", format="email", example="nouveau.email@test.com"),
     * @OA\Property(property="password", type="string", format="password", example="nouveau_motdepasse")
     * )
     * ),
     * @OA\Response(
     * response=200, 
     * description="Utilisateur mis à jour avec succès.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Utilisateur mis à jour"),
     * @OA\Property(property="user", type="object",
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="name", type="string", example="Yassine Elbahi"),
     * @OA\Property(property="email", type="string", format="email", example="yassine@gmail.com")
     * )
     * )
     * ),
     * @OA\Response(response=404, description="Utilisateur non trouvé."),
     * @OA\Response(response=422, description="Erreur de validation des données.")
     * )
     */
    public function update(Request $request, $id){
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }
        $request->validate([
            'name' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'password' => 'sometimes|min:6',
        ]);
        $data = $request->only(['name', 'email']);
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);
        return response()->json(['message' => 'Utilisateur mis à jour', 'user' => $user]);
    }

    /**
     * @OA\Delete(
     * path="/api/users/{id}",
     * summary="Supprimer un utilisateur",
     * description="Supprime un utilisateur par son ID.",
     * tags={"Utilisateurs"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID de l'utilisateur à supprimer.",
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200, 
     * description="Utilisateur supprimé avec succès.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Utilisateur supprimé")
     * )
     * ),
     * @OA\Response(response=404, description="Utilisateur non trouvé.")
     * )
     */
    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }
        $user->delete();
        return response()->json(['message' => 'Utilisateur supprimé']);
    }

}
