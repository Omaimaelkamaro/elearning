<?php

namespace App\Http\Controllers\Inscriptions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Apprentissage;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CertificationController extends Controller
{
    public function getCertificatData($userId, $coursId)
    {
        // Récupération de l'apprentissage
        $apprentissage = Apprentissage::where('user_id', $userId)
            ->where('cours_id', $coursId)
            ->where('etat', 'termine')
            ->with('user', 'cours')
            ->first();

        if (!$apprentissage) {
            return response()->json([
                'message' => 'Aucun certificat disponible. Vérifiez l\'inscription ou la fin du cours.',
            ], 404); // Retourne un message d'erreur si aucun apprentissage n'est trouvé
        }

        return response()->json([
            'nom_utilisateur' => $apprentissage->user->name,
            'titre_cours' => $apprentissage->cours->title,
            'date_achèvement' => $apprentissage->updated_at->format('Y-m-d'),
        ]);
    }
}

