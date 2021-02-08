<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\arrondissements;

class arrondissementsController extends Controller
{
    //
    public function Arrondissements(){

        return arrondissements::all();
    }

    public function getArrondissement($id){

        return arrondissements::find($id);
    }

    public function createArrondissement(Request $request){

        $validator = Validator::make($request->all(), [
            
            'libelle' => 'required|unique:pays|max:250|regex:/[^0-9.-]/',
            'villes_id' => 'required'
        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors(), 201);
            return $validator->errors();

            // return $erreur = "Erreur : 001, lie au champs de saisie";

        }else {

            return arrondissements::create($request->all());
            
        }

    }

    public function putArrondissement(Request $request, $id){

        $arrondissement = arrondissements::findOrFail($id);

        $validator = Validator::make($request->all(), [
            
            'libelle' => 'required|unique:pays|max:250|regex:/[^0-9.-]/',
            'villes_id' => 'required'
        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors(), 201);
            return $validator->errors();

            // return $erreur = "Erreur : 001, lie au champs de saisie";

        }else {
            
            $arrondissement->update($request->all());
            return $arrondissement;
            
        }

    }
}
