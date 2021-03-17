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
                'code' => '004',
                'message' => 'Table vide',
                'data' => $erreur
            ], 201);

        }

    }


    // Consulter ou afficher un pays

    public function getPays($id){

        $pays = pays::find($id);

        if ($pays) {

            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $pays
            ], 200);

        }else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
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
                'code' => '001',
                'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                'data' => $erreur
            ], 201);

        }else {

            $pays = pays::create($request->all());

            if ($pays) {

                return response([
                    'code' => '200',
                    'message' => 'success',
                    'data' => $pays
                ], 200);

            }else {

                return response([
                    'code' => '005',
                    'message' => 'Echec lors de l\'operation',
                    'data' => 'null'
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
                    'code' => '001',
                    'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                    'data' => $erreur
                ], 201);

    
            }else {
    
               $modif = $pays->update($request->all());

               if ($modif) {

                    return response([
                        'code' => '200',
                        'message' => 'success',
                        'data' => $pays
                    ], 200);

               }else {

                    return response([
                        'message' => '005',
                        'message' => 'echec lors de l\'operation',
                        'data' => 'null'
                    ], 201);

                }
                
            }

        }else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'Nul'
            ], 201);

        }

    }



    // Supprimer un pays
     
    public function deletePays($id){

        $delete = pays::findOrFail($id)->delete();

        if ($delete) {

            return response([
                'code' => '200',
                'message' => 'Suppression effectuée avec succes',
                'data' => null
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'L\'identifiant incorrect',
                'data' => null
            ], 201);

        }
        
    }

}
