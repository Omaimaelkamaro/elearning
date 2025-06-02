<?php

namespace App\Http\Controllers\Inscriptions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Apprentissage;
use App\Models\Module;
use App\Models\Cours;
use App\Models\ApprentissageModule;

class ApprentissageController extends Controller
{
    public function completerModule($userId, $coursId, $moduleId)
{
    $apprentissage = Apprentissage::where('user_id', $userId)
                                  ->where('cours_id', $coursId)
                                  ->firstOrFail();

    $apprentissageModule = ApprentissageModule::firstOrCreate([
        'apprentissage_id' => $apprentissage->id,
        'module_id' => $moduleId,
    ]);

    $apprentissageModule->est_complete = true;
    $apprentissageModule->date_fin = now();
    $apprentissageModule->save();

    // Calcul automatique de la progression
    $totalModules = Module::where('cours_id', $coursId)->count();
    $modulesCompletes = ApprentissageModule::where('apprentissage_id', $apprentissage->id)
                                           ->where('est_complete', true)
                                           ->count();

    $progression = round(($modulesCompletes / $totalModules) * 100);
    $apprentissage->progression = $progression;
    $apprentissage->derniere_activite = now();

    if ($progression >= 100) {
        $apprentissage->etat = 'termine';
        $apprentissage->dateFin = now();
    } else {
        $apprentissage->etat = 'en_cours';
    }

    $apprentissage->save();

    return response()->json([
        'message' => 'Module complété, progression mise à jour',
        'progression' => $progression
    ]);
}

    public function coursPourEtudiant($userId)
    {

        $authUser = auth()->user();
        $user = User::findOrFail($userId);

        if ($authUser->role === 'administrateur') {
            $cours = $user->cours()
               ->select('cours.id', 'cours.title', 'cours.photo_path')

                ->withPivot('progression','etat', 'date_debut', 'dateFin', 'derniere_activite')
                ->get()
                ->map(function ($coursItem) use ($user) {
                    return [
                        'titre' => $coursItem->title,
                        'photo_path' => $coursItem->photo_path,
                        'nom_utilisateur' => $user->name,
                        'progression' => $coursItem->pivot->progression,
                        'etat' => $coursItem->pivot->etat,
                        'date_debut' => $coursItem->pivot->date_debut,
                        'dateFin' => $coursItem->pivot->dateFin,
                        'derniere_activite' => $coursItem->pivot->derniere_activite,
                    ];
                });

            return response()->json($cours);
        }

        else if ($authUser->role === 'formateur') {
            $formateurId = $authUser->formateur->id;
            $coursFormateur = Cours::where('formateur_id', $formateurId)->pluck('id');

            $cours = $user->cours()
                ->whereIn('cours.id', $coursFormateur)
               ->select('cours.id', 'cours.title', 'cours.photo_path')

                ->withPivot('progression','etat', 'date_debut', 'dateFin', 'derniere_activite')
                ->get()
                ->map(function ($coursItem) use ($user) {
                    return [
                        'titre' => $coursItem->title,
                        'photo_path' => $coursItem->photo_path,
                        'nom_utilisateur' => $user->name,
                        'progression' => $coursItem->pivot->progression,
                        'etat' => $coursItem->pivot->etat,
                        'date_debut' => $coursItem->pivot->date_debut,
                        'dateFin' => $coursItem->pivot->dateFin,
                        'derniere_activite' => $coursItem->pivot->derniere_activite,
                    ];
                });

                return response()->json($cours);
        }

       elseif ($authUser->id === $user->id) {
    $cours = $user->cours()
        ->select('cours.id', 'cours.title', 'cours.photo_path')

        ->withPivot('progression','etat', 'date_debut', 'dateFin', 'derniere_activite')
        ->get()
        ->map(function ($coursItem) use ($user) {
            return [
                'titre' => $coursItem->title,
                'photo_path' => $coursItem->photo_path,
                'nom_utilisateur' => $user->name,
                'progression' => $coursItem->pivot->progression,
                'etat' => $coursItem->pivot->etat,
                'date_debut' => $coursItem->pivot->date_debut,
                'dateFin' => $coursItem->pivot->dateFin,
                'derniere_activite' => $coursItem->pivot->derniere_activite,
            ];
        });

    return response()->json($cours);
}



        return response()->json(['message' => 'Accès non autorisé'], 403);

        }
    }


