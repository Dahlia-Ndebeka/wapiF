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
                'code' => '200',
                'message' => 'success',
                'data' => $souscategories
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

        $souscategories = souscategories::find($id);

        if ($souscategories) {
            
        return response([
            'code' => '200',
            'message' => 'success',
            'data' => $souscategories
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
            
            'nomSousCategorie' => 'required|unique:souscategories|max:100|regex:/[^0-9.-]/',
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

            $data = souscategories::create($request->all());

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
        $souscategorie = souscategories::findOrFail($id);

        $validator = Validator::make($request->all(), [
            
            'nomSousCategorie' => 'required|unique:souscategories|max:100|regex:/[^0-9.-]/',
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

    // Affichage des etablissements par rapport a la sous categorie

    public function Etablissements($id){

        $etablissements = souscategories::find($id)->Etablissements;

        if ($etablissements) {
            
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $etablissements
            ], 200);

        } else {
            
            return response([
                'code' => '004',
                'message' => 'Echec, aucun etablissement existe pour cette sous categorie',
                'data' => 'null'
            ], 201);
        }
        

    }



}
