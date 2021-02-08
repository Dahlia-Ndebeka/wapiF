<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\departements;
use Illuminate\Support\Facades\Validator;


class departementsController extends Controller
{
    //
    public function Departements(){

        $departements = departements::all();

        if($departements){

            return $departements;

        }else {

            return 'Erreur 004 : Pas d\'enregistrements, la table est vide';
        }
    }

    public function getDepartement($id){

        $departements = departements::find($id);

        if($departements){

            return $departements;

        }else {
            return 'Erreur 004 : Aucune information ne correspond à votre demande';
        }
       
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

            $departement = departements::create($request->all());

            if ($departement) {
                
                return 'message : succes 200' . $departement;
            }else {
                
                return 'Erreur 005 : echec';

            }
            
        }

    }

    public function putDepartement(Request $request, $id){

        $departement = departements::findOrFail($id);

        if ($departement) {
            
            $validator = Validator::make($request->all(), [
            
                'libelle' => 'required|unique:pays|max:250|regex:/[^0-9.-]/',
                'pays_id' => 'required'
            ]);
    
            if ($validator->fails()) {
    
                return response()->json($validator->errors(), 201);
                return $validator->errors();
    
                // return $erreur = "Erreur : 001, lie au champs de saisie";
    
            }else {
                
                $modif = $departement->update($request->all());

                if ($modif) {

                    return 'Message : succes 200' . $modif;

                // return 'Message : 200' . $departement;
                }else {
                    
                    return 'Erreur 005 : une erreur c\'est produite lors de la mofication ';
                }
                
            }
        }else {
            
            return 'Erreur 004 : l\'information n\'existe pas';

        }

        

    }
}
