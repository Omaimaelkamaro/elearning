<?php

namespace App\Http\Controllers\Inscriptions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cours;
use App\Models\Inscription;

class InscriptionController extends Controller
{
    //création d'une inscription
   public function store(Request $request,$userId,$coursId){
    $user=auth()->user();

    $request->validate([
        'cours_id' ,
    ]);

    $cours = Cours::findOrFail($request->cours_id);
    $inscription = new Inscription();
    $inscription->user_id = $user->id;
    $inscription->cours_id = $cours->id;

    if ($cours->gratuit) {
        $inscription->statut_paiement = 'paye';
        $inscription->acces = true;
    } else {
        $inscription->statut_paiement = 'en_attente';
        $inscription->acces = false;
    }

    $inscription->save();
    return response()->json([
        'message' => 'Inscription créée avec succès.',
        'inscription' => $inscription
    ],);
}


public function updateAccess(Request $request, $id)
{
    $user = auth()->user();

    if ($user->role !== 'administrateur') {
        return response()->json([
            'message' => 'Accès non autorisé.'
        ],);
    }

    $validate=$request->validate([
       'acces' => 'required|boolean',
        'statut_paiement' => 'nullable|in:paye,en_attente'
    ]);

    $inscription = Inscription::findOrFail($id);

    $inscription->acces = $request->acces;

    if ($request->has('statut_paiement')) {
        $inscription->statut_paiement = $request->statut_paiement;
    }
    $inscription->save();

    return response()->json([
        'message' => 'Accès mis à jour avec succès.',
        'inscription' => $inscription,
    ]);
     
}
}