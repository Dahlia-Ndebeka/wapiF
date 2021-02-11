<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\etablissements;
use Illuminate\Support\Facades\Validator;

class etablissementsController extends Controller
{
    
    //Creer un etablissement

    public function createEtablissement(Request $request){

        $validator = Validator::make($request->all(), [
            
            'nom_etablissement'=> 'required|unique:etablissements|max:100|regex:/[^0-9.-]/', 
            'adresse'=> 'required|unique:etablissements|max:100', 
            'telephone'=> 'required|unique:etablissements|max:100|regex:/[^a-zA-Z]/', 
            'description'=> 'required|unique:etablissements|max:255|regex:/[^0-9.-]/', 
            'heure_ouverture'=> 'required|max:100', 
            'heure_fermeture'=> 'required|max:100', 
            'email'=> 'required|unique:etablissements|max:200|email', 
            'boite_postale'=> 'required|unique:etablissements|max:100', 
            'site_web'=> 'required|unique:etablissements|max:100|regex:/[^0-9.-]/', 
            'logo'=> 'required|unique:etablissements|max:100', 
            'actif'=> 'required', 
            'latitude'=> 'required|max:100', 
            'longitude'=> 'required|max:100', 
            'arrondissements_id'=> 'required', 
            'utilisateurs_id'=> 'required'
        ]);

        if ($validator->fails()) {

            $erreur = $validator->errors();
            
            return response([
                'message' => 'success',
                'data' => $erreur
            ], 200);

        }else {

            $img = $request->file('logo');

            if($request->hasFile('logo')){

                $imageName = rand(11111, 99999) . '.' . $request->file('logo')->getClientOriginalExtension();

                $img->move(public_path('/uploads/logos', $imageName));

                $Utilisateur['logo'] = $imageName;

                $Utilisateur['password'] = Hash::make($Utilisateur['password']);

                $tes = etablissements::create($Utilisateur);

                if ($tes) {
                    
                    return response([
                        'message' => 'success',
                        'data' => $tes
                    ], 200);

                } else {

                    return response([
                        'message' => 'Erreur 005 : echec lors de la creation',
                        'data' => 'Nul'
                    ], 201);
                    
                }

            }else {

                $ets = etablissements::create($request->all());

                if ($tes) {
                    
                    return response([
                        'message' => 'success',
                        'data' => $tes
                    ], 200);

                } else {

                    return response([
                        'message' => 'Erreur 005 : echec lors de la creation',
                        'data' => 'Nul'
                    ], 201);
                    
                }
                
            }
            
        }

    }


    // Afficher les etablissements

    public function Etablissements(){

        $ets = etablissements::all();

        if ($tes) {
                    
            return response([
                'message' => 'success',
                'data' => $tes
            ], 200);

        } else {

            return response([
                'message' => 'Erreur 004 : Table est vide',
                'data' => 'Nul'
            ], 201);
            
        }

    }


    // Consulter ou afficher un etablissement

    public function Etablissement($id){

        $etablissement = etablissements::find($id);

        if ($etablissement) {
                    
            return response([
                'message' => 'success',
                'data' => $tes
            ], 200);

        } else {

            return response([
                'message' => 'Erreur 004 : Identifiant incorrect',
                'data' => 'Nul'
            ], 201);

        }
        
    }


    // Afficher les sous categories par rapport a l'etablissement

    public function sousCategorie($id){

        return $etablissements = etablissements::find($id)->sousCategories;

    }

}
