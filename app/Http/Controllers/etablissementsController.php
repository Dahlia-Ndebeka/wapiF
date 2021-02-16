<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\etablissements;
use Illuminate\Support\Facades\Validator;

class etablissementsController extends Controller
{
    
    //Creer un etablissement

    public function createEtablissement(Request $request){

        $etablissement = $request->all();

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
            'logo'=> 'unique:etablissements|max:100', 
            'actif'=> 'required', 
            'latitude'=> 'required|max:100', 
            'longitude'=> 'required|max:100', 
            'arrondissements_id'=> 'required', 
            'utilisateurs_id'=> 'required',
            'categories_id' => 'required'
        ]);

        if ($validator->fails()) {

            $erreur = $validator->errors();
            
            return response([
                'code' => '001',
                'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                'data' => $erreur
            ], 201);

        }else {

            $img = $request->file('logo');

            if($request->hasFile('logo')){

                $imageName = rand(11111, 99999) . '.' . $request->file('logo')->getClientOriginalExtension();

                $img->move(public_path('/uploads/logos', $imageName));

                $etablissement['logo'] = $imageName;

                $ets = etablissements::create($etablissement);

                if ($ets) {
                    
                    return response([
                        'code' => '200',
                        'message' => 'success',
                        'data' => $ets
                    ], 200);

                } else {

                    return response([
                        'code' => '005',
                        'message' => 'Erreur lors de l\'operation',
                        'data' => 'null'
                    ], 201);
                    
                }

            }else {

                $ets = etablissements::create($request->all());

                if ($ets) {
                    
                    return response([
                        'code' => '200',
                        'message' => 'success',
                        'data' => $ets
                    ], 200);

                } else {

                    return response([
                        'message' => '005',
                        'message' => 'Echec lors de l\'operation',
                        'data' => 'null'
                    ], 201);
                    
                }
                
            }
            
        }

    }


    // Afficher les etablissements

    public function Etablissements(){

        $ets = etablissements::all();

        if ($ets) {
                    
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $ets
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Table est vide',
                'data' => 'null'
            ], 201);
            
        }

    }


    // Consulter ou afficher un etablissement

    public function Etablissement($id){

        $etablissement = etablissements::find($id);

        if ($etablissement) {
                    
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $etablissement
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
            ], 201);

        }
        
    }


    // Afficher les sous categories par rapport a l'etablissement

    public function sousCategorie($id){

        return $etablissements = etablissements::find($id)->sousCategories;

    }


    // Rechercher un etablissement

    public function rechercheEtablissement($valeur){

        $data = etablissements::where("nom_etablissement", "like", "%".$valeur."%" )
                                    ->orWhere("adresse", "like", "%".$valeur."%" )
                                    ->orWhere("telephone", "like", "%".$valeur."%" )
                                    ->orWhere("description", "like", "%".$valeur."%" )
                                    ->orWhere("heure_ouverture", "like", "%".$valeur."%" )
                                    ->orWhere("heure_fermeture", "like", "%".$valeur."%" )
                                    ->orWhere("email", "like", "%".$valeur."%" )
                                    ->orWhere("boite_postale", "like", "%".$valeur."%" )
                                    ->orWhere("site_web", "like", "%".$valeur."%" )
                                    ->orWhere("logo", "like", "%".$valeur."%" )
                                    ->orWhere("latitude", "like", "%".$valeur."%" )
                                    ->orWhere("longitude", "like", "%".$valeur."%" )->get(); 
            
        return response([
            'code' => '200',
            'message' => 'success',
            'data' => $data
        ], 200);
                                    

    }


    // Modifier un etablissement

    public function putEtablissement(Request $request, $id){

        $identif = etablissements::findOrFail($id);

        $etablissement = $request->all();

        $validator = Validator::make($request->all(), [
            
            // 'nom_etablissement'=> 'required|unique:etablissements|max:100|regex:/[^0-9.-]/', 
            // 'adresse'=> 'required|unique:etablissements|max:100', 
            // 'telephone'=> 'required|unique:etablissements|max:100|regex:/[^a-zA-Z]/', 
            // 'description'=> 'required|unique:etablissements|max:255|regex:/[^0-9.-]/', 
            // 'heure_ouverture'=> 'required|max:100', 
            // 'heure_fermeture'=> 'required|max:100', 
            // 'email'=> 'required|unique:etablissements|max:200|email', 
            // 'boite_postale'=> 'required|unique:etablissements|max:100', 
            // 'site_web'=> 'required|unique:etablissements|max:100|regex:/[^0-9.-]/', 
            // 'logo'=> 'unique:etablissements|max:100', 
            // 'actif'=> 'required', 
            // 'latitude'=> 'required|max:100', 
            // 'longitude'=> 'required|max:100', 
            // 'arrondissements_id'=> 'required', 
            // 'utilisateurs_id'=> 'required',
            // 'categories_id' => 'required'
        ]);

        if ($validator->fails()) {

            $erreur = $validator->errors();
            
            return response([
                'code' => '001',
                'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                'data' => $erreur
            ], 201);

        }else {

            $img = $request->file('logo');

            if($request->hasFile('logo')){

                $imageName = rand(11111, 99999) . '.' . $request->file('logo')->getClientOriginalExtension();

                $img->move(public_path('/uploads/logos', $imageName));

                $etablissement['logo'] = $imageName;

                $ets = $identif->update($etablissement);

                if ($ets) {
                    
                    return response([
                        'code' => '200',
                        'message' => 'success',
                        'data' => $identif
                    ], 200);

                } else {

                    return response([
                        'code' => '005',
                        'message' => 'Erreur lors de l\'operation',
                        'data' => 'null'
                    ], 201);
                    
                }

            }else {

                $ets = $identif->update($etablissement);

                if ($ets) {
                    
                    return response([
                        'code' => '200',
                        'message' => 'success',
                        'data' => $identif
                    ], 200);

                } else {

                    return response([
                        'message' => '005',
                        'message' => 'Echec lors de l\'operation',
                        'data' => 'null'
                    ], 201);
                    
                }
                
            }
            
        }

    }


    // Affichage des annonces a partir de l'etablissement

    public function Annonces($id){

        $annonces = etablissements::find($id)->Annonces;

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


    // Affichage des notes a partir des etablissements

    public function Notes($id){

        $Notes = etablissements::find($id)->Notes;

        if ($Notes) {
            
            return response([
                'message' => 'success',
                'data' => $Notes
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
