<?php

namespace App\Http\Controllers\cours;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\cours;

class CoursController extends Controller
{
   public function index()
{
    try {
        $user = auth()->user();

        if (!$user) {
     $cours = Cours::all()->map(function ($c) {
                return [
                    'id' => $c->id,
                    'title' => $c->title,
                    'description' => $c->description,
                    'date_de_creation' => $c->date_de_creation,
                    'duree' => $c->duree,
                    'prix' => $c->prix,
                    'niveau_de_difficulte' => $c->niveau_de_difficulte,
                    'gratuit' => $c->gratuit,
                    'categorie_id' => $c->categorie_id,
                    'formateur_id' => $c->formateur_id,
                    'photo_path' => $c->photo_path,
                    'archived' => false, // l’étudiant ne voit que les cours actifs
                ];
         });

            return response()->json([
                "message" => "Liste des cours disponibles pour l'étudiant",
                "cours" => $cours,
            ]);
}

         if ($user->role === 'etudiant') {
            $cours = Cours::all()->map(function ($c) {
                return [
                    'id' => $c->id,
                    'title' => $c->title,
                    'description' => $c->description,
                    'date_de_creation' => $c->date_de_creation,
                    'duree' => $c->duree,
                    'prix' => $c->prix,
                    'niveau_de_difficulte' => $c->niveau_de_difficulte,
                    'gratuit' => $c->gratuit,
                    'categorie_id' => $c->categorie_id,
                    'formateur_id' => $c->formateur_id,
                    'photo_path' => $c->photo_path,
                    'archived' => false, // l’étudiant ne voit que les cours actifs
                ];
         });

            return response()->json([
                "message" => "Liste des cours disponibles pour l'étudiant",
                "cours" => $cours,
            ]);
        }
        if (!in_array($user->role, ['formateur', 'administrateur'])) {
            return response()->json([
                'message' => 'Accès non autorisé.'
            ], 403);
        }

       if ($user->role==='administrateur') {

             $cours = Cours::withTrashed()->get()->map(function ($c) {
        return [
            'id' => $c->id,
            'title' => $c->title,
            'description' => $c->description,
            'date_de_creation' => $c->date_de_creation,
            'duree' => $c->duree,
            'prix' => $c->prix,
            'niveau_de_difficulte' => $c->niveau_de_difficulte,
            'gratuit' => $c->gratuit,
            'categorie_id' => $c->categorie_id,
            'formateur_id' => $c->formateur_id,
            'photo_path' => $c->photo_path,
           'archived' => $c->trashed(),
        ];
    });
        } else if ($user->role === 'formateur') {
            if (!$user->formateur) {
                return response()->json([
                    'message' => 'Aucun formateur associé à cet utilisateur.'
                ], 404);
            }

            $formateurId = $user->formateur->id;

             $cours = Cours::where('formateur_id', $formateurId)->withTrashed()->get()->map(function ($c) {
        return [
            'id' => $c->id,
            'title' => $c->title,
            'description' => $c->description,
            'date_de_creation' => $c->date_de_creation,
            'duree' => $c->duree,
            'prix' => $c->prix,
            'niveau_de_difficulte' => $c->niveau_de_difficulte,
            'gratuit' => $c->gratuit,
            'categorie_id' => $c->categorie_id,
            'formateur_id' => $c->formateur_id,
            'photo_path' => $c->photo_path,
           'archived' => $c->trashed(),
        ];
    });
        }

        return response()->json([
            "message" => "Liste des cours",
            "cours" => $cours,
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Erreur serveur : ' . $e->getMessage(),
        ], 500);
    }
}


public function store(Request $request){

    //Récupérer l'utilisateur connecté
    $user = auth()->user();
if(!in_array($user->role,['formateur','administrateur'])){

    return response()->json([
        'message' => 'Accès non autorisé. Seuls les administrateurs et formateurs peuvent accéder à un cours.'
    ], 403);

}

$validated=$request->validate([
        'title'=>'required|string|max:255',
        'description'=>'required|string|max:255',
        'date_de_creation'=>'date',
        'duree'=>'required|integer|max:255',
        'prix'=>'required|numeric|max:255',
        'niveau_de_difficulte'=>'required|in:avance,moyen,basique',
        'gratuit'=>'required|boolean',
        'categorie_id' => 'required',


]);
if ($user->role === 'formateur') {
    $validated['formateur_id'] = $user->formateur->id;
}

if($user->role=='administrateur'){
    if ($user->role === 'administrateur') {
        $formateurData = $request->validate([
            'formateur_id' => 'required|exists:formateur,id'
        ]);

        $formateurExists = \App\Models\Formateur::find($formateurData['formateur_id']);

        if (!$formateurExists) {
            return response()->json([
                'message' => 'Le formateur sélectionné n’existe pas.'
            ], 404);
        }
        $validated['formateur_id'] = $formateurData['formateur_id'];
    }

}
$cours=Cours::create($validated);

 return response()->json([
    "message"=>"cours crée avec succées",
    "cours"=>$cours,

 ]);

}

public function update(Request $request, $id) {
    $user = auth()->user();
    if (!in_array($user->role, ['formateur', 'administrateur'])) {
        return response()->json([
            'message' => 'Accès non autorisé. Seuls les administrateurs et formateurs peuvent accéder à un cours.'
        ], 403);
    }

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string|max:255',
        'langue' => 'nullable|string|max:255',
        'date_de_creation' => 'nullable|date',
        'duree' => 'nullable|integer|max:255',
        'prix' => 'nullable|numeric|max:255',
        'niveau_de_difficulte' => 'nullable|in:avance,moyen,basique',
        'gratuit' => 'nullable|boolean',

    ]);

    $cours = Cours::find($id);
    if (!$cours) {
        return response()->json([
            'message' => 'Ce cours n\'existe pas',
        ], 404);
    }

    $cours->update($validated);





    return response()->json([
        'message' => 'Cours modifié avec succès.',
        'cours' => $cours,
    ], 200);
}
 public function updateImage(Request $request, $id){

 $user = auth()->user();
    if (!in_array($user->role, ['formateur', 'administrateur'])) {
        return response()->json([
            'message' => 'Accès non autorisé. Seuls les administrateurs et formateurs peuvent accéder à un cours.'
        ], 403);
    }
 $request->validate([
        'photo_path' => 'required|image|mimes:jpeg,png,jpg|max:4096',
    ]);

    $cours = Cours::find($id);
    if (!$cours) {
        return response()->json([
            'message' => 'Cours non trouvé.',
        ], 404);
    }

    if ($request->hasFile('photo_path')) {
        $photo_path = $request->file('photo_path')->store('cours_photos', 'public');
        $cours->photo_path = $photo_path;
        $cours->save();
    }




    return response()->json([
        'message' => 'Photo mise à jour avec succès.',
        'photo_path' => $cours->photo_path,
    ], 200);
}

public function archiver($id)
{
    $user = auth()->user();

    if (!in_array($user->role, ['formateur', 'administrateur'])) {
        return response()->json([
            'message' => 'Accès non autorisé. Seuls les administrateurs et formateurs peuvent supprimer un cours.'
        ], 403);
    }

    $cours = Cours::findOrFail($id);

    // Si l'utilisateur est formateur, il ne peut supprimer que ses propres cours
    if ($user->role === 'formateur' && $cours->formateur_id !== $user->formateur->id) {
        return response()->json([
            'message' => 'Vous ne pouvez supprimer que vos propres cours.'
        ], 403);
    }

    $cours->delete();

    return response()->json([
        'message' => 'Cours archivé avec succès.',
    ], 200);
}


public function restore($id)
{
    $cours = Cours::withTrashed()->find($id);

    if (!$cours) {
        return response()->json([
            'message' => 'Cours introuvable.',
        ], 404);
    }

    if (!$cours->trashed()) {
        return response()->json([
            'message' => 'Ce cours n’est pas supprimé.',
        ], 400);
    }

    $cours->restore();

    return response()->json([
        'message' => 'Cours restauré avec succès.',
        'cours' => $cours
    ]);

}



public function destroy($id)
{
    $cours = Cours::withTrashed()->find($id);

    if (!$cours) {
        return response()->json([
            'message' => 'Cours introuvable.',
        ], 404);
    }

    $cours->forceDelete();

    return response()->json([
        'message' => 'Cours supprimé définitivement.',
    ]);
}


public function trash()
{
    $cours = Cours::onlyTrashed()->get();

    return response()->json([
        'message' => 'Liste des cours archivés',
        'cours' => $cours
    ]);
}





}
