<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\departements;
use Illuminate\Support\Facades\Validator;


class departementsController extends Controller
{
    //
    public function Departements(){

        return departements::all();
    }

    public function getDepartement($id){

        return departements::find($id);
    }

    public function createDepartement(Request $request){

        $validator = Validator::make($request->all(), [
            
            'libelle' => 'required|unique:pays|max:250|regex:/[^0-9.-]/',
            'pays_id' => 'required'
        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors(), 201);
            return $validator->errors();

            // return $erreur = "Erreur : 001, lie au champs de saisie";

        }else {

            return departements::create($request->all());
            
        }

    }

    public function putDepartement(Request $request, $id){

        $departement = departements::findOrFail($id);

        $validator = Validator::make($request->all(), [
            
            'libelle' => 'required|unique:pays|max:250|regex:/[^0-9.-]/',
            'pays_id' => 'required'
        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors(), 201);
            return $validator->errors();

            // return $erreur = "Erreur : 001, lie au champs de saisie";

        }else {
            
            $departement->update($request->all());
            return $departement;
            
        }

    }
}
