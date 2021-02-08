<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\villes;
use Illuminate\Support\Facades\Validator;


class villesController extends Controller
{
    //
    public function Villes(){

        return villes::all();
    }

    public function getVille($id){

        return villes::find($id);
    }

    public function createVille(Request $request){

        $validator = Validator::make($request->all(), [
            
            'libelle' => 'required|unique:pays|max:250|regex:/[^0-9.-]/',
            'departements_id' => 'required'
        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors(), 201);
            return $validator->errors();

            // return $erreur = "Erreur : 001, lie au champs de saisie";

        }else {

            return villes::create($request->all());
            
        }

    }

    public function putVille(Request $request, $id){

        $ville = villes::findOrFail($id);

        $validator = Validator::make($request->all(), [
            
            'libelle' => 'required|unique:pays|max:250|regex:/[^0-9.-]/',
            'departements_id' => 'required'
        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors(), 201);
            return $validator->errors();

            // return $erreur = "Erreur : 001, lie au champs de saisie";

        }else {
            
            $ville->update($request->all());
            return $ville;
            
        }

    }
}
