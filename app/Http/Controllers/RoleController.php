<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/roles",
     * summary="Liste de tous les rôles utilisateurs",
     * description="Récupère la liste complète des rôles disponibles dans l'application.",
     * tags={"Rôles"},
     * @OA\Response(
     * response=200, 
     * description="Liste des rôles récupérée avec succès.",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="role", type="string", example="admin", description="Nom du rôle (ex: admin, emp, user)."),
     * @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
     * @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
     * )
     * )
     * )
     * )
     */
    public function index(){
        return response()->json(Role::all());
    }

    /**
     * @OA\Get(
     * path="/api/roles/users",
     * summary="Liste des utilisateurs simples",
     * description="Récupère la liste de tous les utilisateurs ayant spécifiquement le rôle 'user'.",
     * tags={"Rôles"},
     * @OA\Response(
     * response=200, 
     * description="Liste des utilisateurs de rôle 'user' récupérée avec succès.",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(
     * @OA\Property(property="id", type="integer", example=3),
     * @OA\Property(property="role", type="string", example="user", description="Nom du rôle."),
     * @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
     * @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
     * )
     * )
     * )
     * )
     */
    public function getUsers(){
        $users = Role::where('role', 'user')->get();
        return response()->json($users);
    }

    /**
     * @OA\Get(
     * path="/api/roles/employers",
     * summary="Liste des utilisateurs employeurs/entreprises",
     * description="Récupère la liste de tous les utilisateurs ayant spécifiquement le rôle 'emp'.",
     * tags={"Rôles"},
     * @OA\Response(
     * response=200, 
     * description="Liste des utilisateurs de rôle 'emp' récupérée avec succès.",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(
     * @OA\Property(property="id", type="integer", example=2),
     * @OA\Property(property="role", type="string", example="emp", description="Nom du rôle."),
     * @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
     * @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
     * )
     * )
     * )
     * )
     */
    public function getEmps(){
        $emps = Role::where('role', 'emp')->get();
        return response()->json($emps);
    }

    /**
     * @OA\Get(
     * path="/api/roles/admins",
     * summary="Liste des administrateurs",
     * description="Récupère la liste de tous les utilisateurs ayant spécifiquement le rôle 'admin'.",
     * tags={"Rôles"},
     * @OA\Response(
     * response=200, 
     * description="Liste des utilisateurs de rôle 'admin' récupérée avec succès.",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="role", type="string", example="admin", description="Nom du rôle."),
     * @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
     * @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
     * )
     * )
     * )
     * )
     */
    public function getAdmins(){
        $admin = Role::where('role', 'admin')->get();
        return response()->json($admin);
    }

    /**
     * @OA\Get(
     * path="/api/roles/{id}",
     * summary="Afficher un rôle spécifique",
     * description="Récupère les détails d'un rôle utilisateur par son ID.",
     * tags={"Rôles"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID du rôle à afficher.",
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\Response(
     * response=200, 
     * description="Détails du rôle récupérés avec succès.",
     * @OA\JsonContent(
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="role", type="string", example="admin", description="Nom du rôle (ex: admin, emp, user)."),
     * @OA\Property(property="created_at", type="string", format="date-time", nullable=true),
     * @OA\Property(property="updated_at", type="string", format="date-time", nullable=true)
     * )
     * ),
     * @OA\Response(
     * response=404, 
     * description="Rôle non trouvé.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Role not found")
     * )
     * )
     * )
     */
    public function show($id){
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['message' => 'Role not found'], 404);
        }
        return response()->json($role);
    }

    /**
     * @OA\Post(
     * path="/api/roles",
     * summary="Créer un nouveau rôle",
     * description="Crée un nouveau rôle utilisateur, en s'assurant que le nom du rôle est valide.",
     * tags={"Rôles"},
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"role"},
     * @OA\Property(property="role", type="string", example="user", enum={"user", "emp", "admin"}, description="Le nom du rôle à assigner."),
     * @OA\Property(property="user_id", type="integer", example=5, description="ID de l'utilisateur concerné (si non géré par un autre endpoint).", nullable=true)
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Rôle créé avec succès.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Role created"),
     * @OA\Property(property="data", type="object",
     * @OA\Property(property="role", type="string", example="user"),
     * @OA\Property(property="user_id", type="integer", example=5),
     * @OA\Property(property="id", type="integer", example=4),
     * @OA\Property(property="created_at", type="string", format="date-time"),
     * @OA\Property(property="updated_at", type="string", format="date-time")
     * )
     * )
     * ),
     * @OA\Response(response=422, description="Erreur de validation des données (rôle invalide).")
     * )
     */
    public function store(Request $request){
        $request->validate([
            'role' => 'required|in:user,emp,admin',
        ]);
        $role = Role::create($request->only(['role', 'user_id']));
        return response()->json(['message' => 'Role created', 'data' => $role], 201);
    }

    /**
     * @OA\Put(
     * path="/api/roles/{id}",
     * summary="Mettre à jour un rôle",
     * description="Met à jour le nom du rôle ou l'utilisateur associé par l'ID du rôle. Seuls les champs fournis sont mis à jour.",
     * tags={"Rôles"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID du rôle à mettre à jour.",
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * description="Champs à mettre à jour. Au moins un champ est requis.",
     * @OA\Property(property="role", type="string", example="emp", enum={"user", "emp", "admin"}, nullable=true, description="Le nouveau nom du rôle."),
     * @OA\Property(property="user_id", type="integer", example=5, nullable=true, description="Le nouvel ID de l'utilisateur concerné.")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Rôle mis à jour avec succès.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Role updated"),
     * @OA\Property(property="data", type="object",
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="role", type="string", example="emp"),
     * @OA\Property(property="user_id", type="integer", example=5),
     * @OA\Property(property="created_at", type="string", format="date-time"),
     * @OA\Property(property="updated_at", type="string", format="date-time")
     * )
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Rôle non trouvé.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="not found 404")
     * )
     * ),
     * @OA\Response(response=422, description="Erreur de validation des données (rôle ou user_id invalide).")
     * )
     */
    public function update(Request $request, $id){
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['message' => 'not found 404'], 404);
        }
        $request->validate([
            'role' => 'in:user,emp,admin',
            'user_id' => 'exists:users,id'
        ]);
        $role->update($request->only(['role', 'user_id']));
        return response()->json(['message' => 'Role updated', 'data' => $role]);
    }

    /**
     * @OA\Delete(
     * path="/api/roles/{id}",
     * summary="Supprimer un rôle",
     * description="Supprime définitivement un rôle utilisateur par son ID.",
     * tags={"Rôles"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID du rôle à supprimer.",
     * @OA\Schema(type="integer", example=4)
     * ),
     * @OA\Response(
     * response=200, 
     * description="Rôle supprimé avec succès.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Role deleted")
     * )
     * ),
     * @OA\Response(
     * response=404, 
     * description="Rôle non trouvé.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="not found 404")
     * )
     * )
     * )
     */
    public function destroy($id){
        $role = Role::find($id);
        if (!$role) {
            return response()->json(['message' => 'not found 404'], 404);
        }
        $role->delete();
        return response()->json(['message' => 'Role deleted']);
    }
}
