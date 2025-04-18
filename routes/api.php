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
    Route::get('/cours', [CoursController::class, 'index']);
    Route::post('/cours', [CoursController::class ,'store']);
    Route::put('/cours/{cours}', [CoursController::class ,'update']);
    Route::delete('/cours/archiver/{cours}', [CoursController::class ,'archiver']);
    Route::get('/cours/trash', [CoursController::class, 'trash']);
    Route::delete('/cours/{cours}', [CoursController::class ,'destroy']);
    Route::post('/cours/restore/{cours}', [CoursController::class ,'restore']);

    
});
        
    
Route::middleware('auth:sanctum')->get( '/user',function (Request $request) {
    return $request->user();
    
});
