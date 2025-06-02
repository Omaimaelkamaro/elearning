<?php

namespace App\Http\Controllers\Mail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Welcome;

class MailController extends Controller
{
    public function Email(){
        $user=auth()->user();

      Mail::to($user->email)->send(new Welcome());
      return response()->json([
        'message' => 'Email envoyé avec succès à ' . $user->email
    ]);

    }

    public function store(Request $request) {
        $user = auth()->user();
    
        
        $request->validate([
            'message' => 'required|string'
        ]);
    
        $recepteur = env('MAIL_USERNAME'); 
        
        
            Mail::raw($request->message, function($message) use ($request, $recepteur, $user) {
                $message->to($recepteur)
                       ->subject("Contact us")
                       ->replyTo($user->email, $user->name);
            });
    
            return response()->json([
            'success' => 'Thanks for contacting us!',
             'message' => 'Merci pour votre message !',
            'details' => [
                'expediteur' => $user->email,
                'destinataire' => $recepteur
            ]
        ]);
            
        
        
    }
}
