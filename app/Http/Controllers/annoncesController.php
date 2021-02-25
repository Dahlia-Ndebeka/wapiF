<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\annonces;
use App\Models\annonces_etablissements;
use App\Models\etablissements;

class annoncesController extends Controller
{
    
    // Affichage des images annonces a partir de l'annonce

    public function imageAnnonce($id){

        $imageAnnonce = annonces::find($id)->AnnonceImage;

        if ($imageAnnonce) {
            
            return response([
                'message' => 'success',
                'data' => $imageAnnonce
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
            ], 201);

        }
        
    }




    // Affichage des etablissements a partir de l'annonce

    public function Etablissements($id){

        $ets = annonces::find($id)->Etablissements;

        if ($ets) {
            
            return response([
                'message' => 'success',
                'data' => $ets
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
            ], 201);

        }
        
    }



    //Afficher toutes les annonces 

    public function Annonces(){

        $annonces = annonces::all();

        if ($annonces) {

            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $annonces
            ], 200);

        }else {

            return response([
                'code' => '005',
                'message' => 'La table est vide',
                'data' => $annonces
            ], 201);

        }

    }



    //Afficher les annonces publiees

    public function AnnoncesPublier(){

        $annonces = annonces::where('etat', '=', true)->get();

        if ($annonces) {

            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $annonces
            ], 200);

        }else {

            return response([
                'code' => '005',
                'message' => 'La table est vide',
                'data' => $annonces
            ], 201);

        }

    }




    // Consulter ou afficher une annonce

    public function getAnnonce($id){

        $annonces = annonces::find($id);

        if ($annonces) {
            
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $annonces
            ], 200);

        }else {
            
            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
            ], 201);

        }

    }

    

    // Affichage des utilisateurs a partir de l'annonce

    public function Utilisateur($id){

        $utilisateur = annonces::find($id)->Utilisateurs;

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
    
    
    
    
    // Affichage du calendrier a partir de l'annonce

    public function Calendrier($id){

        $calendrier = annonces::find($id)->Calendriers;

        if ($calendrier) {
            
            return response([
                'message' => 'success',
                'data' => $calendrier
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
            ], 201);

        }
        
    }



    // Rechercher une annonce

    public function rechercheAnnonce($valeur){

        $data = annonces::where("titre", "like", "%".$valeur."%" )
                                    ->orWhere("description", "like", "%".$valeur."%" )
                                    ->orWhere("etat", "like", "%".$valeur."%" )
                                    ->orWhere("date", "like", "%".$valeur."%" )
                                    ->orWhere("type", "like", "%".$valeur."%" )
                                    ->orWhere("image_couverture", "like", "%".$valeur."%" )
                                    ->orWhere("lieu", "like", "%".$valeur."%" )->get();
            
        return response([
            'code' => '200',
            'message' => 'success',
            'data' => $data
        ], 200);
                                    
    }


    
    // Creer une annonce

    public function createAnnonce(Request $request){

        $data = $request->all();

        $valeur = $data['etablissement'];

        $validator = Validator::make($data, [
            'titre' => 'required|unique:annonces|max:250|regex:/[^0-9.-]/',
            'description' => 'required',
            'date' => 'required',
            'type' => 'required',
            'image_couverture',
            'lieu' => 'required',
            'latitude',
            'longitude',
            'etablissement'=> 'required',
            'etat',
            'actif',
            'utilisateurs_id' => 'required',
            'sous_categories_id' => 'required',
            'calendriers_id' => 'required',
        ]);

        if ($validator->fails()) {

            $erreur = $validator->errors();
            
            return response([
                'code' => '001',
                'message' => 'L\'un des champs est vide ou ne respecte pas au format',
                'data' => $erreur
            ], 201);

        }else {

            if ($valeur == true) {

                $validator = Validator::make($request->all(), [

                    'nom_etablissement' => 'required',
                ]);

                if ($validator->fails()) {

                    $erreur = $validator->errors();
                    
                    return response([
                        'code' => '001',
                        'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                        'data' => $erreur
                    ], 201);
        
                }else {

                    $img = $request->file('image_couverture');

                    if($request->hasFile('image_couverture')){
    
                        $imageName = rand() . '.' . $request->file('image_couverture')->getClientOriginalExtension();
    
                        $img->move(public_path('/annonces/images', $imageName));
    
                        // return response()->json($imageName);
    
                        $data['image_couverture'] = $imageName;
    
                        $annonces = annonces::create($data);

                        // On enregistre automatiquement les identifiants dans la table pivot (annonces_etablissements)

                        $nomEts = $data['nom_etablissement'];

                        $result = etablissements::where('nom_etablissement', '=', $nomEts)->addSelect('id')->first();
    
                        $id1 = $result->id;
    
                        $id2 = $annonces['id'];
    
                        $EtsAnnonces = annonces_etablissements::firstOrCreate([
                            'etablissements_id' => $id1,
                            'annonces_id' => $id2,
                        ]);
    
                        if ($annonces) {
    
                            return response([
                                'code' => '200',
                                'message' => 'success',
                                'data' => $annonces
                            ], 200);
    
                        }else {
    
                            return response([
                                'code' => '005',
                                'message' => 'Echec lors de l\'operation',
                                'data' => 'null'
                            ], 201);
    
                        }
    
                    }else {
    
                        $annonces = annonces::create($data);

                        // On enregistre automatiquement les identifiants dans la table pivot (annonces_etablissements)

                        $nomEts = $data['nom_etablissement'];

                        $result = etablissements::where('nom_etablissement', '=', $nomEts)->addSelect('id')->first();
    
                        $id1 = $result->id;
    
                        $id2 = $annonces['id'];
    
                        $EtsAnnonces = annonces_etablissements::firstOrCreate([
                            'etablissements_id' => $id1,
                            'annonces_id' => $id2,
                        ]);
    
                        return response([
                            'code' => '200',
                            'message' => $annonces,
                            'data' => 'null'
                        ], 201);
    
                    } 

                }

            } else {
                
                $img = $request->file('image_couverture');

                if($request->hasFile('image_couverture')){

                    $imageName = rand() . '.' . $request->file('image_couverture')->getClientOriginalExtension();

                    $img->move(public_path('/annonces/images', $imageName));

                    // return response()->json($imageName);

                    $data['image_couverture'] = $imageName;

                    $annonces = annonces::create($data);

                    if ($annonces) {

                        return response([
                            'code' => '200',
                            'message' => 'success',
                            'data' => $annonces
                        ], 200);

                    }else {

                        return response([
                            'code' => '005',
                            'message' => 'Echec lors de l\'operation',
                            'data' => 'null'
                        ], 201);

                    }

                }else {

                    $annonces = annonces::create($data);

                    return response([
                        'code' => '200',
                        'message' => $annonces,
                        'data' => 'null'
                    ], 201);
                    
                }

            }
             
        }


    }





    // publier une annonce

    public function publier($id){

        $annonces = annonces::find($id);

        $valeur = $annonces['etat'];

        if ($valeur == true) {

            return response([
                'code' => '200',
                'message' => 'success, votre annonce a ete publie',
                'data' => $annonces
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Desole vous ne pouvez pas publiez cette annonce son etat est bloque',
                'data' => 'null'
            ], 201);

        }

    }



    // Modifier annonce

    public function putAnnonce(Request $request, $id){

        $identifiant = annonces::findOrFail($id);

        $data = $request->all();

        if ($identifiant) {
            
            $valeur = $data['etablissement'];

            $validator = Validator::make($data, [
                'titre' => 'required|unique:annonces|max:250|regex:/[^0-9.-]/',
                'description' => 'required',
                'date' => 'required',
                'type' => 'required',
                'image_couverture',
                'lieu' => 'required',
                'latitude',
                'longitude',
                'etablissement'=> 'required',
                'etat',
                'actif',
                'utilisateurs_id' => 'required',
                'sous_categories_id' => 'required',
                'calendriers_id' => 'required',
            ]);
    
            if ($validator->fails()) {
    
                $erreur = $validator->errors();
                
                return response([
                    'code' => '001',
                    'message' => 'L\'un des champs est vide ou ne respecte pas au format',
                    'data' => $erreur
                ], 201);
    
            }else {
    
                if ($valeur == true) {
    
                    $validator = Validator::make($request->all(), [
    
                        'nom_etablissement' => 'required',
                    ]);
    
                    if ($validator->fails()) {
    
                        $erreur = $validator->errors();
                        
                        return response([
                            'code' => '001',
                            'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                            'data' => $erreur
                        ], 201);
            
                    }else {
    
                        $img = $request->file('image_couverture');
    
                        if($request->hasFile('image_couverture')){
        
                            $imageName = rand() . '.' . $request->file('image_couverture')->getClientOriginalExtension();
        
                            $img->move(public_path('/annonces/images', $imageName));
        
                            // return response()->json($imageName);
        
                            $data['image_couverture'] = $imageName;
        
                            $annonces = $identifiant->update($data);
    
                            // On enregistre automatiquement les identifiants dans la table pivot (annonces_etablissements)
    
                            $nomEts = $data['nom_etablissement'];
    
                            $result = etablissements::where('nom_etablissement', '=', $nomEts)->addSelect('id')->first();
        
                            $id1 = $result->id;
        
                            $id2 = $annonces['id'];
        
                            $EtsAnnonces = annonces_etablissements::firstOrCreate([
                                'etablissements_id' => $id1,
                                'annonces_id' => $id2,
                            ]);
        
                            if ($annonces) {
        
                                return response([
                                    'code' => '200',
                                    'message' => 'success',
                                    'data' => $annonces
                                ], 200);
        
                            }else {
        
                                return response([
                                    'code' => '005',
                                    'message' => 'Echec lors de l\'operation',
                                    'data' => 'null'
                                ], 201);
        
                            }
        
                        }else {
        
                            $annonces = $identifiant->update($data);
    
                            // On enregistre automatiquement les identifiants dans la table pivot (annonces_etablissements)
    
                            $nomEts = $data['nom_etablissement'];
    
                            $result = etablissements::where('nom_etablissement', '=', $nomEts)->addSelect('id')->first();
        
                            $id1 = $result->id;
        
                            $id2 = $annonces['id'];
        
                            $EtsAnnonces = annonces_etablissements::firstOrCreate([
                                'etablissements_id' => $id1,
                                'annonces_id' => $id2,
                            ]);
        
                            return response([
                                'code' => '200',
                                'message' => $annonces,
                                'data' => 'null'
                            ], 201);
        
                        } 
    
                    }
    
                } else {
                    
                    $img = $request->file('image_couverture');
    
                    if($request->hasFile('image_couverture')){
    
                        $imageName = rand() . '.' . $request->file('image_couverture')->getClientOriginalExtension();
    
                        $img->move(public_path('/annonces/images', $imageName));
    
                        // return response()->json($imageName);
    
                        $data['image_couverture'] = $imageName;
    
                        $annonces = $identifiant->update($data);
    
                        if ($annonces) {
    
                            return response([
                                'code' => '200',
                                'message' => 'success',
                                'data' => $annonces
                            ], 200);
    
                        }else {
    
                            return response([
                                'code' => '005',
                                'message' => 'Echec lors de l\'operation',
                                'data' => 'null'
                            ], 201);
    
                        }
    
                    }else {
    
                        $annonces = $identifiant->update($data);
    
                        return response([
                            'code' => '200',
                            'message' => $annonces,
                            'data' => 'null'
                        ], 201);
                        
                    }
    
                }
                 
            }

        }else {
            
            return response([
                'code' => '004',
                'message' => 'L\'identifiant incorrect',
                'data' => 'null'
            ], 201);

        }
        
    }
    


}
