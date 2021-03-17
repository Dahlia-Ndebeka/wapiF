<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\sous_categories;

class sous_categoriesController extends Controller
{
    
    //Afficher tous les sous categories

    public function sous_categories(){

        $sous_categories = sous_categories::all();

        if ($sous_categories) {
            
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $sous_categories
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Table vide',
                'data' => 'null'
            ], 201);

        }
        
    }


    //Afficher tous les sous categories et ses categories

    public function sous_categoriesCat(){

        $sous_categories = sous_categories::join('categories', 'sous_categories.categories_id', '=' , 'categories.id')
                                            ->select('sous_categories.id',
                                            'sous_categories.nom_sous_categorie', 
                                            'categories.nomCategorie')->get();

        if ($sous_categories) {
            
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $sous_categories
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Table vide',
                'data' => 'null'
            ], 201);

        }
        
    }




    // Consulter ou afficher une sous categorie

    public function sousCategorie($id){

        $sous_categories = sous_categories::find($id);

        if ($sous_categories) {
            
        return response([
            'code' => '200',
            'message' => 'success',
            'data' => $sous_categories
        ], 200);

        } else {
            
        return response([
            'message' => '004',
            'message' => 'Indentifiant incorrect',
            'data' => 'null'
        ], 201);
        
        }
         
    }


    // Creation de la sous categorie

    public function createSousCategorie(Request $request){

        $validator = Validator::make($request->all(), [
            
            'nom_sous_categorie' => 'required|unique:sous_categories|max:100|regex:/[^0-9.-]/',
            'categories_id' => 'required',
        ]);

        if ($validator->fails()) {
            
            $erreur = $validator->errors();

            return response([
                'code' => '001',
                'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                'data' => $erreur,

            ], 202);

        }else {

            $data = sous_categories::create($request->all());

            if ($data) {
                
                return response([
                    'code' => '200',
                    'message' => 'success',
                    'data' => $data
                ], 200);

            } else {
                
                return response([
                    'code' => '005',
                    'message' => 'Erreur lors de l\'operation',
                    'data' => 'null'
                ], 201);
            }
            
            
        }
    }


    // Modification de la sous categorie

    public function putSousCategorie(Request $request, $id)
    {
        //
        $souscategorie = sous_categories::findOrFail($id);

        $validator = Validator::make($request->all(), [
            
            'nom_sous_categorie' => 'required|unique:sous_categories|max:100|regex:/[^0-9.-]/',
            'categories_id' => 'required',
        ]);

        if ($validator->fails()) {

            $erreur = $validator->errors();

            return response([
                'code' => '001',
                'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                'data' => $erreur, 
            ], 202);
            
        }else {

            $datas = $souscategorie->update($request->all());

            if ($datas) {
                
                return response([
                    'code' => '200',
                    'message' => 'success',
                    'data' => $souscategorie
                ], 200);

            } else {
            
                return response([
                    'code' => '005',
                    'message' => 'Echec lors de l\'operation',
                    'data' => 'null'
                ], 201);
            }
             
        }
    }
    


    // Affichage des categories a partir des sous categories

    public function Categories($id){

        $categories = sous_categories::find($id)->Categories;

        if ($categories) {
            
            return response([
                'message' => 'success',
                'data' => $categories
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
            ], 201);

        }
        
    }



    // Affichage des etablissements par rapport a la sous categorie

    // public function Etablissements($id){

    //     $etablissements = sous_categories::find($id);

    //     $ets = $etablissements->Etablissements;

    //     $etablissements = sous_categories::find($id)->Etablissements;

    //     if ($ets) {
            
    //         return response([
    //             'code' => '200',
    //             'message' => 'success',
    //             'data' => $ets
    //         ], 200);

    //     } else {
            
    //         return response([
    //             'code' => '004',
    //             'message' => 'Echec, aucun etablissement existe pour cette sous categorie',
    //             'data' => 'null'
    //         ], 201);
    //     }

    // }



    // Affichage des etablissements par rapport a la sous categorie

    public function Etablissements($id){

        // $cats = categories::find($id)->Etablissements;

        $cats = sous_categories::from('sous_categories')->where('sous_categories.id', '=', $id)
        ->join('etablissements_sous_categories', 'etablissements_sous_categories.sous_categories_id', '=', 'sous_categories.id')
        ->join('categories', function($join)
            {
                $join->on('categories.id', '=', 'sous_categories.categories_id');
            })
        ->join('etablissements', function($join)
            {
                $join->on('etablissements.id', '=', 'etablissements_sous_categories.etablissements_id');
            })
        ->join('utilisateurs', function($join)
            {
                $join->on('utilisateurs.id', '=', 'etablissements.utilisateurs_id');
            })
        ->join('arrondissements', 'etablissements.arrondissements_id', '=', 'arrondissements.id')
        ->join('villes', function($join)
            {
                $join->on('villes.id', '=', 'arrondissements.villes_id');
            })
        ->join('departements', function($join)
            {
                $join->on('departements.id', '=', 'villes.departements_id');
            })
        ->join('pays', function($join)
            {
                $join->on('pays.id', '=', 'departements.pays_id');
            })
        ->select('etablissements.id',
                    'etablissements.nom_etablissement',
                    'etablissements.adresse',
                    'etablissements.telephone',
                    'etablissements.description',
                    'etablissements.heure_ouverture',
                    'etablissements.heure_fermeture',
                    'etablissements.email',
                    'etablissements.boite_postale',
                    'etablissements.site_web',
                    'etablissements.logo',
                    'etablissements.latitude',
                    'etablissements.longitude',
                    'etablissements.utilisateurs_id',
                    'utilisateurs.login',
                    'utilisateurs.email',
                    'sous_categories.id',
                    'sous_categories.nom_sous_categorie',
                    'categories.id',
                    'categories.nomCategorie',
                    'categories.image',
                    'categories.titre',
                    'arrondissements.libelle_arrondissement', 
                    'villes.libelle_ville', 
                    'departements.libelle_departement',
                    'pays.libelle_pays')->get();

        if ($cats) {
            
            return response([
                'message' => 'success',
                'data' => $cats
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
            ], 201);

        }
        
    }




    // Affichage des annonces par rapport a la sous categorie

    public function Annonces($id){

        $cats = sous_categories::from('sous_categories')->where('sous_categories.id', '=', $id)
        ->join('annonces', 'sous_categories.id', '=', 'annonces.sous_categories_id')
        ->join('categories', function($join)
            {
                $join->on('categories.id', '=', 'sous_categories.categories_id');
            })
        ->join('utilisateurs', function($join)
            {
                $join->on('utilisateurs.id', '=', 'annonces.utilisateurs_id');
            })
        ->select(
                    'annonces.id',
                    'annonces.titre',
                    'annonces.description',
                    'annonces.date',
                    'annonces.type',
                    'annonces.image_couverture',
                    'annonces.lieu',
                    'annonces.latitude',
                    'annonces.longitude',
                    'annonces.utilisateurs_id',
                    'utilisateurs.login',
                    'utilisateurs.email',
                    'annonces.etablissement',
                    'annonces.nom_etablissement',
                    'annonces.etat',
                    'sous_categories.id',
                    'sous_categories.nom_sous_categorie',
                    'categories.id',
                    'categories.nomCategorie',
                    'categories.image',
                    'categories.titre',
                    
                    )->get();

        if ($cats) {
            
            return response([
                'message' => 'success',
                'data' => $cats
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
            ], 201);

        }
        
    }



    // Supprimer une sous categorie
     
    public function deleteSousCategorie($id){

        $delete = sous_categories::findOrFail($id)->delete();

        if ($delete) {

            return response([
                'code' => '200',
                'message' => 'Suppression effectuée avec succes',
                'data' => null
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'L\'identifiant incorrect',
                'data' => null
            ], 201);

        }
        
    }


}
