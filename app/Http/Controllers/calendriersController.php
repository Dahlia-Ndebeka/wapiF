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

}
