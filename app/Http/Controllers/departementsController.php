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
                'code' => '200',
                'message' => 'success',
                'data' => $departements
            ], 200);

        }else {

            return response([
                'code' => '004',
                'message' => 'Ta table est vide',
                'data' => 'null'
            ], 201);

        }
    }


    // Consulter ou afficher un departement

    public function getDepartement($id){

        $departements = departements::find($id);

        if($departements){
            
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $departements
            ], 200);

        }else {
            
            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
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
                'code' => '001',
                'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                'data' => $erreur
            ], 201);


        }else {

            $departement = departements::create($request->all());

            if ($departement) {
                
                return response([
                    'code' => '200',
                    'message' => 'success',
                    'data' => $departement
                ], 200);

            }else {
                
                return response([
                    'message' => '005',
                    'message' => 'Echec lors de l\'operation',
                    'data' => 'null'
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
                    'code' => '001',
                    'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                    'data' => $erreur
                ], 201);
    
            }else {
                
                $modif = $departement->update($request->all());

                if ($modif) {

                    return response([
                        'code' => '200',
                        'message' => 'success',
                        'data' => $departement
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

    // Affichage des etablissements a partir du departements

    public function Etablissements($id){

        $etablissements = departements::find($id)->Etablissements;

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
