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
<<<<<<< HEAD

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
=======
        if ($user->role === 'etudiant') {
>>>>>>> 9209062 (commit back)
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
<<<<<<< HEAD
         });
=======
        });
>>>>>>> 9209062 (commit back)

            return response()->json([
                "message" => "Liste des cours disponibles pour l'étudiant",
                "cours" => $cours,
            ]);
        }
<<<<<<< HEAD
=======


>>>>>>> 9209062 (commit back)
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
<<<<<<< HEAD
           'archived' => $c->trashed(),
=======
           'archived' => $c->trashed(), 
>>>>>>> 9209062 (commit back)
        ];
    });
        } else if ($user->role === 'formateur') {
            if (!$user->formateur) {
                return response()->json([
                    'message' => 'Aucun formateur associé à cet utilisateur.'
                ], 404);
            }

            $formateurId = $user->formateur->id;
<<<<<<< HEAD

=======
            
>>>>>>> 9209062 (commit back)
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
<<<<<<< HEAD
           'archived' => $c->trashed(),
=======
           'archived' => $c->trashed(), 
>>>>>>> 9209062 (commit back)
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
<<<<<<< HEAD


public function store(Request $request){

    //Récupérer l'utilisateur connecté
=======


public function store(Request $request)
{
    // Récupérer l'utilisateur connecté
>>>>>>> 9209062 (commit back)
    $user = auth()->user();

    if (!in_array($user->role, ['formateur', 'administrateur'])) {
        return response()->json([
            'message' => 'Accès non autorisé. Seuls les administrateurs et formateurs peuvent accéder à un cours.'
        ], 403);
    }

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'nullable|string|max:255',
        'date_de_creation' => 'nullable|date',
        'duree' => 'nullable|integer|max:255',
        'prix' => 'nullable|numeric|max:255',
        'niveau_de_difficulte' => 'nullable|in:avance,moyen,basique',
        'gratuit' => 'nullable|boolean',
        'categorie_id' => 'required|exists:categories,id',
        'photo_path' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
    ]);

    if (!isset($validated['date_de_creation']) ){
    $validated['date_de_creation'] = now()->toDateString(); // Format Y-m-d
}

<<<<<<< HEAD
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
=======
if ($request->hasFile('photo_path')) {
    $path = $request->file('photo_path')->store('cours', 'public');
    $validated['photo_path'] = $path;
>>>>>>> 9209062 (commit back)
}
    // Associer l'id du formateur selon le rôle
    if ($user->role === 'formateur') {
        $validated['formateur_id'] = $user->formateur->id;
    }

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
<<<<<<< HEAD

}
$cours=Cours::create($validated);

 return response()->json([
    "message"=>"cours crée avec succées",
    "cours"=>$cours,
=======
>>>>>>> 9209062 (commit back)

    $cours = Cours::create($validated);

<<<<<<< HEAD
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

=======
    return response()->json([
        "message" => "Cours créé avec succès",
        "cours" => $cours,
>>>>>>> 9209062 (commit back)
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

<<<<<<< HEAD
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
=======

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
>>>>>>> 9209062 (commit back)
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
