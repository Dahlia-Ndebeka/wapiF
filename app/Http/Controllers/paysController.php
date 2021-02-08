<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pays;
use Illuminate\Support\Facades\Validator;

class paysController extends Controller
{
    //
    public function Pays(){

        return pays::all();
    }

    public function getPays($id){

        return pays::find($id);
    }

    public function createPays(Request $request){

        $validator = Validator::make($request->all(), [
            
            'libelle' => 'required|unique:pays|max:250|regex:/[^0-9.-]/',
        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors(), 201);
            return $validator->errors();

            // return $erreur = "Erreur : 001, lie au champs de saisie";

        }else {

            return pays::create($request->all());
            
        }

    }

    public function putPays(Request $request, $id){

        $pays = pays::findOrFail($id);

        $validator = Validator::make($request->all(), [
            
            'libelle' => 'required|unique:pays|max:250|regex:/[^0-9.-]/',
        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors(), 201);
            return $validator->errors();

            // return $erreur = "Erreur : 001, lie au champs de saisie";

        }else {
            
            $pays->update($request->all());
            return $pays;
            
        }

    }

}
