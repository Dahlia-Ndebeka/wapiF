<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\commentaires;

class commentairesController extends Controller
{

    //Creer un commentaire

    public function createCommentaire(Request $request){

        $validator = Validator::make($request->all(), [
            
            'commentaire' => 'required',
            'utilisateurs_id' => 'required',
            'annonces_id' => 'required'
        ]);

        if ($validator->fails()) {

            $erreur = $validator->errors();
            
            return response([
                'code' => '001',
                'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                'data' => $erreur
            ], 201);

        }else {

            $commentaires = commentaires::create($request->all());

            if ($commentaires) {
                
                return response([
                    'code' => '200',
                    'message' => 'success',
                    'data' => $commentaires
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


    // Afficher les commentaires

    public function Commentaires(){

        $commentaires = commentaires::all();

        if ($commentaires) {

            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $commentaires
            ], 200);

        }else {

            return response([
                'code' => '005',
                'message' => 'La table est vide',
                'data' => $commentaires
            ], 201);

        }

    }


    // Consulter ou afficher un commentaire

    public function getCommentaire($id){

        // $commentaires = commentaires::find($id);

        $commentaires = commentaires::where('commentaires.id', '=', $id)
            ->join('utilisateurs', 'utilisateurs.id', '=', 'commentaires.utilisateurs_id')
            ->select(
                'commentaires.id',
                'commentaires.commentaire',
                'commentaires.created_at',
                'utilisateurs.login',
                'utilisateurs.email',
            )->get();

        if ($commentaires) {
            
            return response([
                
                'code' => '200',
                'message' => 'success',
                'data' => $commentaires,
                
            ], 200);

        }else {
            
            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
            ], 201);

        }

    }


    // Affichage d'utilisateur à partir des commentaires

    public function Utilisateur($id){

        $utilisateur = commentaires::find($id)->Utilisateurs;

        if ($utilisateur) {
            
            return response([
                'message' => 'success',
                'data' => $utilisateur
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
            ], 201);

        }
        
    }


    // Modifier un commentaire

    public function putCommentaire(Request $request, $id){

        $identif = commentaires::findOrFail($id);

        $data = $request->all();

        $validator = Validator::make($request->all(), [
            
            'commentaire' => 'required',
            'utilisateurs_id' => 'required',
            'annonces_id' => 'required'
        ]);

        if ($validator->fails()) {

            $erreur = $validator->errors();
            
            return response([
                'code' => '001',
                'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                'data' => $erreur
            ], 201);

        }else {

            $commentaires = $identif->update($data);

            if ($commentaires) {
                
                return response([
                    'code' => '200',
                    'message' => 'success',
                    'data' => $identif
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
    

}
