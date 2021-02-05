<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\utilisateursController;
use App\Http\Controllers\authentificationsController;
use App\Http\Controllers\categoriesController;
use App\Http\Controllers\souscategoriesController;
use App\Http\Controllers\etablissementsController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('utilisateur', [utilisateursController::class, 'createUtilisateur']);
Route::post("login", [authentificationsController::class, 'login']);
Route::get('utilisateurs', [utilisateursController::class, 'Utilisateurs']);


// Secure routes

Route::group(['middleware'=>['auth:sanctum']], function() {

    //Routes concernanats les comptes 

    Route::get('utilisateurs', [utilisateursController::class, 'Utilisateurs']);
    Route::get('utilisateur/{id}', [utilisateursController::class, 'getUtilisateur']);
    Route::put('utilisateur/{id}', [utilisateursController::class, 'putUtilisateur']);
    Route::delete('utilisateur/{id}', [utilisateursController::class, 'deleteUtilisateur']);
    Route::get("utilisateur/{login}", [utilisateursController::class, 'researchUtilisateur']);
    Route::get("utilisateurE/{id}", [utilisateursController::class, 'Etablissements']);
    Route::put("utilisateurImg/{id}", [utilisateursController::class, 'putImage']);
    Route::put("utilisateurPwd/{id}", [utilisateursController::class, 'putPassword']);
    Route::post("utilisateurImg", [utilisateursController::class, 'imageUtilisateur']);


    //Routes concernanats les Categories 

    Route::get('categories', [categoriesController::class, 'Categories']);
    Route::post('categorie', [categoriesController::class, 'createCategorie']);
    Route::get('categorie/{id}', [categoriesController::class, 'Categorie']);
    Route::put('categorie/{id}', [categoriesController::class, 'putCategorie']);

    //Routes concernanats les sous Categories 

    Route::get('sousCategories', [souscategoriesController::class, 'sousCategories']);
    Route::post('sousCategorie', [souscategoriesController::class, 'createSousCategorie']);
    Route::get('sousCategorie/{id}', [souscategoriesController::class, 'sousCategorie']);
    Route::put('sousCategorie/{id}', [souscategoriesController::class, 'putSousCategorie']);
    Route::get('sousCategorieE/{id}', [souscategoriesController::class, 'Etablissements']);


    //Routes concernanats les etablissements

    Route::get('etablissements', [etablissementsController::class, 'Etablissements']);
    Route::post('etablissement', [etablissementsController::class, 'createEtablissement']);
    Route::get('etablissement/{id}', [etablissementsController::class, 'Etablissement']);
    // Route::get('etablissementSC/{id}', [etablissementsController::class, 'SousCategories']);

});
