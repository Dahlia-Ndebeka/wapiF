<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\villes;
use Illuminate\Support\Facades\Validator;


class villesController extends Controller
{

    //Afficher les villes

    public function Villes(){

        $villes = villes::all();

        if ($villes) {

            return response([
                'message' => 'success',
                'data' => $villes
            ], 200);

        }else {
    
            return response([
                'message' => 'Erreur 004 : Pas d\'enregistrements, la table est vide',
                'data' => 'Nul'
            ], 201);

            
        }
    }


    // Consulter ou afficher une ville

    public function getVille($id){

        $ville = villes::find($id);

        if ($ville) {
            
            return response([
                'message' => 'success',
                'data' => $ville
            ], 200);
            
        }else {
            
            return response([
                'message' => 'Erreur 004, Aucune information ne correspond à cet identifiant',
                'data' => 'Nul'
            ], 201);

        }

    }


    // Creer une ville

    public function createVille(Request $request){

        $validator = Validator::make($request->all(), [
            
            'libelle' => 'required|unique:pays|max:250|regex:/[^0-9.-]/',
            'departements_id' => 'required'
        ]);

        if ($validator->fails()) {

            $erreur = $validator->errors();

            return response([
                'code' => '001',
                'message' => $erreur,
                'info' => 'erreur lie au champs de saisie' 
            ], 202);


        }else {

            $ville = villes::create($request->all());

            if ($ville) {
                
                return response([
                    'message' => 'success',
                    'data' => $ville
                ], 200);

            }else {
                
                return response([
                    'message' => 'Erreur 005 : echec, lors de l\'ajout',
                    'data' => 'Nul'
                ], 201);

            }
            
        }

    }


    // Modifier une ville

    public function putVille(Request $request, $id){

        $ville = villes::findOrFail($id);

        if ($ville) {
            
            $validator = Validator::make($request->all(), [
            
                'libelle' => 'required|unique:pays|max:250|regex:/[^0-9.-]/',
                'departements_id' => 'required'
            ]);
    
            if ($validator->fails()) {

                $erreur = $validator->errors();
    
                return response([
                    'code' => '001',
                    'message' => $erreur,
                    'info' => 'erreur lie au champs de saisie' 
                ], 202);

            }else {
                
                $modif = $ville->update($request->all());

                if ($modif) {

                    return response([
                        'message' => 'success',
                        'data' => $ville
                    ], 200);

                }else {
                    
                    return response([
                        'message' => 'Erreur 005 : Echec lors de la modification',
                        'data' => 'Nul'
                    ], 201);

                }
                
            }

        }else {

            return response([
                'message' => 'Erreur 004 : l\identifiant n\existe pas',
                'data' => 'Nul'
            ], 201);

        }

    }


}
