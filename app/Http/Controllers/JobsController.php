<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Jobs;

class JobsController extends Controller{

    public function index(){
        $jobs = Jobs::all();
        return response()->json($jobs);
    }

    public function show($id){
        $job = Jobs::find($id);
        if (!$job){
            return response()->json(['message' => 'Job not found'], 404);
        }
        return response()->json($job);
    }

    public function store(Request $request){
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

    public function update(Request $request, $id){
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

    public function destroy($id){
        $job = Jobs::find($id);
        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }
        $job->delete();
        return response()->json(['message' => 'Job deleted'], 200);
    }

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
