<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\pays;
use Illuminate\Support\Facades\Validator;

class paysController extends Controller
{

    //Afficher tous les pays

    public function Pays(){

        $pays = pays::all();

        if ($pays) {

            return response([
                'message' => 'success',
                'data' => $pays
            ], 200);

        }else {

            return response([
                'message' => 'Erreur 004 : Pas d\'enregistrements, la table est vide',
                'data' => 'Nul'
            ], 201);

        }

    }


    // Consulter ou afficher un pays

    public function getPays($id){

        $pays = pays::find($id);

        if ($pays) {

            return response([
                'message' => 'success',
                'data' => $pays
            ], 200);

        }else {

            return response([
                'message' => 'Erreur 004 : Aucune information ne correspond à votre demande',
                'data' => 'Nul'
            ], 201);

        }

    }


    // Creer un pays

    public function createPays(Request $request){

        $validator = Validator::make($request->all(), [
            
            'libelle' => 'required|unique:pays|max:250|regex:/[^0-9.-]/',
        ]);

        if ($validator->fails()) {

            $erreur = $validator->errors();

            return response([
                'message' => 'success',
                'data' => $erreur
            ], 200);

        }else {

            $pays = pays::create($request->all());

            if ($pays) {

                return response([
                    'message' => 'success',
                    'data' => $pays
                ], 200);

            }else {

                return response([
                    'message' => 'message : echec 005, une erreur c\'est produite lors de l\'enregistrement',
                    'data' => 'Nul'
                ], 201);

            }

            
        }

    }


    // Modifier un pays

    public function putPays(Request $request, $id){

        $pays = pays::findOrFail($id);

        if($pays){

            $validator = Validator::make($request->all(), [
            
                'libelle' => 'required|unique:pays|max:250|regex:/[^0-9.-]/',
            ]);
    
            if ($validator->fails()) {

                $erreur = $validator->errors();

                return response([
                        'message' => 'success',
                        'data' => $erreur
                    ], 200);

    
            }else {
    
               $modif = $pays->update($request->all());

               if ($modif) {

                    return response([
                        'message' => 'success',
                        'data' => $pays
                    ], 200);

               }else {

                    return response([
                        'message' => 'message : echec 005, une erreur c\'est produite lors de l\'enregistrement',
                        'data' => 'Nul'
                    ], 201);

                }
                
            }

        }else {

            return response([
                'message' => 'Erreur 004: Identifiant n\'existe pas',
                'data' => 'Nul'
            ], 201);

        }

    }



}
