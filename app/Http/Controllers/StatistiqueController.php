<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cours;
use App\Models\Formateur;
use App\Models\Apprentissage;


class StatistiqueController extends Controller
{
     public function getStatistiques()
    {
        $nbCours = Cours::count();
        $nbFormateurs = Formateur::count();
        $nbCertificats = Apprentissage::where('etat', 'termine')->count();

        return response()->json([
            'cours' => $nbCours,
            'formateurs' => $nbFormateurs,
            'certificats' => $nbCertificats,
        ]);
    }
}
