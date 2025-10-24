<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jobs;

class JobsController extends Controller{

    /**
     * @OA\Get(
     * path="/api/jobs",
     * summary="Liste de toutes les offres d'emploi",
     * description="Récupère la liste complète de toutes les offres d'emploi disponibles.",
     * tags={"Offres d'emploi"},
     * @OA\Response(
     * response=200, 
     * description="Liste des offres d'emploi récupérée avec succès.",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="title", type="string", example="Développeur Full Stack (Laravel/Vue)"),
     * @OA\Property(property="description", type="string", example="Création et maintenance de nos applications web."),
     * @OA\Property(property="salary", type="number", format="float", example=55000.00),
     * @OA\Property(property="location", type="string", example="Paris, France"),
     * @OA\Property(property="status", type="string", example="Ouvert", enum={"Ouvert", "Fermé", "En attente"}),
     * @OA\Property(property="created_at", type="string", format="date-time"),
     * @OA\Property(property="updated_at", type="string", format="date-time")
     * )
     * )
     * )
     * )
     */
    public function index(){
        $jobs = Jobs::all();
        return response()->json($jobs);
    }

    /**
     * @OA\Get(
     * path="/api/jobs/{id}",
     * summary="Afficher les détails d'une offre d'emploi",
     * description="Récupère les détails d'une offre d'emploi spécifique par son ID.",
     * tags={"Offres d'emploi"},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID de l'offre d'emploi à afficher.",
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\Response(
     * response=200, 
     * description="Détails de l'offre d'emploi récupérés avec succès.",
     * @OA\JsonContent(
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="title", type="string", example="Développeur Full Stack (Laravel/Vue)"),
     * @OA\Property(property="description", type="string", example="Création et maintenance de nos applications web."),
     * @OA\Property(property="salary", type="number", format="float", example=55000.00),
     * @OA\Property(property="location", type="string", example="Paris, France"),
     * @OA\Property(property="status", type="string", example="Ouvert", enum={"Ouvert", "Fermé", "En attente"}),
     * @OA\Property(property="created_at", type="string", format="date-time"),
     * @OA\Property(property="updated_at", type="string", format="date-time")
     * )
     * ),
     * @OA\Response(
     * response=404, 
     * description="Offre d'emploi non trouvée.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Job not found")
     * )
     * )
     * )
     */
    public function show($id){
        $job = Jobs::find($id);
        if (!$job){
            return response()->json(['message' => 'Job not found'], 404);
        }
        return response()->json($job);
    }

    /**
     * @OA\Post(
     * path="/api/jobs",
     * summary="Créer une nouvelle offre d'emploi",
     * description="Enregistre une nouvelle offre d'emploi. L'accès est limité aux rôles 'admin' et 'emp'.",
     * tags={"Offres d'emploi"},
     * security={{"bearerAuth":{}}},
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * required={"title","description","entreprise","ville","user_id"},
     * @OA\Property(property="title", type="string", example="Développeur Back-end Senior"),
     * @OA\Property(property="description", type="string", example="Responsable de l'architecture API et des bases de données."),
     * @OA\Property(property="entreprise", type="string", example="Tech Innov S.A."),
     * @OA\Property(property="ville", type="string", example="Casablanca"),
     * @OA\Property(property="user_id", type="integer", example=2, description="ID de l'utilisateur (employeur/admin) créant l'offre.")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Offre d'emploi créée avec succès.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Job created"),
     * @OA\Property(property="data", type="object",
     * @OA\Property(property="title", type="string", example="Développeur Back-end Senior"),
     * @OA\Property(property="description", type="string", example="Responsable de l'architecture API et des bases de données."),
     * @OA\Property(property="entreprise", type="string", example="Tech Innov S.A."),
     * @OA\Property(property="ville", type="string", example="Casablanca"),
     * @OA\Property(property="user_id", type="integer", example=2),
     * @OA\Property(property="id", type="integer", example=12),
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
     * @OA\Response(response=422, description="Erreur de validation des données.")
     * )
     */

    public function store(Request $request){
        $user = $request->user();
        $role = $user->role->role; 
        if ($role != 'admin' &&  $role != 'emp') {
            return response()->json(['message' => 'not exiscte'],403);
        }
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'entreprise' => 'required|string',
            'ville' => 'required|string',
            'user_id' => 'required|exists:users,id'
        ]);
        $job = Jobs::create($validated);
        return response()->json(['message' => 'Job created', 'data' => $job], 201);
    }

    /**
     * @OA\Put(
     * path="/api/jobs/{id}",
     * summary="Mettre à jour une offre d'emploi",
     * description="Met à jour les informations d'une offre d'emploi par son ID. L'accès est limité aux rôles 'admin' et 'emp'.",
     * tags={"Offres d'emploi"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID de l'offre d'emploi à mettre à jour.",
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\RequestBody(
     * required=true,
     * @OA\JsonContent(
     * description="Seuls les champs fournis dans le corps seront mis à jour.",
     * @OA\Property(property="title", type="string", example="Développeur Full Stack (Mise à jour)", nullable=true),
     * @OA\Property(property="description", type="string", example="Nouveau texte de description du poste.", nullable=true),
     * @OA\Property(property="entreprise", type="string", example="Tech Innov S.A. (Modifié)", nullable=true),
     * @OA\Property(property="ville", type="string", example="Rabat", nullable=true),
     * @OA\Property(property="user_id", type="integer", example=2, nullable=true)
     * )
     * ),
     * @OA\Response(
     * response=200, 
     * description="Mise à jour réussie. Retourne l'objet Job mis à jour.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="mise à jour réussie"),
     * @OA\Property(property="data", type="object",
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="title", type="string", example="Développeur Full Stack (Mise à jour)"),
     * @OA\Property(property="description", type="string", example="Nouveau texte de description du poste."),
     * @OA\Property(property="entreprise", type="string", example="Tech Innov S.A. (Modifié)"),
     * @OA\Property(property="ville", type="string", example="Rabat"),
     * @OA\Property(property="user_id", type="integer", example=2),
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
     * description="Offre non trouvée.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Offre non trouvée")
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
        $job = Jobs::find($id);
        if (!$job) {
            return response()->json(['message' => 'Offre non trouvée'], 404);
        }
        $validated = $request->validate([
            'title' => 'sometimes|string',
            'description' => 'sometimes|string',
            'entreprise' => 'sometimes|string',
            'ville' => 'sometimes|string',
            'user_id' => 'sometimes|exists:users,id'
        ]);
        $job->update($validated);
        return response()->json([ 'message' => 'mise à jour réussie', 'data' => $job ], 200);
    }

    /**
     * @OA\Delete(
     * path="/api/jobs/{id}",
     * summary="Supprimer une offre d'emploi",
     * description="Supprime définitivement une offre d'emploi par son ID. L'accès est limité aux rôles 'admin' et 'emp'.",
     * tags={"Offres d'emploi"},
     * security={{"bearerAuth":{}}},
     * @OA\Parameter(
     * name="id",
     * in="path",
     * required=true,
     * description="ID de l'offre d'emploi à supprimer.",
     * @OA\Schema(type="integer", example=1)
     * ),
     * @OA\Response(
     * response=200, 
     * description="Offre d'emploi supprimée avec succès.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Job deleted")
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
     * description="Offre d'emploi non trouvée.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Job not found")
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
        $job = Jobs::find($id);
        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }
        $job->delete();
        return response()->json(['message' => 'Job deleted'], 200);
    }

    /**
     * @OA\Get(
     * path="/api/jobs/search",
     * summary="Rechercher des offres d'emploi",
     * description="Permet de rechercher des offres d'emploi en utilisant des critères optionnels (titre, entreprise, ville).",
     * tags={"Offres d'emploi"},
     * @OA\Parameter(
     * name="title",
     * in="query",
     * required=false,
     * description="Recherche partielle par titre de l'offre d'emploi.",
     * @OA\Schema(type="string", example="Développeur")
     * ),
     * @OA\Parameter(
     * name="entreprise",
     * in="query",
     * required=false,
     * description="Recherche partielle par nom d'entreprise.",
     * @OA\Schema(type="string", example="Tech Innov")
     * ),
     * @OA\Parameter(
     * name="ville",
     * in="query",
     * required=false,
     * description="Recherche partielle par ville.",
     * @OA\Schema(type="string", example="Casablanca")
     * ),
     * @OA\Response(
     * response=200, 
     * description="Liste des offres d'emploi correspondant aux critères de recherche.",
     * @OA\JsonContent(
     * type="array",
     * @OA\Items(
     * @OA\Property(property="id", type="integer", example=1),
     * @OA\Property(property="title", type="string", example="Développeur Full Stack"),
     * @OA\Property(property="description", type="string", example="Description de l'offre."),
     * @OA\Property(property="entreprise", type="string", example="Tech Innov S.A."),
     * @OA\Property(property="ville", type="string", example="Casablanca"),
     * @OA\Property(property="created_at", type="string", format="date-time"),
     * @OA\Property(property="updated_at", type="string", format="date-time")
     * )
     * )
     * )
     * )
     */
    public function search(Request $request){
        $query = Jobs::query();
        if ($request->filled('title')) {
            $query->where('title', 'like', '%'.$request->title.'%');
        }
        if ($request->filled('entreprise')) {
            $query->where('entreprise', 'like', '%'.$request->entreprise.'%');
        }
        if ($request->filled('ville')) {
            $query->where('ville', 'like', '%'.$request->ville.'%');
        }
        $jobs = $query->get();
        return response()->json($jobs);
    }
}
