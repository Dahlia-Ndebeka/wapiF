<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\models\souscategories;
use Illuminate\Support\Facades\Validator;


class souscategoriesController extends Controller
{
    //
    public function sousCategories(){

        return souscategories::all();
    }

    public function sousCategorie($id){

        return $souscategories = souscategories::find($id);
    }


    public function createSousCategorie(Request $request){

        $validator = Validator::make($request->all(), [
            
            'nomSousCategorie' => 'required|unique:souscategories|max:100|regex:/[^0-9.-]/',
            'categories_id' => 'required',
        ]);

        if ($validator->fails()) {

            // return response()->json($validator->errors(), 201);
            // return $validator->errors();

            return $erreur = "Erreur : 001, lie au champs de saisie";

        }else {

            return souscategories::create($request->all());
            
        }
    }


    public function putSousCategorie(Request $request, $id)
    {
        //
        $souscategorie = souscategories::findOrFail($id);

        $validator = Validator::make($request->all(), [
            
            'nomSousCategorie' => 'required|unique:souscategories|max:100|regex:/[^0-9.-]/',
            'categories_id' => 'required',
        ]);

        if ($validator->fails()) {

            // return response()->json($validator->errors(), 201);
            // return $validator->errors();

            return $erreur = "Erreur : 001, lie au champs de saisie";
            
        }else {

             $souscategorie->update($request->all());
             return $souscategorie;
        }
    }
}
