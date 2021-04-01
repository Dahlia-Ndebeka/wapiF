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
            'date_evenement' => 'required',
            'heure_debut' => 'required',
            'heure_fin' => 'required',
            'annonces_id' => 'required',
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
                    'data' => null
                ], 201);

            }
            
        }

    }


    // Affichage des annonces a partir du calendrier

    public function Annonces($id){

        $annonces = calendriers::where('calendriers.id', '=', $id)
        ->join('annonces', function($join)
            {
                $join->on('calendriers.annonces_id', '=', 'annonces.id')
                ->where('annonces.actif', '=', true);
            })
        ->join('sous_categories', 'annonces.sous_categories_id', '=', 'sous_categories.id')
        ->join('categories', function($join)
            {
                $join->on('categories.id', '=', 'sous_categories.categories_id');
            })
        ->select('annonces.id',
            'annonces.titre',
            'annonces.description',
            'annonces.date',
            'annonces.type',
            'annonces.image_couverture',
            'annonces.lieu',
            'annonces.latitude',
            'annonces.longitude',
            'annonces.etat',
            'sous_categories.nom_sous_categorie',
            'categories.nomCategorie'
        )->get();

        if ($annonces) {
            
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $annonces
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => null
            ], 201);

        }
        
    }

    

    //Afficher les calendriers

    public function Calendriers(){

        $calendriers = calendriers::join('annonces', function($join)
            {
                $join->on('calendriers.annonces_id', '=', 'annonces.id');
            })
        ->join('sous_categories', 'annonces.sous_categories_id', '=', 'sous_categories.id')
        ->join('categories', function($join)
            {
                $join->on('categories.id', '=', 'sous_categories.categories_id');
            })
        ->select('calendriers.id',
            'calendriers.labelel',
            'calendriers.date_evenement',
            'calendriers.heure_debut',
            'calendriers.heure_fin',
            'annonces.titre',
            'annonces.description'
        )->get();

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
                'data' => null
            ], 201);

        }

    }


    // Consulter ou afficher un programme

    public function getCalendriers($id){

        $calendriers = calendriers::where('calendriers.id', '=', $id)
        ->join('annonces', function($join)
            {
                $join->on('calendriers.annonces_id', '=', 'annonces.id');
            })
        ->join('sous_categories', 'annonces.sous_categories_id', '=', 'sous_categories.id')
        ->join('categories', function($join)
            {
                $join->on('categories.id', '=', 'sous_categories.categories_id');
            })
        ->select('calendriers.id',
            'calendriers.labelel',
            'calendriers.date_evenement',
            'calendriers.heure_debut',
            'calendriers.heure_fin',
            'annonces.titre',
            'annonces.description'
        )->get();

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
                'data' => null
            ], 201);

        }

    }


    // Modifier un programme 

    public function putCalendrier(Request $request, $id){

        $data = $request->all();

        $modif = calendriers::findOrFail($id);

        $validator = Validator::make($data, [
            
            'date_evenement' => 'required|date',
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
                    'data' => null
                ], 201);

            }
            
        }

    }



    // Supprimer un calendrier
     
    public function deleteCalendrier($id){

        if (Auth::check()) {
            
            $user = Auth::user();

            $role = $user['role'];

            if ($role == "administrateur" || $role == "mobinaute") {

                $delete = calendriers::findOrFail($id)->delete();

                if ($delete) {

                    return response([
                        'code' => '200',
                        'message' => 'Suppression effectuée avec succes',
                        'data' => null
                    ], 200);

                } else {

                    return response([
                        'code' => '004',
                        'message' => 'L\'identifiant incorrect',
                        'data' => null
                    ], 201);

                }

            }else{

                return response([
                    'code' => '004',
                    'message' => 'Acces non autorise',
                    'data' => null
                ], 201);
            }
            
        }
        
    }

}