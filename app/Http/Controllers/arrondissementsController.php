<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\arrondissements;

class arrondissementsController extends Controller
{
    //
    public function Arrondissements(){

        $arrondissement = arrondissements::all();

        if ($arrondissement) {
            
            return 'Message : succes 200';
            return $arrondissement;

        }else {
            
            return 'Erreur 004 : Aucune information n\'existe, la table est vide';
        }

    }


    public function getArrondissement($id){

        $arrondissement = arrondissements::find($id);

        if ($arrondissement) {
            
            return 'Message : succes 200';
            return $arrondissement;
        }else {
            
            return 'Message : l\'identifiant n\'existe pas';
        }
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

            $arrondissement = arrondissements::create($request->all());

            if ($arrondissement) {
                
                return 'Message : succes 200';
                return $arrondissement ;

            }else {
                
                return 'Erreur 005 : echec ';
            }
            
        }

    }

    public function putArrondissement(Request $request, $id){

        $arrondissement = arrondissements::findOrFail($id);

        if ($arrondissement) {
            
            $validator = Validator::make($request->all(), [
            
                'libelle' => 'required|unique:pays|max:250|regex:/[^0-9.-]/',
                'villes_id' => 'required'
            ]);
    
            if ($validator->fails()) {
    
                return response()->json($validator->errors(), 201);
                return $validator->errors();
    
                // return $erreur = "Erreur : 001, lie au champs de saisie";
    
            }else {
                
                $modif = $arrondissement->update($request->all());

                if ($modif) {
                    
                    return 'Message : succes 200';
                    return $modif;

                }else {
                    
                    return 'Erreur 005 : Echec';
                }
                
            }

        }else {
            
            return 'Erreur 004 : l\'identifiant n\'existe pas ';
            
        }

    }
}
