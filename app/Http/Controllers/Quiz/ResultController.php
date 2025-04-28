<?php

namespace App\Http\Controllers\Quiz;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\reponse_etudiant;
use App\Models\Resultat;

class ResultController extends Controller
{
    public function index()
    {
         $user=auth()->user();

        if(!in_array($user->role,['formateur','administrateur'])){
            return response()->json([
                'message'=>"vous n'avez pas d'accés"
            ]);
            
        }  
         $resultats = Resultat::with(['user','quiz',])
        ->get()
        ->map(function ($resultat)
       
        
            
                
        {
           

            return [
                'score' => $resultat->score,
                'nom_utilisateur' => $resultat->user->name ?? 'Utilisateur inconnu',
                'Quiz' => $resultat->quiz->titre  ?? 'quiz supprimé',
            ];
        });
        
        return response()->json([
            'message' => 'Liste des résultats des étudiants',
            'résultats' => $resultats
        ]);

    
}
    



    public function store($userId, $quizId)
{
    // on recupere toutes les reponses de toutes les questions d'un utilisateur pour un quiz specifique 
    $reponses = Reponse_etudiant::whereHas('question', function($query) use ($quizId) {
        $query->where('quiz_id', $quizId);
    })->where('user_id', $userId)
    ->get();

    // Calculer le score et le nbr de rep correcte
    $nbr_rep_corr = $reponses->where('est_correcte', true)->count(); 
    
    $score = $reponses
    ->filter(function ($reponse) {
        return $reponse->est_correcte;
    })
    ->sum(function ($reponse) {
        return $reponse->question->points ?? 0;
    });


    // Créer le résultat
    $resultat = Resultat::create([
        'score' => $score,
        'user_id' => $userId,
        'quiz_id' => $quizId,
    ]);

    
    foreach ($reponses as $reponse) {
        $reponse->resultat_id = $resultat->id;
        $reponse->save();
    }

    return response()->json([
        'message' => 'Résultat enregistré avec succès',
        'score' => $score,
    ]);
}

}
