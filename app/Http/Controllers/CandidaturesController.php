<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\candidatures;

class CandidaturesController extends Controller{

    public function store(Request $request){
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'job_id' => 'required|exists:jobs,id',
            'full_name' => 'required|string',
            'can_email' => 'required|email',
            'phone_number' => 'required|string',
            'motivation' => 'nullable|string',
        ]);
        $candidature = Candidature::create($request->all());
        return response()->json($candidature, 201);
    }

    public function show($id){
        $candidature = Candidature::find($id);
        if(!$candidature) {
            return response()->json(['message' => 'Candidature non trouvée'], 404);
        }
        return response()->json($candidature);
    }

    public function getByUser($user_id){
    $candidatures = Candidature::where('user_id', $user_id)->get();
    if ($candidatures->isEmpty()) {
        return response()->json(['message' => 'Aucune candidature trouvée pour cet utilisateur'], 404);
    }
    return response()->json($candidatures);
    }

    public function getByJob($job_id){
    $candidatures = Candidature::where('job_id', $job_id)->get();
    if ($candidatures->isEmpty()) {
        return response()->json(['message' => 'Aucune candidature trouvée pour cette offre'], 404);
    }
    return response()->json($candidatures);
    }

    public function update(Request $request, $id){
        $candidature = Candidature::find($id);
        if (!$candidature){
            return response()->json(['message' => 'Candidature non trouvée'], 404);
        }
        $candidature->update($request->all());
        return response()->json($candidature);
    }

    public function destroy($id){
        $candidature = Candidature::find($id);
        if (!$candidature) {
            return response()->json(['message' => 'Candidature non trouvée'], 404);
        }
        $candidature->delete();
        return response()->json(['message' => 'Candidature supprimée avec succès']);
    }
}
