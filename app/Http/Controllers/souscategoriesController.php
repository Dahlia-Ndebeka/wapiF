<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\souscategories;
use Illuminate\Support\Facades\Validator;


class souscategoriesController extends Controller
{

    //Afficher tous les sous categories

    public function sousCategories(){

        $souscategories = souscategories::all();

        if ($souscategories) {
            
            return response([
                'message' => 'success',
                'data' => $souscategories
            ], 200);

        } else {

            return response([
                'message' => 'Ereur 004 : Table vide',
                'data' => 'Nul'
            ], 201);

        }
        
    }


    // Consulter ou afficher une sous categorie

    public function sousCategorie($id){

        $souscategories = souscategories::find($id);

        if ($souscategories) {
            
        return response([
            'message' => 'success',
            'data' => $souscategories
        ], 200);

        } else {
            
        return response([
            'message' => 'Ereur 004 : indentifiant n\'existe pas',
            'data' => 'Nul'
        ], 201);
        
        }
         
    }


    // Creation de la sous categorie

    public function createSousCategorie(Request $request){

        $validator = Validator::make($request->all(), [
            
            'nomSousCategorie' => 'required|unique:souscategories|max:100|regex:/[^0-9.-]/',
            'categories_id' => 'required',
        ]);

        if ($validator->fails()) {
            
            $erreur = $validator->errors();

            return response([
                'code' => '001',
                'message' => $erreur,
                'info' => 'erreur lie au champs de saisie' 
            ], 202);

        }else {

            $data = souscategories::create($request->all());

            if ($data) {
                
                return response([
                    'message' => 'success',
                    'data' => $data
                ], 200);

            } else {
                
                return response([
                    'message' => 'Erreur 005 : la modification a echoue',
                    'data' => 'Nul'
                ], 201);
            }
            
            
        }
    }


    // Modification de la sous categorie

    public function putSousCategorie(Request $request, $id)
    {
        //
        $souscategorie = souscategories::findOrFail($id);

        $validator = Validator::make($request->all(), [
            
            'nomSousCategorie' => 'required|unique:souscategories|max:100|regex:/[^0-9.-]/',
            'categories_id' => 'required',
        ]);

        if ($validator->fails()) {

            $erreur = $validator->errors();

            return response([
                'code' => '001',
                'message' => $erreur,
                'info' => 'erreur lie au champs de saisie' 
            ], 202);
            
        }else {

            $datas = $souscategorie->update($request->all());

            if ($datas) {
                
                return response([
                    'message' => 'success',
                    'data' => $souscategorie
                ], 200);

            } else {
            
                return response([
                    'message' => 'Erreur 005 : echec lors de la modification',
                    'data' => 'Nul'
                ], 201);
            }
             
        }
    }

    // Affichage des etablissements par rapport a la sous categorie

    public function Etablissements($id){

        $etablissements = souscategories::find($id)->Etablissements;

        if ($etablissements) {
            
            return response([
                'message' => 'success',
                'data' => $etablissements
            ], 200);

        } else {
            
            return response([
                'message' => 'Echec, aucun etablissement existe pour cette sous categorie',
                'data' => 'Nul'
            ], 201);
        }
        

    }



}
