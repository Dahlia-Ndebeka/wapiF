<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\notes;

class notesController extends Controller
{
    
    //Afficher les notes

    public function Notes(){

        $notes = notes::all();

        if ($notes) {

            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $notes
            ], 200);

        }else {

            return response([
                'code' => '005',
                'message' => 'La table est vide',
                'data' => $notes
            ], 201);

        }

    }


    // Consulter ou afficher une note

    public function getNote($id){

        $notes = notes::find($id);

        if ($notes) {
            
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $notes
            ], 200);

        }else {
            
            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
            ], 201);

        }

    }


    // Creer une note

    public function createNote(Request $request){

        $validator = Validator::make($request->all(), [
            
            'commentaire' => 'required|regex:/[^0-9.-]/',
            'score' => 'required',
            'utilisateurs_id' => 'required',
            'etablissements_id' => 'required'
        ]);

        if ($validator->fails()) {

            $erreur = $validator->errors();
            
            return response([
                'code' => '001',
                'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                'data' => $erreur
            ], 201);

        }else {

            $notes = notes::create($request->all());

            if ($notes) {
                
                return response([
                    'code' => '200',
                    'message' => 'success',
                    'data' => $notes
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


    // Modifier une note

    public function putNote(Request $request, $id){

        $identif = notes::findOrFail($id);

        if ($identif) {
            
            $validator = Validator::make($request->all(), [
            
                'commentaire' => 'required|regex:/[^0-9.-]/',
                'score' => 'required',
                'utilisateurs_id' => 'required',
                'etablissements_id' => 'required'
            ]);
    
            if ($validator->fails()) {
    
                $erreur = $validator->errors();
            
                return response([
                    'code' => '001',
                    'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                    'data' => $erreur
                ], 200);
    
            }else {
                
                $modif = $identif->update($request->all());

                if ($modif) {

                    return response([
                        'code' => '200',
                        'message' => 'success',
                        'data' => $identif
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


    // Affichage des utilisateurs a partir des notes

    public function Utilisateur($id){

        $Utilisateur = notes::find($id)->Utilisateurs;

        if ($Utilisateur) {
            
            return response([
                'message' => 'success',
                'data' => $Utilisateur
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
            ], 201);

        }
        
    }


    // Affichage des etablissements a partir des notes

    public function Etablissements($id){

        $Etablissements = notes::find($id)->Etablissements;

        if ($Etablissements) {
            
            return response([
                'message' => 'success',
                'data' => $Etablissements
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
