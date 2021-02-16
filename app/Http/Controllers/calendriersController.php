<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\calendriers;


class calendriersController extends Controller
{
    
    // Creer un calendrier

    public function createCalendrier(Request $request){

        $validator = Validator::make($request->all(), [
            
            'date' => 'required',
            'heure_debut' => 'required',
            'heure_fin' => 'required'
        ]);

        if ($validator->fails()) {

            $erreur = $validator->errors();
            
            return response([
                'code' => '001',
                'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                'data' => $erreur
            ], 201);

        }else {

            $calendriers = calendriers::create($request->all());

            if ($calendriers) {
                
                return response([
                    'code' => '200',
                    'message' => 'success',
                    'data' => $calendriers
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


    // Affichage des annonces a partir du calendrier

    public function Annonces($id){

        $annonces = calendriers::find($id)->Annonces;

        if ($annonces) {
            
            return response([
                'message' => 'success',
                'data' => $annonces
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
            ], 201);

        }
        
    }

    

    //Afficher les programmes

    public function Calendriers(){

        $calendriers = calendriers::all();

        if ($calendriers) {

            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $calendriers
            ], 200);

        }else {

            return response([
                'code' => '005',
                'message' => 'La table est vide',
                'data' => $calendriers
            ], 201);

        }

    }


    // Consulter ou afficher un programme

    public function getArrondissement($id){

        $calendriers = calendriers::find($id);

        if ($calendriers) {
            
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $calendriers
            ], 200);

        }else {
            
            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
            ], 201);

        }

    }


    // Modifier un programme 

    public function putCalendrier(Request $request, $id){

        $data = $request->all();

        $modif = calendriers::findOrFail($id);

        $validator = Validator::make($request->all(), [
            
            'date' => 'required',
            'heure_debut' => 'required',
            'heure_fin' => 'required'
        ]);

        if ($validator->fails()) {

            $erreur = $validator->errors();
            
            return response([
                'code' => '001',
                'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                'data' => $erreur
            ], 201);

        }else {

            $calendriers = $modif->update($data);

            if ($calendriers) {
                
                return response([
                    'code' => '200',
                    'message' => 'success',
                    'data' => $modif
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
