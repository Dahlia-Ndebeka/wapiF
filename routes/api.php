<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\utilisateursController;
use App\Http\Controllers\authentificationsController;
use App\Http\Controllers\categoriesController;
use App\Http\Controllers\souscategoriesController;
use App\Http\Controllers\etablissementsController;
use App\Http\Controllers\paysController;
use App\Http\Controllers\departementsController;
use App\Http\Controllers\villesController;
use App\Http\Controllers\arrondissementsController;


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


    //Routes concernanats les pays

    Route::get('pays', [paysController::class, 'Pays']);
    Route::post('pays', [paysController::class, 'createPays']);
    Route::get('pays/{id}', [paysController::class, 'getPays']);
    Route::put('pays/{id}', [paysController::class, 'putPays']);


    //Routes concernanats les departements

    Route::get('departements', [departementsController::class, 'Departements']);
    Route::post('departement', [departementsController::class, 'createDepartement']);
    Route::get('departement/{id}', [departementsController::class, 'getDepartement']);
    Route::put('departement/{id}', [departementsController::class, 'putDepartement']);


    //Routes concernanats les villes

    Route::get('villes', [villesController::class, 'Villes']);
    Route::post('ville', [villesController::class, 'createVille']);
    Route::get('ville/{id}', [villesController::class, 'getVille']);
    Route::put('ville/{id}', [villesController::class, 'putVille']);

    //Routes concernanats les arrondissements

    Route::get('arrondissements', [arrondissementsController::class, 'Arrondissements']);
    Route::post('arrondissement', [arrondissementsController::class, 'createArrondissement']);
    Route::get('arrondissement/{id}', [arrondissementsController::class, 'getArrondissement']);
    Route::put('arrondissement/{id}', [arrondissementsController::class, 'putArrondissement']);


    //Routes concernanats les etablissements

    Route::get('etablissements', [etablissementsController::class, 'Etablissements']);
    Route::post('etablissement', [etablissementsController::class, 'createEtablissement']);
    Route::get('etablissement/{id}', [etablissementsController::class, 'Etablissement']);
    // Route::get('etablissementSC/{id}', [etablissementsController::class, 'SousCategories']);

});
