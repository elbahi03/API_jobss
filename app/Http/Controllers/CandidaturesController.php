<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\candidatures;

class CandidaturesController extends Controller{

    /**
     * @OA\Post(
     * path="/api/candidatures",
     * summary="Soumettre une nouvelle candidature",
     * description="Enregistre une nouvelle candidature pour une offre d'emploi spécifique.",
     * tags={"Candidatures"},
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"user_id","job_id","full_name","can_email","phone_number"},
     * @OA\Property(property="user_id", type="integer", example=1, description="ID de l'utilisateur qui postule."),
     * @OA\Property(property="job_id", type="integer", example=5, description="ID de l'offre d'emploi concernée."),
     * @OA\Property(property="full_name", type="string", example="Jean Dupont"),
     * @OA\Property(property="can_email", type="string", format="email", example="jean.dupont@candidat.com", description="Email du candidat (champ `can_email` dans la requête)."),
     * @OA\Property(property="phone_number", type="string", example="0612345678"),
     * @OA\Property(property="motivation", type="string", nullable=true, example="Je suis très intéressé par ce poste.")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Candidature créée avec succès.",
     * @OA\JsonContent(
     * @OA\Property(property="user_id", type="integer", example=1),
     * @OA\Property(property="job_id", type="integer", example=1),
     * @OA\Property(property="full_name", type="string", example="Jean Dupont"),
     * @OA\Property(property="can_email", type="string", format="email", example="jean.dupont@candidat.com"),
     * @OA\Property(property="phone_number", type="string", example="0612345678"),
     * @OA\Property(property="motivation", type="string", nullable=true, example="Je suis très intéressé par ce poste."),
     * @OA\Property(property="updated_at", type="string", format="date-time"),
     * @OA\Property(property="created_at", type="string", format="date-time"),
     * @OA\Property(property="id", type="integer", example=10)
     * )
     * ),
     * @OA\Response(response=422, description="Erreur de validation des données.")
     * )
     */
    public function store(Request $request){
        $validate = $request->validate([
            'user_id' => 'required|exists:users,id',
            'job_id' => 'required|exists:jobs,id',
            'full_name' => 'required|string',
            'can_email' => 'required|email',
            'phone_number' => 'required|string',
            'motivation' => 'nullable|string',
        ]);
        $candidature = Candidatures::create($validate);
        return response()->json($candidature, 201);
    }

    /**
     * @OA\Get(
     * path="/api/candidatures/{id}",
     * summary="Afficher les détails d'une candidature",
     * description="Récupère les détails d'une candidature spécifique par son ID. L'accès est limité aux rôles 'admin' et 'emp'.",
     * tags={"Candidatures"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID de la candidature à afficher.",
     * @OA\Schema(type="integer", example=10)
     * ),
     * @OA\Response(
     * response=200, 
     * description="Détails de la candidature récupérés avec succès.",
     * @OA\JsonContent(
     * @OA\Property(property="id", type="integer", example=10),
     * @OA\Property(property="user_id", type="integer", example=1),
     * @OA\Property(property="job_id", type="integer", example=5),
     * @OA\Property(property="full_name", type="string", example="Jean Dupont"),
     * @OA\Property(property="can_email", type="string", format="email", example="jean.dupont@candidat.com"),
     * @OA\Property(property="phone_number", type="string", example="0612345678"),
     * @OA\Property(property="motivation", type="string", nullable=true, example="Je suis très intéressé par ce poste."),
     * @OA\Property(property="created_at", type="string", format="date-time"),
     * @OA\Property(property="updated_at", type="string", format="date-time")
     * )
     * ),
     * @OA\Response(
     * response=403, 
     * description="Interdit (Non autorisé).",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="not exiscte")
     * )
     * ),
     * @OA\Response(
     * response=404, 
     * description="Candidature non trouvée.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Candidature non trouvée")
     * )
     * )
     * )
     */
    public function show($id){
        $user = $request->user();
        $role = $user->role->role; 
        if ($role != 'admin' &&  $role != 'emp') {
            return response()->json(['message' => 'not exiscte'],403);
        }
        $candidature = Candidatures::find($id);
        if(!$candidature) {
            return response()->json(['message' => 'Candidature non trouvée'], 404);
        }
        return response()->json($candidature);
    }

    /**
     * @OA\Get(
     * path="/api/candidatures/user/{user_id}",
     * summary="Liste des candidatures par utilisateur",
     * description="Récupère toutes les candidatures soumises par un utilisateur spécifique (ID). L'accès doit être restreint à l'utilisateur lui-même ou à un admin/employeur (Logique d'autorisation supposée).",
     * tags={"Candidatures"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="user_id",
     * in="path",
     * required=true,
     * description="ID de l'utilisateur dont on veut récupérer les candidatures.",
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\Response(
     * response=200, 
     * description="Liste des candidatures récupérées avec succès.",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="user_id", type="integer", example=1),
     * @OA\Property(property="job_id", type="integer", example=1),
     * @OA\Property(property="full_name", type="string", example="Jean Dupont"),
     * @OA\Property(property="can_email", type="string", format="email", example="jean.dupont@candidat.com"),
     * @OA\Property(property="phone_number", type="string", example="0612345678"),
     * @OA\Property(property="motivation", type="string", nullable=true, example="Motivation pour le poste."),
     * @OA\Property(property="created_at", type="string", format="date-time"),
     * @OA\Property(property="updated_at", type="string", format="date-time")
     * )
     * )
     * ),
     * @OA\Response(
     * response=404, 
     * description="Aucune candidature trouvée pour cet utilisateur.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Aucune candidature trouvée")
     * )
     * )
     * )
     */
    public function getByUser($user_id){
    $candidatures = Candidatures::where('user_id', $user_id)->get();
    if ($candidatures->isEmpty()) {
        return response()->json(['message' => 'Aucune candidature trouvée'], 404);
    }
    return response()->json($candidatures);
    }

    /**
     * @OA\Get(
     * path="/api/candidatures/job/{job_id}",
     * summary="Liste des candidatures par offre d'emploi",
     * description="Récupère toutes les candidatures soumises pour une offre d'emploi spécifique (ID). L'accès est limité aux rôles 'admin' et 'emp'.",
     * tags={"Candidatures"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="job_id",
     * in="path",
     * required=true,
     * description="ID de l'offre d'emploi pour laquelle on veut récupérer les candidatures.",
     * @OA\Schema(type="integer", example=5)
     * ),
     * @OA\Response(
     * response=200, 
     * description="Liste des candidatures récupérées avec succès.",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(
     * @OA\Property(property="id", type="integer", example=10),
     * @OA\Property(property="user_id", type="integer", example=1),
     * @OA\Property(property="job_id", type="integer", example=5),
     * @OA\Property(property="full_name", type="string", example="Jean Dupont"),
     * @OA\Property(property="can_email", type="string", format="email", example="jean.dupont@candidat.com"),
     * @OA\Property(property="phone_number", type="string", example="0612345678"),
     * @OA\Property(property="motivation", type="string", nullable=true, example="Motivation pour le poste."),
     * @OA\Property(property="created_at", type="string", format="date-time"),
     * @OA\Property(property="updated_at", type="string", format="date-time")
     * )
     * )
     * ),
     * @OA\Response(
     * response=403, 
     * description="Interdit (Non autorisé).",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="not exiscte")
     * )
     * ),
     * @OA\Response(
     * response=404, 
     * description="Aucune candidature trouvée pour cette offre d'emploi.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Aucune candidature trouvée")
     * )
     * )
     * )
     */
    public function getByJob($job_id){
        $user = $request->user();
        $role = $user->role->role; 
        if ($role != 'admin' &&  $role != 'emp') {
            return response()->json(['message' => 'not exiscte'],403);
        }
    $candidatures = Candidatures::where('job_id', $job_id)->get();
    if ($candidatures->isEmpty()) {
        return response()->json(['message' => 'Aucune candidature trouvée'], 404);
    }
    return response()->json($candidatures);
    }

    /**
     * @OA\Put(
     * path="/api/candidatures/{id}",
     * summary="Mettre à jour le statut ou les informations d'une candidature",
     * description="Met à jour les détails d'une candidature par son ID. L'accès est limité aux rôles 'admin' et 'emp'.",
     * tags={"Candidatures"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID de la candidature à mettre à jour.",
     * @OA\Schema(type="integer", example=10)
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * description="Seuls les champs fournis seront mis à jour. (Ex: mettre à jour le statut.)",
     * @OA\Property(property="full_name", type="string", example="Jean Dupont MODIFIE", nullable=true),
     * @OA\Property(property="phone_number", type="string", example="0799887766", nullable=true),
     * @OA\Property(property="status", type="string", example="en attente", enum={"en attente", "acceptée", "rejetée"}, nullable=true, description="Exemple d'un champ de statut qui pourrait être mis à jour.")
     * )
     * ),
     * @OA\Response(
     * response=200, 
     * description="Candidature mise à jour avec succès. Retourne l'objet mis à jour.",
     * @OA\JsonContent(
     * @OA\Property(property="id", type="integer", example=10),
     * @OA\Property(property="user_id", type="integer", example=1),
     * @OA\Property(property="job_id", type="integer", example=5),
     * @OA\Property(property="full_name", type="string", example="Jean Dupont MODIFIE"),
     * @OA\Property(property="can_email", type="string", format="email", example="jean.dupont@candidat.com"),
     * @OA\Property(property="phone_number", type="string", example="0799887766"),
     * @OA\Property(property="motivation", type="string", nullable=true, example="Motivation pour le poste."),
     * @OA\Property(property="updated_at", type="string", format="date-time"),
     * @OA\Property(property="created_at", type="string", format="date-time")
     * )
     * ),
     * @OA\Response(
     * response=403, 
     * description="Interdit (Non autorisé).",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="not exiscte")
     * )
     * ),
     * @OA\Response(
     * response=404, 
     * description="Candidature non trouvée.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Candidature non trouvée")
     * )
     * ),
     * @OA\Response(response=422, description="Erreur de validation des données.")
     * )
     */
    
    public function update(Request $request, $id){
        $user = $request->user();
        $role = $user->role->role; 
        if ($role != 'admin' &&  $role != 'emp') {
            return response()->json(['message' => 'not exiscte'],403);
        }
        $candidature = Candidatures::find($id);
        if (!$candidature){
            return response()->json(['message' => 'Candidature non trouvée'], 404);
        }
        $candidature->update($request->all());
        return response()->json($candidature);
    }

    /**
     * @OA\Delete(
     * path="/api/candidatures/{id}",
     * summary="Supprimer une candidature",
     * description="Supprime définitivement une candidature par son ID. L'accès est limité aux rôles 'admin' et 'emp'.",
     * tags={"Candidatures"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID de la candidature à supprimer.",
     * @OA\Schema(type="integer", example=10)
     * ),
     * @OA\Response(
     * response=200, 
     * description="Candidature supprimée avec succès.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Candidature supprimée")
     * )
     * ),
     * @OA\Response(
     * response=403, 
     * description="Interdit (Non autorisé).",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="not exiscte")
     * )
     * ),
     * @OA\Response(
     * response=404, 
     * description="Candidature non trouvée.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Candidature non trouvée")
     * )
     * )
     * )
     */
    public function destroy($id){
        $user = $request->user();
        $role = $user->role->role; 
        if ($role != 'admin' &&  $role != 'emp') {
            return response()->json(['message' => 'not exiscte'],403);
        }
        $candidature = Candidatures::find($id);
        if (!$candidature) {
            return response()->json(['message' => 'Candidature non trouvée'], 404);
        }
        $candidature->delete();
        return response()->json(['message' => 'Candidature supprimée']);
    }
}
