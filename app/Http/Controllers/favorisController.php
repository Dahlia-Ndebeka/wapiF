<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\favoris;

class favorisController extends Controller
{
    
    //Afficher tous les favoris

    public function Favoris(){

        $favoris = favoris::all();

        if($favoris){

            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $favoris
            ], 200);

        }else {

            return response([
                'code' => '004',
                'message' => 'Ta table est vide',
                'data' => 'null'
            ], 201);

        }
    }


    // Consulter ou afficher favoris

    public function getFavoris($id){

        $favoris = favoris::find($id);

        if($favoris){
            
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $favoris
            ], 200);

        }else {
            
            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
            ], 201);

        }
       
    }


    // Creer favoris

    public function createFavoris(Request $request){

        $validator = Validator::make($request->all(), [
            
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

            $favoris = favoris::create($request->all());

            if ($favoris) {
                
                return response([
                    'code' => '200',
                    'message' => 'success',
                    'data' => $favoris
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



}
