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
                'code' => '200',
                'message' => 'success',
                'data' => $villes
            ], 200);

        }else {
    
            return response([
                'code' => '004',
                'message' => 'Table est vide',
                'data' => 'null'
            ], 201);

            
        }
    }


    // Consulter ou afficher une ville

    public function getVille($id){

        $ville = villes::find($id);

        if ($ville) {
            
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $ville
            ], 200);
            
        }else {
            
            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
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
                'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                'data' => $erreur
            ], 201);


        }else {

            $ville = villes::create($request->all());

            if ($ville) {
                
                return response([
                    'code' => '200',
                    'message' => 'success',
                    'data' => $ville
                ], 200);

            }else {
                
                return response([
                    'code' => '005',
                    'message' => 'Echec, lors de l\'operation',
                    'data' => 'null'
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
                    'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                    'data' => $erreur
                ], 201);

            }else {
                
                $modif = $ville->update($request->all());

                if ($modif) {

                    return response([
                        'code' => '200',
                        'message' => 'success',
                        'data' => $ville
                    ], 200);

                }else {
                    
                    return response([
                        'code' => '005',
                        'message' => 'Echec lors de l\'operation',
                        'data' => 'null'
                    ], 201);

                }
                
            }

        }else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
            ], 201);

        }

    }


    // // Affichage des etablissements a partir de la ville

    // public function Etablissements($id){

    //     $etablissements = villes::find($id)->Etablissements;

    //     if ($etablissements) {
            
    //         return response([
    //             'message' => 'success',
    //             'data' => $etablissements
    //         ], 200);

    //     } else {

    //         return response([
    //             'code' => '004',
    //             'message' => 'Identifiant incorrect',
    //             'data' => 'null'
    //         ], 201);

    //     }
        
    // }


}
