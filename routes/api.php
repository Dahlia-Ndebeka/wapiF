<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\utilisateursController;
use App\Http\Controllers\authentificationsController;
use App\Http\Controllers\categoriesController;
use App\Http\Controllers\sous_categoriesController;
use App\Http\Controllers\etablissementsController;
use App\Http\Controllers\paysController;
use App\Http\Controllers\departementsController;
use App\Http\Controllers\villesController;
use App\Http\Controllers\arrondissementsController;
use App\Http\Controllers\calendriersController;
use App\Http\Controllers\annoncesController;
use App\Http\Controllers\commentairesController;
use App\Http\Controllers\annonce_imagesController;
use App\Http\Controllers\notesController;
use App\Http\Controllers\favorisController;


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



//Routes concernanats les Categories 

Route::get('categories', [categoriesController::class, 'Categories']);
Route::get('categorieSousCategorie/{id}', [categoriesController::class, 'Souscategories']);
Route::get('categorie/{id}', [categoriesController::class, 'Categorie']);
Route::get('categorieEts/{id}', [categoriesController::class, 'Etablissements']);


//Routes concernanats les sous Categories 

Route::get('sousCategorieEts/{id}', [sous_categoriesController::class, 'Etablissements']);
Route::get('sousCategorie/{id}', [sous_categoriesController::class, 'sousCategorie']);
Route::get('sousCategories', [sous_categoriesController::class, 'sousCategories']);
Route::get('sousCategorieCategorie/{id}', [sous_categoriesController::class, 'Categories']);


//Routes concernanats les pays

Route::get('pays/{id}', [paysController::class, 'getPays']);
Route::get('pays', [paysController::class, 'Pays']);


//Routes concernanats les departements

Route::get('departement/{id}', [departementsController::class, 'getDepartement']);
Route::get('departements', [departementsController::class, 'Departements']);

//Routes concernanats les villes

Route::get('villes', [villesController::class, 'Villes']);
Route::get('villeEts/{id}', [villesController::class, 'Etablissements']);
Route::get('ville/{id}', [villesController::class, 'getVille']);


//Routes concernanat les arrondissements

Route::get('arrondissements', [arrondissementsController::class, 'Arrondissements']);
Route::get('arrondissementEts/{id}', [arrondissementsController::class, 'Etablissements']);
Route::get('arrondissement/{id}', [arrondissementsController::class, 'getArrondissement']);


//Routes concernanat les etablissements

Route::get('etablissements', [etablissementsController::class, 'Etablissements']);
Route::get('etablissement/{id}', [etablissementsController::class, 'Etablissement']);
Route::get('etablissementRecherche/{valeur}', [etablissementsController::class, 'rechercheEtablissement']);
Route::get('etablissementNote/{id}', [etablissementsController::class, 'Notes']);
Route::get('etablissementSousCategories/{id}', [etablissementsController::class, 'sousCategories']);
// Route::get('etablissementCategories/{id}', [etablissementsController::class, 'Categories']);
Route::get('etablissementVilles/{id}', [etablissementsController::class, 'Villes']);
Route::get('etablissementCategories', [etablissementsController::class, 'CategoriesAp']);
Route::get('etablissementAnnonces/{id}', [etablissementsController::class, 'Annonces']);



// Routes concernant les calendriers

Route::get('calendriers', [calendriersController::class, 'Calendriers']);
Route::get('calendrier/{id}', [calendriersController::class, 'getCalendriers']);
Route::get('calendrierAnnonce/{id}', [calendriersController::class, 'Annonces']);



// Routes concernant les annonces

Route::get('annonces', [annoncesController::class, 'Annonces']);
Route::get('annonceRecherche/{valeur}', [annoncesController::class, 'rechercheAnnonce']);
Route::get('annonce/{id}', [annoncesController::class, 'getAnnonce']);
Route::get('annonceUtilisateur/{id}', [annoncesController::class, 'Utilisateur']);
Route::get('annonceCalendrier/{id}', [annoncesController::class, 'Calendrier']);
Route::get('annonceImage/{id}', [annoncesController::class, 'imageAnnonce']);
Route::get('publierAnnonce/{id}', [annoncesController::class, 'publier']);
Route::get('annoncesEts/{id}', [annoncesController::class, 'Etablissements']);



// Les routes concernant les commentaires

Route::get('commentaireUtilisateur/{id}', [commentairesController::class, 'Utilisateur']);
Route::get('commentaires', [commentairesController::class, 'Commentaires']);
Route::get('commentaire/{id}', [commentairesController::class, 'getCommentaire']);



// Routes concernant annonce_image 

Route::get('imageAnnonces', [annonce_imagesController::class, 'AnnonceImage']);
Route::get('imageAnnonce/{id}', [annonce_imagesController::class, 'getAnnonceImage']);
Route::get('imageAnnonceA/{id}', [annonce_imagesController::class, 'Annonce']);



// Routes concernant les notes

Route::get('notes', [notesController::class, 'Notes']);
Route::get('note/{id}', [notesController::class, 'getNote']);
Route::get('noteUtilisateur/{id}', [notesController::class, 'Utilisateur']);
Route::get('noteEts/{id}', [notesController::class, 'Etablissements']);




// Secure routes

Route::group(['middleware'=>['auth:sanctum']], function() {

    // Pour se deconnecter

    Route::post("logOut", [authentificationsController::class, 'logOut']);


    //Routes concernanats les comptes 

    Route::get('utilisateurs', [utilisateursController::class, 'Utilisateurs']);
    Route::put('utilisateurAdd/{id}', [utilisateursController::class, 'addUtilisateur']);
    Route::get('utilisateur/{id}', [utilisateursController::class, 'getUtilisateur']);
    Route::put('utilisateur/{id}', [utilisateursController::class, 'putUtilisateur']);
    // Route::delete('utilisateur/{id}', [utilisateursController::class, 'deleteUtilisateur']);
    Route::get("utilisateurRecherche/{valeur}", [utilisateursController::class, 'rechercheUtilisateur']);
    Route::get("utilisateurEts/{id}", [utilisateursController::class, 'Etablissements']);
    Route::put("utilisateurImg/{id}", [utilisateursController::class, 'putImage']);
    Route::put("utilisateurPwd/{id}", [utilisateursController::class, 'putPassword']);
    Route::post("utilisateurImg", [utilisateursController::class, 'imageUtilisateur']);
    Route::get("utilisateurAnnonce/{id}", [utilisateursController::class, 'Annonces']);
    Route::get("utilisateurCommentaire/{id}", [utilisateursController::class, 'Commentaires']);
    Route::get("utilisateurNote/{id}", [utilisateursController::class, 'Notes']);



    //Routes concernanats les Categories 

    // Route::get('categories', [categoriesController::class, 'Categories']);
    // Route::get('categorieSousCategorie/{id}', [categoriesController::class, 'Souscategories']);
    // Route::get('categorie/{id}', [categoriesController::class, 'Categorie']);
    // Route::get('categorieEts/{id}', [categoriesController::class, 'Etablissements']);

    Route::post('categorie', [categoriesController::class, 'createCategorie']);
    Route::put('categorie/{id}', [categoriesController::class, 'putCategorie']);



    //Routes concernanats les sous Categories 

    // Route::get('sousCategorieEts/{id}', [sous_categoriesController::class, 'Etablissements']);
    // Route::get('sousCategorie/{id}', [sous_categoriesController::class, 'sousCategorie']);
    // Route::get('sousCategories', [sous_categoriesController::class, 'sousCategories']);
    // Route::get('sousCategorieCategorie/{id}', [sous_categoriesController::class, 'Categories']);
    Route::post('sousCategorie', [sous_categoriesController::class, 'createSousCategorie']);
    Route::put('sousCategorie/{id}', [sous_categoriesController::class, 'putSousCategorie']);
    



    //Routes concernanats les pays

    // Route::get('pays/{id}', [paysController::class, 'getPays']);
    // Route::get('pays', [paysController::class, 'Pays']);
    Route::post('pays', [paysController::class, 'createPays']);
    Route::put('pays/{id}', [paysController::class, 'putPays']);



    //Routes concernanats les departements

    // Route::get('departement/{id}', [departementsController::class, 'getDepartement']);
    // Route::get('departements', [departementsController::class, 'Departements']);
    Route::post('departement', [departementsController::class, 'createDepartement']);
    Route::put('departement/{id}', [departementsController::class, 'putDepartement']);
    // Route::get('departementEts/{id}', [departementsController::class, 'Etablissements']);



    //Routes concernanats les villes

    // Route::get('villes', [villesController::class, 'Villes']);
    // Route::get('villeEts/{id}', [villesController::class, 'Etablissements']);
    // Route::get('ville/{id}', [villesController::class, 'getVille']);
    Route::post('ville', [villesController::class, 'createVille']);
    Route::put('ville/{id}', [villesController::class, 'putVille']);



    //Routes concernanat les arrondissements

    // Route::get('arrondissements', [arrondissementsController::class, 'Arrondissements']);
    // Route::get('arrondissementEts/{id}', [arrondissementsController::class, 'Etablissements']);
    // Route::get('arrondissement/{id}', [arrondissementsController::class, 'getArrondissement']);
    Route::post('arrondissement', [arrondissementsController::class, 'createArrondissement']);
    Route::put('arrondissement/{id}', [arrondissementsController::class, 'putArrondissement']);



    //Routes concernanat les etablissements

    // Route::get('etablissements', [etablissementsController::class, 'Etablissements']);
    // Route::get('etablissement/{id}', [etablissementsController::class, 'Etablissement']);
    // Route::get('etablissementRecherche/{valeur}', [etablissementsController::class, 'rechercheEtablissement']);
    // Route::get('etablissementAnnonces/{valeur}', [etablissementsController::class, 'Annonces']);
    // Route::get('etablissementNote/{id}', [etablissementsController::class, 'Notes']);
    // Route::get('etablissementSousCategories/{id}', [etablissementsController::class, 'sousCategories']);
    // Route::get('etablissementCategories/{id}', [etablissementsController::class, 'Categories']);
    Route::get('etablissementVilles/{id}', [etablissementsController::class, 'Villes']);
    Route::get('etablissementCategories', [etablissementsController::class, 'CategoriesAp']);
    Route::post('etablissement', [etablissementsController::class, 'createEtablissement']);
    Route::put('etablissement/{id}', [etablissementsController::class, 'putEtablissement']);



    // Routes concernant les calendriers

    // Route::get('calendriers', [calendriersController::class, 'Calendriers']);
    // Route::get('calendrier/{id}', [calendriersController::class, 'getCalendriers']);
    // Route::get('calendrierAnnonce/{id}', [calendriersController::class, 'Annonces']);
    Route::post('calendrier', [calendriersController::class, 'createCalendrier']);
    Route::put('calendrier/{id}', [calendriersController::class, 'putCalendrier']);



    // Routes concernant les annonces

    // Route::get('annonces', [annoncesController::class, 'Annonces']);
    // Route::get('annonceRecherche/{valeur}', [annoncesController::class, 'rechercheAnnonce']);
    // Route::get('annonce/{id}', [annoncesController::class, 'getAnnonce']);
    // Route::get('annonceUtilisateur/{id}', [annoncesController::class, 'Utilisateur']);
    // Route::get('annonceCalendrier/{id}', [annoncesController::class, 'Calendrier']);
    // Route::get('annonceImage/{id}', [annoncesController::class, 'imageAnnonce']);
    Route::post('annonce', [annoncesController::class, 'createAnnonce']);
    Route::put('annonce/{id}', [annoncesController::class, 'putAnnonce']);
    Route::get('annoncesPublie', [annoncesController::class, 'AnnoncesPublier']);



    // Les routes concernant les commentaires

    // Route::get('commentaireUtilisateur/{id}', [commentairesController::class, 'Utilisateur']);
    // Route::get('commentaires', [commentairesController::class, 'Commentaires']);
    // Route::get('commentaire/{id}', [commentairesController::class, 'getCommentaire']);
    Route::post('commentaire', [commentairesController::class, 'createCommentaire']);
    Route::put('commentaire/{id}', [commentairesController::class, 'putCommentaire']);



    // Routes concernant annonce_image 

    // Route::get('imageAnnonces', [annonce_imagesController::class, 'AnnonceImage']);
    // Route::get('imageAnnonce/{id}', [annonce_imagesController::class, 'getAnnonceImage']);
    // Route::get('imageAnnonceA/{id}', [annonce_imagesController::class, 'Annonce']);
    Route::post('imageAnnonce', [annonce_imagesController::class, 'createImageAnnonce']);
    Route::put('imageAnnonce/{id}', [annonce_imagesController::class, 'putImageAnnonce']);
    


    // Routes concernant les notes

    Route::get('notes', [notesController::class, 'Notes']);
    Route::get('note/{id}', [notesController::class, 'getNote']);
    Route::get('noteUtilisateur/{id}', [notesController::class, 'Utilisateur']);
    Route::get('noteEts/{id}', [notesController::class, 'Etablissements']);
    Route::post('note', [notesController::class, 'createNote']);
    Route::put('note/{id}', [notesController::class, 'putNote']);


});
