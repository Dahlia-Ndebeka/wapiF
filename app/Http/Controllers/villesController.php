<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\villes;
use Illuminate\Support\Facades\Validator;


class villesController extends Controller
{
    //
    public function Villes(){

        $villes = villes::all();

        if ($villes) {
            
            return 'Message : succes 200 ' . $villes;
        }else {
            
            return 'Erreur 004 : Pas d\'enregistrements, la table est vide';
            
        }
    }


    public function getVille($id){

        $ville = villes::find($id);

        if ($ville) {
            
            return 'Message : succes 200 ' . $ville ;
            
        }else {
            
            return 'Erreur 004, Aucune information ne correspond à cet identifiant';

        }
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

            $ville = villes::create($request->all());

            if ($ville) {
                
                return 'Message :succes 200' . $ville;
            }else {
                
                return 'Erreur 005 : echec';

            }
            
        }

    }


    public function putVille(Request $request, $id){

        $ville = villes::findOrFail($id);

        if ($ville) {
            
            $validator = Validator::make($request->all(), [
            
                'libelle' => 'required|unique:pays|max:250|regex:/[^0-9.-]/',
                'departements_id' => 'required'
            ]);
    
            if ($validator->fails()) {
    
                return response()->json($validator->errors(), 201);
                return $validator->errors();
    
                // return $erreur = "Erreur : 001, lie au champs de saisie";
    
            }else {
                
                $modif = $ville->update($request->all());

                if ($modif) {

                    return 'Message : succes 200';
                    return $ville;

                }else {
                    
                    return 'Erreur 005 : Echec';
                }
                
            }

        }else {
            
            return 'Erreur 004 : l\identifiant n\existe pas';
        }

    }

}
