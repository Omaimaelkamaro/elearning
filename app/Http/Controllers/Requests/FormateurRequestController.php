<?php

namespace App\Http\Controllers\Requests;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormateurRequest;
use App\Models\Etudiant;
use App\Models\User;


class FormateurRequestController extends Controller
{
// creer une demande
   public function store(Request $request)
{
   $user = auth()->user();
$validated=$request->validate(['persuasion' => 'required|string']);
$etudiant = Etudiant::where('user_id', $user->id)->first();

if (!$etudiant) {
    return response()->json([
        'message' => 'Étudiant non trouvé pour cet utilisateur.',
    ], 404);
}

// Vérifie si une demande existe déjà pour cet étudiant
$existingRequest = FormateurRequest::where('etudiant_id', $etudiant->id)->first();
if ($existingRequest) {
    return response()->json([
        'message' => 'Vous avez déjà soumis une demande.',
    ], 409);
}

// Crée la demande avec le bon etudiant_id
FormateurRequest::create([
    'etudiant_id' => $etudiant->id,
    'persuasion' => $validated['persuasion'],
    'status' => 'en_attente',
]);

return response()->json([
    'message' => 'Demande envoyée avec succès',
], 201);

}




    // Récupérer toutes les demandes
public function index() {

    $user = auth()->user();

    if ($user->role !== 'administrateur') {
        return response()->json([
            'message' => 'Seul l administrateur peut voir les demandes.'
        ],);
    }

    $demandes = FormateurRequest::with('etudiant')->latest()->get();
    return response()->json($demandes, 200);
}
// Montrer les détails d’une seule demande
public function show($id) {

    $user = auth()->user();

    if ($user->role !== 'administrateur') {
        return response()->json([
            'message' => 'Seul l administrateur peut voir les demandes.'
        ],);
    }

    $demande = FormateurRequest::with('etudiant')->find($id);

    if (!$demande) {
        return response()->json(['message' => 'Demande non trouvée'], 404);
    }
    return response()->json($demande, 200);
}


    //permettre à l’admin de valider ou refuser une demande

    public function updateStatut(Request $request, $id) {
        $user = auth()->user();
        if ($user->role !== 'administrateur') {
            return response()->json([
                'message' => 'Seul l administrateur peut valider ou refuser une demande'
            ]);
        }

        $validated = $request->validate([
            'status' => 'required|in:approuvee,rejetee',
            'motif_rejet' => 'nullable|string|max:255'
        ]);

        if ($validated['status'] === 'rejetee' && empty($validated['motif_rejet'])) {
            return response()->json([
                'message' => 'Le motif de rejet est obligatoire en cas de refus.'
            ], 422);
        }

        $demande = FormateurRequest::find($id);
        if (!$demande) {
            return response()->json(['message' => 'Demande non trouvée'], 404);
        }

        if ($validated['status'] === 'rejetee') {
            $demande->delete();
            return response()->json(['message' => 'Demande rejetée et supprimée'], 200);
        }

        $demande->update([
            'status' => 'approuvee',
            'motif_rejet' => null,
        ]);

        $etudiant = $demande->etudiant;

        if ($demande->status === 'approuvee') {
            $user = $etudiant->user;

            // Créer formateur
            $user->formateur()->create([
                'specialite' => 'à définir',
            ]);

            // Mettre à jour le rôle de l'utilisateur
            $user->update([
                'role' => 'formateur',
            ]);

            // Supprimer l'étudiant
            $etudiant->delete();
        }

        return response()->json(['message' => 'Demande approuvée avec succès', 'data' => $demande], 200);
    }









}
