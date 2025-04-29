<?php

namespace App\Http\Controllers\Inscriptions;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Srmklive\PayPal\Services\PayPal as PayPalClient;
use App\Models\Paiement;
use App\Models\Inscription;


class PaiementController extends Controller
{
    public function store(Request $request, $inscriptionId)
{
    $user = auth()->user();

    // Validation des données envoyées
    $request->validate([
        'inscription_id',
        'methode' => 'required|in:paypal,virement',
        'montant' => 'required|numeric',
        'preuve' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    // Création du paiement
    $paiement = new Paiement();
    $paiement->inscription_id = $request->inscription_id;
    $paiement->methode = $request->methode;
    $paiement->montant = $request->montant;

    if ($request->hasFile('preuve')) {
        $path = $request->file('preuve')->store('preuves_paiement', 'public');
        $paiement->preuve = $path;
    }
    $paiement->save();

    // Récupération de l'inscription liée au paiement
    $inscription = $paiement->inscription;

    // Si la méthode de paiement est PayPal
    if ($request->methode === 'paypal') {
        // Appel à la méthode pour initier le paiement PayPal
        return $this->payerAvecPaypal($inscriptionId);
    }

    // Si le paiement est par virement, on marque le statut comme payé immédiatement
    $inscription->statut_paiement = 'paye';
    $inscription->acces = true;
    $inscription->save();

    // Réponse pour virement
    return response()->json([
        'message' => 'Paiement enregistré avec succès (virement).',
        'paiement' => $paiement
    ], 201);
}


    public function update(Request $request, $id)
{
    $user = auth()->user();

    if ($user->role !== 'administrateur') {
        return response()->json([
            'message' => 'Seul l administrateur peut modifier un paiement.'
        ],);
    }

    $paiement = Paiement::find($id);

    if (!$paiement) {
        return response()->json([
            'message' => 'Paiement introuvable.'
        ], 404);
    }

    $validate=$request->validate([
        'methode' => 'required|in:paypal,virement',
        'montant' => 'required|numeric',
        'preuve' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
    ]);

    if ($request->hasFile('preuve')) {
        $path = $request->file('preuve')->store('preuves_paiement', 'public');
        $validated['preuve'] = $path;
    }
    
$paiement->update($validate);

   

    return response()->json([
        'message' => 'Paiement mis à jour avec succès.',
        'paiement' => $paiement
    ], 200);
}





    // 🔹 1. Créer l’ordre PayPal
    public function payerAvecPaypal($inscriptionId)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $token = $provider->getAccessToken();
        $provider->setAccessToken($token);

        $inscription = Inscription::findOrFail($inscriptionId);
        $montant = $inscription->cours->prix;

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "purchase_units" => [
                [
                    "amount" => [
                        "currency_code" => "EUR",
                        "value" => $montant
                    ]
                ]
            ],
            "application_context" => [
                "cancel_url" => route('paypal.cancel'),
                "return_url" => route('paypal.success', ['inscriptionId' => $inscriptionId]),
            ]
        ]);

        if (isset($response['id']) && $response['status'] === 'CREATED') {
            foreach ($response['links'] as $link) {
                if ($link['rel'] === 'approve') {
                    return response()->json([
                        'status' => 'success',
                        'approval_url' => $link['href']
                    ]);
                }
            }
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Erreur lors de la création du paiement PayPal.'
        ], 500);
        
        
    }

    // 🔹 2. Validation du paiement PayPal
    public function paypalSuccess(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $token = $provider->getAccessToken();
        $provider->setAccessToken($token);

        $response = $provider->capturePaymentOrder($request->query('token'));

        if ($response['status'] === 'COMPLETED') {
            $inscriptionId = $request->query('inscriptionId');
            $montant = $response['purchase_units'][0]['payments']['captures'][0]['amount']['value'];

            $paiement = Paiement::create([
                'inscription_id' => $inscriptionId,
                'methode' => 'paypal',
                'montant' => $montant
            ]);

            $inscription = Inscription::findOrFail($inscriptionId);
            $inscription->statut_paiement = 'paye';
            $inscription->acces = true;
            $inscription->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Paiement effectué avec succès.',
                'paiement' => $paiement
            ]);
        }//HAJARRRRRR

        return response()->json([
            'status' => 'error',
            'message' => 'Erreur lors du paiement PayPal.'
        ], 500);
    }

    // 🔹 3. Annulation du paiement PayPal
    public function paypalCancel()
    {
        return response()->json([
            'status' => 'cancelled',
            'message' => 'Paiement annulé par l’utilisateur.'
        ]);
    }






}