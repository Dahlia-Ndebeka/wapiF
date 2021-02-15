<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\arrondissements;

class arrondissementsController extends Controller
{
    
    //Afficher les arrondissements

    public function Arrondissements(){

        $arrondissement = arrondissements::all();

        if ($arrondissement) {

            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $arrondissement
            ], 200);

        }else {

            return response([
                'code' => '005',
                'message' => 'La table est vide',
                'data' => $arrondissement
            ], 201);

        }

    }


    // Consulter ou afficher un arrondissement

    public function getArrondissement($id){

        $arrondissement = arrondissements::find($id);

        if (!empty($arrondissement)) {
            
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $arrondissement
            ], 200);

        }else {
            
            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
            ], 201);

        }

    }


    // Creer un arrondissement

    public function createArrondissement(Request $request){

        $validator = Validator::make($request->all(), [
            
            'libelle' => 'required|unique:arrondissements|max:250|regex:/[^0-9.-]/',
            'villes_id' => 'required'
        ]);

        if ($validator->fails()) {

            $erreur = $validator->errors();
            
            return response([
                'code' => '001',
                'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                'data' => $erreur
            ], 201);

        }else {

            $arrondissement = arrondissements::create($request->all());

            if ($arrondissement) {
                
                return response([
                    'code' => '200',
                    'message' => 'success',
                    'data' => $arrondissement
                ], 200);

            }else {
                
                return response([
                    'code' => '005',
                    'message' => 'Echec lors de l\'opération',
                    'data' => 'null'
                ], 201);

            }
            
        }

    }


    // Modifier un arrondissement

    public function putArrondissement(Request $request, $id){

        $arrondissement = arrondissements::findOrFail($id);

        if ($arrondissement) {
            
            $validator = Validator::make($request->all(), [
            
                'libelle' => 'required|unique:pays|max:250|regex:/[^0-9.-]/',
                'villes_id' => 'required'
            ]);
    
            if ($validator->fails()) {
    
                $erreur = $validator->errors();
            
                return response([
                    'code' => '001',
                    'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                    'data' => $erreur
                ], 200);
    
            }else {
                
                $modif = $arrondissement->update($request->all());

                if ($modif) {

                    return response([
                        'code' => '200',
                        'message' => 'success',
                        'data' => $modif
                    ], 200);

                }else {

                    return response([
                        'code' => '005',
                        'message' => 'Erreur lors de l\'operation',
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


    // Affichage des etablissements a partir de l'arrondissement

    public function Etablissements($id){

        $etablissements = arrondissements::find($id)->Etablissements;

        if ($etablissements) {
            
            return response([
                'message' => 'success',
                'data' => $etablissements
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
            ], 201);

        }
        
    }


}
