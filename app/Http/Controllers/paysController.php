<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pays;
use Illuminate\Support\Facades\Validator;

class paysController extends Controller
{
    //
    public function Pays(){

        $pays = pays::all();

        if ($pays) {

            return 'message: succes 200' . $pays;

        }else {

            return 'Erreur 004 : Pas d\'enregistrements, la table est vide';
        }
    }

    public function getPays($id){

        $pays = pays::find($id);

        if ($pays) {

            return 'message: succes 200' . $pays;

        }else {

            return 'Erreur 004 : Aucune information ne correspond à votre demande';
        }

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

            // return pays::create($request->all());

            $pays = pays::create($request->all());

            if ($pays) {

                return 'message : succes 200 ' . $pays;

            }else {

                return 'message : echec 005, une erreur c\'est produite lors de l\'enregistrement ';

            }

            
        }

    }

    public function putPays(Request $request, $id){

        $pays = pays::findOrFail($id);

        if($pays){

            $validator = Validator::make($request->all(), [
            
                'libelle' => 'required|unique:pays|max:250|regex:/[^0-9.-]/',
            ]);
    
            if ($validator->fails()) {
    
                return response()->json($validator->errors(), 201);
                return $validator->errors();
    
                // return $erreur = "Erreur : 001, lie au champs de saisie";
    
            }else {
    
               $modif = $pays->update($request->all());

               if ($modif) {

                return 'message : succes 200' . $pays;

               }else {
                   
                    return 'message : echec 005, une erreur c\'est produite lors de l\'enregistrement ';

               }
                
            }

        }else {

            return 'Erreur 004: Identifiant n\'existe pas';

        }

        

    }

}
