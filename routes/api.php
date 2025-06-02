<?php

use App\Http\Controllers\Users\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Users\EtudiantController;
use App\Http\Controllers\Users\FormateurController;
use App\Http\Controllers\cours\CoursController;
use App\Http\Controllers\Inscriptions\InscriptionController;
use App\Http\Controllers\Inscriptions\PaiementController;



use App\Http\Controllers\cours\CategorieController;
use App\Http\Controllers\cours\ModuleController;
use App\Http\Controllers\Quiz\OptionReponseController;
use App\Http\Controllers\Quiz\QuizController;
use App\Http\Controllers\Quiz\QuestionController;
use App\Http\Controllers\Quiz\ReponseEtudiantController;
use App\Http\Controllers\Quiz\ResultController;
use App\Http\Controllers\Requests\FormateurRequestController;

use App\Http\Controllers\Inscriptions\ApprentissageController;
use App\Http\Controllers\Inscriptions\CertificationController;
use App\Http\Controllers\StatistiqueController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
*/

Route::group([],function(){
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', [RegisterController::class, 'register']);
    Route::post('/logout', [LoginController::class, 'logout']);
    //statistique about us
    Route::get('/statistiques', [StatistiqueController::class, 'getStatistiques']);
 Route::get('/categories', [CategorieController::class, 'index']);
   Route::get('/cours', [CoursController::class, 'index']);

});
// Route::get('/login', [LoginController::class, 'showLoginform']);





Route::middleware('auth:sanctum')->group( function () {

    //Les routes qui concerne user


    Route::get('/users', [UserController::class, 'index']);
    // ajouter les utilisateurs à la base de donné
    Route::post('/users', [UserController::class ,'store']);
    // retourne un utilisateur
    Route::get('/users/{user}', [UserController::class ,'show']);
    // retourner la formulaire de modification d'un utilisateur

    // modifier un utilisateur user
    Route::put('/users/{user}', [UserController::class ,'update']);
    // supprimer un utilisateur
    Route::delete('/users/{user}', [UserController::class ,'destroy']);




    //les routes d'un formateurs
    Route::get('/formateurs', [FormateurController::class, 'index']);
    Route::post('/formateurs', [FormateurController::class ,'store']);
    Route::put('/formateurs/{formateur}', [FormateurController::class ,'update']);
    Route::delete('/formateurs/{formateur}', [FormateurController::class ,'destroy']);



    //les routes pour etudiant
    Route::get('/etudiants', [EtudiantController::class, 'index']);
    Route::post('/etudiants', [EtudiantController::class ,'store']);
    Route::put('/etudiants/{etudiant}', [EtudiantController::class ,'update']);
    Route::delete('/etudiants/{etudiant}', [EtudiantController::class ,'destroy']);



    //les routes pour administrateur
    Route::get('/admins', [AdminController::class, 'index']);
    Route::post('/admins', [AdminController::class ,'store']);
    Route::put('/admins/{admin}', [AdminController::class ,'update']);
    Route::delete('/admins/{admin}', [AdminController::class ,'destroy']);


    //les routes pour cours

    Route::post('/cours', [CoursController::class ,'store']);
    Route::put('/cours/{cours}', [CoursController::class ,'update']);
    Route::delete('/cours/archiver/{cours}', [CoursController::class ,'archiver']);
    Route::get('/cours/trash', [CoursController::class, 'trash']);
    Route::delete('/cours/{cours}', [CoursController::class ,'destroy']);
    Route::post('/cours/restore/{cours}', [CoursController::class ,'restore']);
    Route::put('/coursImage/{cours}', [CoursController::class ,'updateImage']);



    //les routes pour l'Inscription
    Route::post('/inscriptions/{cours_id}/{user_id}', [InscriptionController::class, 'store']);//Inscription
    Route::get('/inscriptions', [InscriptionController::class, 'index']);//Voir tt les inscriptions
    Route::get('/inscriptions/{id}', [InscriptionController::class, 'show']);//Voir une inscription précise
    Route::put('/inscriptions/{id}', [InscriptionController::class, 'updateAccess']);

    // les routes pour les paiements
    Route::post('/paiements/{inscription_id}', [PaiementController::class, 'store']);//Enregistrer un paiement
    Route::get('/paiements', [PaiementController::class, 'index']);//Voir tous les paiements
    Route::get('/paiements/{id}', [PaiementController::class, 'show']);//Voir un paiement spécifique
    Route::put('/paiements/{id}', [PaiementController::class, 'update']);


    Route::get('/paypal/success', [PaiementController::class, 'paypalSuccess'])->name('paypal.success');
    Route::get('/paypal/cancel', [PaiementController::class, 'paypalCancel'])->name('paypal.cancel');


    //les routes pour une catégorie

    Route::post('/categories', [CategorieController::class ,'store']);
    Route::put('/categories/{categories}', [CategorieController::class ,'update']);
    Route::delete('/categories/{categories}', [CategorieController::class ,'destroy']);


    //les routes pour les modules
    Route::get('/modules/{cours_id}', [ModuleController::class, 'index']);
    Route::post('/modules/{cours_id}', [ModuleController::class ,'store']);
    Route::put('/modules/{cours_id}/{module_id}', [ModuleController::class ,'update']);
    Route::delete('/modules/{cours_id}/{module_id}', [ModuleController::class ,'destroy']);

    //les routes pour les quizs

    Route::put('/quizs/{quiz_id}', [QuizController::class ,'update']);
    Route::post('/quizs/{module_id}', [QuizController::class ,'store']);
    Route::delete('/quizs/{quize_id}', [QuizController::class ,'destroy']);



    //les routes pour les questions

    Route::get('/questions/{quiz_id}', [QuestionController::class ,'index']);
    Route::put('/questions/{question_id}', [QuestionController::class ,'update']);
    Route::post('/questions/{quiz_id}', [QuestionController::class ,'store']);
    Route::delete('/questions/{question_id}', [QuestionController::class ,'destroy']);

    //les routes pour les options des reponses
    Route::put('/reponses/{reponse_id}', [OptionReponseController::class ,'update']);
    Route::post('/reponses/{question_id}', [OptionReponseController::class ,'store']);
    Route::delete('/reponses/{question_id}', [OptionReponseController::class ,'destroy']);

    //les routes pour les reponses de l'etudiant
    Route::post('/reponsesEtud/{question_id}/{option_reponse_id}', [ReponseEtudiantController::class ,'store']);

    //les routes pour les résultats
    Route::get('/resultats', [ResultController::class ,'index']);
    Route::post('/reponsesEtud/{question_id}/{option_reponse_id}/{resultat_id}', [ResultController::class ,'store']);



    //les routes pour les demandes (devenir formateur)


 Route::post('/formateur-request', [FormateurRequestController::class, 'store']);// Créer une nouvelle demande

 Route::get('/formateur-requests', [FormateurRequestController::class, 'index']);// Voir toutes les demandes

 Route::get('/formateur-request/{id}', [FormateurRequestController::class, 'show']); // Voir une seule demande

 Route::put('/formateur-request/{id}/statut', [FormateurRequestController::class, 'updateStatut']);// Valider ou rejeter une demande



    //les routes pour le système d'apprentissage
    Route::post('/apprentissages/{userId}/{coursId}/{moduleId}', [ApprentissageController::class, 'completerModule']);
    Route::get('/apprentissages/{userId}', [ApprentissageController::class, 'coursPourEtudiant']);
    Route::get('/apprentissages', [ApprentissageController::class, 'index']);


    //Route pour certificat
    Route::get('/certificat/{userId}/{coursId}', [CertificationController::class, 'getCertificatData']);





});



Route::middleware('auth:sanctum')->get( '/user',function (Request $request) {
    return $request->user();

});


