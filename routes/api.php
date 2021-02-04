<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\utilisateursController;
use App\Http\Controllers\authentificationsController;
use App\Http\Controllers\categoriesController;
use App\Http\Controllers\souscategoriesController;


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


    //Routes concernanats les Categories 

    Route::get('Categories', [categoriesController::class, 'Categories']);
    Route::post('Categorie', [categoriesController::class, 'createCategorie']);
    Route::get('Categorie/{id}', [categoriesController::class, 'Categorie']);
    Route::put('Categorie/{id}', [categoriesController::class, 'putCategorie']);

    //Routes concernanats les sous Categories 

    Route::get('sousCategories', [souscategoriesController::class, 'sousCategories']);
    Route::post('sousCategorie', [souscategoriesController::class, 'createSousCategorie']);
    Route::get('sousCategorie/{id}', [souscategoriesController::class, 'sousCategorie']);
    Route::put('sousCategorie/{id}', [souscategoriesController::class, 'putSousCategorie']);
    Route::get('sousCategorieA/{id}', [souscategoriesController::class, 'getAnnuaire']);


    //Routes concernanats les annuaires 

    Route::get('Annuaires', [AnnuaireController::class, 'Annuaires']);
    Route::post('Annuaire', [AnnuaireController::class, 'createAnnuaire']);
    Route::get('Annuaire/{id}', [AnnuaireController::class, 'Annuaire']);
    Route::get('AnnuaireSC/{id}', [AnnuaireController::class, 'SousCategories']);

});
