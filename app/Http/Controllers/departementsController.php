<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\departements;
use Illuminate\Support\Facades\Validator;


class departementsController extends Controller
{

    //Afficher tous les departements

    public function Departements(){

        $departements = departements::all();

        if($departements){

            return response([
                'message' => 'success',
                'data' => $departements
            ], 200);

        }else {

            return response([
                'message' => 'Erreur 004 : Pas d\'enregistrements, la table est vide',
                'data' => 'Nul'
            ], 201);

        }
    }


    // Consulter ou afficher un departement

    public function getDepartement($id){

        $departements = departements::find($id);

        if($departements){

            return $departements;return response([
                'message' => 'success',
                'data' => $departements
            ], 200);

        }else {
            
            return response([
                'message' => 'Erreur 004 : Aucune information ne correspond à votre demande',
                'data' => 'Nul'
            ], 201);

        }
       
    }


    // Creer un departement

    public function createDepartement(Request $request){

        $validator = Validator::make($request->all(), [
            
            'libelle' => 'required|unique:pays|max:250|regex:/[^0-9.-]/',
            'pays_id' => 'required'
        ]);

        if ($validator->fails()) {

            $erreur = $validator->errors();
            
            return response([
                'message' => 'success',
                'data' => $erreur
            ], 200);


        }else {

            $departement = departements::create($request->all());

            if ($departement) {
                
                return response([
                    'message' => 'success',
                    'data' => $departement
                ], 200);

            }else {
                
                return response([
                    'message' => 'Erreur 005 : echec lors de l\'ajout',
                    'data' => 'Nul'
                ], 201);

            }
            
        }

    }


    // Modifier un departement

    public function putDepartement(Request $request, $id){

        $departement = departements::findOrFail($id);

        if ($departement) {
            
            $validator = Validator::make($request->all(), [
            
                'libelle' => 'required|unique:pays|max:250|regex:/[^0-9.-]/',
                'pays_id' => 'required'
            ]);
    
            if ($validator->fails()) {
    
                $erreur = $validator->errors();
            
                return response([
                    'message' => 'success',
                    'data' => $erreur
                ], 200);
    
            }else {
                
                $modif = $departement->update($request->all());

                if ($modif) {

                    return response([
                        'message' => 'success',
                        'data' => $modif
                    ], 200);

                }else {
                    
                return response([
                    'message' => 'Erreur 005 : une erreur c\'est produite lors de la mofication',
                    'data' => 'Nul'
                ], 201);

                }
                
            }
        }else {
            
            return response([
                'message' => 'Erreur 004 : l\'information n\'existe pas',
                'data' => 'Nul'
            ], 201);

        }

    }


}
