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
                'message' => 'success',
                'data' => $arrondissement
            ], 200);

        }else {

            return response([
                'message' => 'Erreur 004 : Aucune information n\'existe, la table est vide',
                'data' => 'Nul'
            ], 201);
        }

    }


    // Consulter ou afficher un arrondissement

    public function getArrondissement($id){

        $arrondissement = arrondissements::find($id);

        if ($arrondissement) {
            
            return response([
                'message' => 'success',
                'data' => $arrondissement
            ], 200);

        }else {
            
            return response([
                'message' => 'Erreur 004 : l\'identifiant n\'existe pas',
                'data' => 'Nul'
            ], 201);

        }
    }


    // Creer un arrondissement

    public function createArrondissement(Request $request){

        $validator = Validator::make($request->all(), [
            
            'libelle' => 'required|unique:pays|max:250|regex:/[^0-9.-]/',
            'villes_id' => 'required'
        ]);

        if ($validator->fails()) {

            $erreur = $validator->errors();
            
            return response([
                'message' => 'success',
                'data' => $erreur
            ], 200);

        }else {

            $arrondissement = arrondissements::create($request->all());

            if ($arrondissement) {
                
                return response([
                    'message' => 'success',
                    'data' => $arrondissement
                ], 200);

            }else {
                
                return response([
                    'message' => 'Erreur 005 : Echec lors de la creation',
                    'data' => 'Nul'
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
                    'message' => 'success',
                    'data' => $erreur
                ], 200);
    
            }else {
                
                $modif = $arrondissement->update($request->all());

                if ($modif) {

                    return response([
                        'message' => 'success',
                        'data' => $modif
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
                'message' => 'Erreur 004 : l\'identifiant n\'existe pas',
                'data' => 'Nul'
            ], 201);

        }

    }


}
