<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\etablissements;
use Illuminate\Support\Facades\Validator;
use App\Models\categories;
use App\Models\sous_categories;
use App\Models\etablissements_sous_categories;


class etablissementsController extends Controller
{
    
    //Creer un etablissement

    public function createEtablissement(Request $request){

        $etablissement = $request->all();

        $heure_ouverture = $etablissement['heure_ouverture'];

        $heure_fermeture = $etablissement['heure_fermeture'];

        $valeur_ouverture = ($heure_ouverture * 3600);

        $valeur_fermeture = ($heure_fermeture * 3600);

        $validator = Validator::make($request->all(), [
            
            'nom_etablissement'=> 'required|unique:etablissements|max:100|regex:/[^0-9.-]/', 
            'adresse'=> 'required', 
            'telephone'=> 'required|unique:etablissements|max:100|regex:/[^a-zA-Z]/', 
            'description'=> 'required|unique:etablissements|max:255|regex:/[^0-9.-]/', 
            'heure_ouverture'=> 'required', 
            'heure_fermeture'=> 'required', 
            'email'=> 'required|unique:etablissements|max:200|email', 
            'boite_postale'=> 'required|unique:etablissements|max:100', 
            'site_web'=> 'required|unique:etablissements|max:100|regex:/[^0-9.-]/', 
            // 'logo'=> 'unique:etablissements|max:100', 
            'actif'=> 'required', 
            'latitude'=> 'required|max:100', 
            'longitude'=> 'required|max:100', 
            'arrondissements_id'=> 'required', 
            'utilisateurs_id'=> 'required',
            // 'sous_categories_id' => 'required',
            'nom_sous_categorie' => 'required'
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

                $fileName = $request->file('logo')->getClientOriginalName();

                $path = $img->move(public_path("/uploads/images/"), $fileName);

                $photoURL = url('/uploads/images/'.$fileName);

                $etablissement['logo'] = $fileName;

                $etablissement['heure_ouverture'] = $valeur_ouverture;

                $etablissement['heure_fermeture'] = $valeur_fermeture;

                $ets = etablissements::create($etablissement);

                $nomSousCat = $etablissement['nom_sous_categorie'];

                $result = sous_categories::where('nom_sous_categorie', '=', $nomSousCat)->addSelect('id')->first();

                $id2 = $result->id;

                $id1 = $ets['id'];

                $EtsSousCat = etablissements_sous_categories::firstOrCreate([
                    'etablissements_id' => $id1,
                    'sous_categories_id' => $id2,
                ]);

                if ($ets) {
                    
                    return response([
                        'code' => '200',
                        'message' => 'success',
                        'data' => $ets,
                        'url' => $photoURL,
                    ], 200);

                } else {

                    return response([
                        'code' => '005',
                        'message' => 'Erreur lors de l\'operation',
                        'data' => 'null'
                    ], 201);
                    
                }

            }else {

                $etablissement['heure_ouverture'] = $valeur_ouverture;

                $etablissement['heure_fermeture'] = $valeur_fermeture;

                $ets = etablissements::create($etablissement);

                $nomSousCat = $etablissement['nom_sous_categorie'];

                $result = sous_categories::where('nom_sous_categorie', '=', $nomSousCat)->addSelect('id')->first();

                $id2 = $result->id;

                $id1 = $ets['id'];

                $EtsSousCat = etablissements_sous_categories::firstOrCreate([
                    'etablissements_id' => $id1,
                    'sous_categories_id' => $id2,
                ]);

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

        $ets = etablissements::
        join('etablissements_sous_categories', 'etablissements_sous_categories.etablissements_id', '=', 'etablissements.id')
        ->join('sous_categories', 'etablissements_sous_categories.sous_categories_id', '=', 'sous_categories.id')
        ->join('categories', function($join)
            {
                $join->on('categories.id', '=', 'sous_categories.categories_id');
            })
        ->join('arrondissements', 'etablissements.arrondissements_id', '=', 'arrondissements.id')
        ->join('villes', function($join)
            {
                $join->on('villes.id', '=', 'arrondissements.villes_id');
            })
        ->join('departements', function($join)
            {
                $join->on('departements.id', '=', 'villes.departements_id');
            })
        ->join('pays', function($join)
            {
                $join->on('pays.id', '=', 'departements.pays_id');
            })
        ->select('etablissements.id',
                    'etablissements.nom_etablissement',
                    'etablissements.adresse',
                    'etablissements.telephone',
                    'etablissements.description',
                    'etablissements.heure_ouverture',
                    'etablissements.heure_fermeture',
                    'etablissements.email',
                    'etablissements.boite_postale',
                    'etablissements.site_web',
                    'etablissements.logo',
                    'etablissements.latitude',
                    'etablissements.longitude',
                    'sous_categories.nom_sous_categorie',
                    'categories.nomCategorie',
                    'categories.image',
                    'categories.titre',
                    'arrondissements.libelle_arrondissement', 
                    'villes.libelle_ville', 
                    'departements.libelle_departement',
                    'pays.libelle_pays')->get();

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

        // $etablissement = etablissements::find($id);

        // $etablissements = etablissements::find($id)->sousCategories;


        $etablissement = etablissements::from('etablissements')->where('etablissements.id', '=', $id)
        ->join('etablissements_sous_categories', 'etablissements_sous_categories.etablissements_id', '=', 'etablissements.id')
        ->join('sous_categories', 'etablissements_sous_categories.sous_categories_id', '=', 'sous_categories.id')
        ->join('categories', function($join)
            {
                $join->on('categories.id', '=', 'sous_categories.categories_id');
            })
        ->join('arrondissements', 'etablissements.arrondissements_id', '=', 'arrondissements.id')
        ->join('villes', function($join)
            {
                $join->on('villes.id', '=', 'arrondissements.villes_id');
            })
        ->join('departements', function($join)
            {
                $join->on('departements.id', '=', 'villes.departements_id');
            })
        ->join('pays', function($join)
            {
                $join->on('pays.id', '=', 'departements.pays_id');
            })
        ->select('etablissements.id',
                    'etablissements.nom_etablissement',
                    'etablissements.adresse',
                    'etablissements.telephone',
                    'etablissements.description',
                    'etablissements.heure_ouverture',
                    'etablissements.heure_fermeture',
                    'etablissements.email',
                    'etablissements.boite_postale',
                    'etablissements.site_web',
                    'etablissements.logo',
                    'etablissements.latitude',
                    'etablissements.longitude',
                    'sous_categories.nom_sous_categorie',
                    'categories.nomCategorie',
                    'categories.image',
                    'categories.titre',
                    'arrondissements.libelle_arrondissement', 
                    'villes.libelle_ville', 
                    'departements.libelle_departement',
                    'pays.libelle_pays')->get();
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

        // $data = etablissements::where("etablissement", "like", "%".$valeur."%" )->get();

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
            
            'nom_etablissement'=> 'required|unique:etablissements|max:100|regex:/[^0-9.-]/', 
            'adresse'=> 'required|unique:etablissements|max:100', 
            'telephone'=> 'required|unique:etablissements|max:100|regex:/[^a-zA-Z]/', 
            'description'=> 'required|unique:etablissements|max:255|regex:/[^0-9.-]/', 
            'heure_ouverture'=> 'required', 
            'heure_fermeture'=> 'required', 
            'email'=> 'required|unique:etablissements|max:200|email', 
            'boite_postale'=> 'required|unique:etablissements|max:100', 
            'site_web'=> 'required|unique:etablissements|max:100|regex:/[^0-9.-]/',
            'actif'=> 'required', 
            'latitude'=> 'required|max:100', 
            'longitude'=> 'required|max:100', 
            'arrondissements_id'=> 'required', 
            'utilisateurs_id'=> 'required',
            'sous_categories_id' => 'required'
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

                $fileName = $request->file('logo')->getClientOriginalName();

                $path = $img->move(public_path("/uploads/images/"), $fileName);

                $photoURL = url('/uploads/images/'.$fileName);

                $etablissement['logo'] = $fileName;

                $ets = $identif->update($etablissement);

                if ($ets) {
                    
                    return response([
                        'code' => '200',
                        'message' => 'success',
                        'data' => $identif,
                        'url' => $photoURL
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



    // Affichage des sous categories a partir des etablissements

    public function sousCategories($id){

        $sousCategories = etablissements::find($id);

        $sousCat = $sousCategories->sousCategories;

        return $sousCat;

    }





    // Affichage des categories a partir des etablissements

    // public function Categories($id){

    //     $Categories = etablissements::find($id)->Categories;

    //     if ($Categories) {
            
    //         return response([
    //             'message' => 'success',
    //             'data' => $Categories
    //         ], 200);

    //     } else {

    //         return response([
    //             'code' => '004',
    //             'message' => 'Identifiant incorrect',
    //             'data' => 'null'
    //         ], 201);

    //     }
        
    // }



    // Affichage la ville a partir des etablissements

    public function Villes($id){

        $villes = etablissements::find($id)->Villes;

        if ($villes) {
            
            return response([
                'message' => 'success',
                'data' => $villes
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
            ], 201);

        }
        
    }


    public function ets(){
        
        return $categorie = etablissements::
        join('etablissements_sous_categories', 'etablissements_sous_categories.etablissements_id', '=', 'etablissements.id')
        ->join('sous_categories', 'etablissements_sous_categories.sous_categories_id', '=', 'sous_categories.id')
        ->join('categories', function($join)
            {
                $join->on('categories.id', '=', 'sous_categories.categories_id');
            })
        ->select('etablissements.*',
                    'categories.nomCategorie',
                    'categories.image',
                    'categories.titre'
                    )->get();
    }



    // Acceder aux images
     
    public function image($fileName){
        
        return response()->download(public_path('/uploads/images/' . $fileName));

    }
    

}
