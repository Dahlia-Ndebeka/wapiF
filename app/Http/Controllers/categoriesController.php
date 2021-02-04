<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\categories;
use Illuminate\Support\Facades\Validator;

class categoriesController extends Controller
{
    //
    public function Categories(){

        return categories::all();
    }

    public function Categorie($id){

        return $Categorie = categories::find($id);
    }

    public function createCategorie(Request $request){

        $validator = Validator::make($request->all(), [
            
            'nomCategorie' => 'required|unique:categories|max:100|regex:/[^0-9.-]/',
        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors(), 201);
            return $validator->errors();

            // return $erreur = "Erreur : 001, lie au champs de saisie";

        }else {

            return categories::create($request->all());
            
        }
    }

    public function putCategorie(Request $request, $id)
    {
        //
        $categorie = categories::findOrFail($id);

        $validator = Validator::make($request->all(), [
            
            'nomCategorie' => 'required|unique:categories|max:100|regex:/[^0-9.-]/',
        ]);

        if ($validator->fails()) {

            // return response()->json($validator->errors(), 201);
            // return $validator->errors();

            return $erreur = "Erreur : 001, lie au champs de saisie";

        }else {

            $categorie->update($request->all());
            return $categorie;
            
        }
    }
}
