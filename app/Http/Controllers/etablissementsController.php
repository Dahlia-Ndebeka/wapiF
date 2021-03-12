<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\etablissements;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class etablissementsController extends Controller
{
    
    //Creer un etablissement

    public function createEtablissement(Request $request){

        if (Auth::check()) {
            
            // utilisateur actuellement authentifie
            $user = Auth::user();

            $role = $user['role'];

            if ($role == null) {

                return response([
                    'code' => '001',
                    'message' => 'Vous n\'avez aucun role veuillez completer vos informations avant de poursuivre ',
                    'data' => 'null'
                ], 201);

            }else {

                if ($role == 'administrateur') {

                    $etablissement = $request->all();

                    $heure_ouverture = $etablissement['heure_ouverture'];

                    $heure_fermeture = $etablissement['heure_fermeture'];

                    $valeur_ouverture = ($heure_ouverture * 3600);

                    $valeur_fermeture = ($heure_fermeture * 3600);

                    $validator = Validator::make($request->all(), [
                        
                        'nom_etablissement'=> 'required|unique:etablissements|max:100|regex:/[^0-9.-]/', 
                        'adresse'=> 'required', 
                        'telephone'=> 'unique:etablissements|max:100|regex:/[^a-zA-Z]/', 
                        'description'=> 'required|unique:etablissements|max:255|regex:/[^0-9.-]/', 
                        'heure_ouverture'=> 'required', 
                        'heure_fermeture'=> 'required', 
                        'email'=> 'required|unique:etablissements|max:200|email', 
                        'boite_postale'=> 'unique:etablissements|max:100', 
                        'site_web'=> 'required|unique:etablissements|max:100|regex:/[^0-9.-]/', 
                        'logo'=> 'image|mimes:jpeg,png,jpg,svg|max:2048',
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


                            foreach ($ets as $etablissements) {
                    
                                return response([
                                    'code' => '200',
                                    'message' => 'success',
                                    'data' => $etablissements
                                ], 200);
                                
                            }

                            // if ($ets) {
                                
                            //     return response([
                            //         'code' => '200',
                            //         'message' => 'success',
                            //         'data' => $ets,
                            //         'url' => $photoURL,
                            //     ], 200);

                            // } else {

                                return response([
                                    'code' => '005',
                                    'message' => 'Erreur lors de l\'operation',
                                    'data' => null
                                ], 201);
                                
                            // }

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

                }else {

                    return response([
                        'code' => '005',
                        'message' => 'Vous n\'avez pas le droit d\'effectue cette operation',
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

        // foreach ($ets as $ets) {

        //     return response([
        //         'code' => '200',
        //         'message' => 'success',
        //         'data' => $etablissements
        //     ], 200);
            
        // }

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
                'data' => null
            ], 201);

        }

    }



    // Consulter ou afficher un etablissement

    public function Etablissement($id){

        etablissements::find($id)->increment('nombre_visite');

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
                    'etablissements.nombre_visite',
                    'sous_categories.nom_sous_categorie',
                    'categories.nomCategorie',
                    'categories.image',
                    'categories.titre',
                    'arrondissements.libelle_arrondissement', 
                    'villes.libelle_ville', 
                    'departements.libelle_departement',
                    'pays.libelle_pays')->get();

        foreach ($etablissement as $ets) {

            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $ets
            ], 200);
            
        }
        // if ($etablissement) {
                    
        //     return response([
        //         'code' => '200',
        //         'message' => 'success',
        //         'data' => $etablissement
        //     ], 200);

        // } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => null
            ], 201);

        // }
        
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
                            ->orWhere("longitude", "like", "%".$valeur."%" )
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
                                        'etablissements.nombre_visite',
                                        'sous_categories.nom_sous_categorie',
                                        'categories.nomCategorie',
                                        'categories.image',
                                        'categories.titre',
                                        'arrondissements.libelle_arrondissement', 
                                        'villes.libelle_ville', 
                                        'departements.libelle_departement',
                                        'pays.libelle_pays')
                            ->get(); 

        foreach ($data as $datas) {

            $id = $datas->id;

            // etablissements::find($id)->increment('nombre_visite');

            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $data
            ], 200);
            
        }

        return response([
            'code' => '004',
            'message' => 'Aucune information ne correspond a votre recherche',
            'data' => null
        ], 201);
                                    
    }


    // Modifier un etablissement

    public function putEtablissement(Request $request, $id){

        if (Auth::check()) {
            
            // utilisateur actuellement authentifie

            $user = Auth::user();

            $role = $user['role'];

            if ($role == null) {

                return response([
                    'code' => '001',
                    'message' => 'Vous n\'avez aucun role veuillez completer vos informations avant de poursuivre ',
                    'data' => 'null'
                ], 201);

            }else {

                if ($role == 'administrateur') {

                    $identif = etablissements::findOrFail($id);

                    $etablissement = $request->all();

                    $validator = Validator::make($request->all(), [
                        
                        'nom_etablissement'=> 'required|unique:etablissements|max:100|regex:/[^0-9.-]/', 
                        'adresse'=> 'required|unique:etablissements|max:100', 
                        'telephone'=> 'unique:etablissements|max:100|regex:/[^a-zA-Z]/', 
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

                }else {

                    return response([
                        'code' => '005',
                        'message' => 'Vous n\'avez pas le droit d\'effectue cette operation',
                        'data' => 'null'
                    ], 201);

                }

            }
 
        }   

    }



    // Affichage des annonces a partir de l'etablissement

    public function Annonces($id){

        // $annonces = etablissements::find($id)->Annonces;

        $annonces = etablissements::from('etablissements')->where('etablissements.id', '=', $id)
        ->join('annonces_etablissements', 'annonces_etablissements.etablissements_id', '=', 'etablissements.id')
        ->join('annonces', 'annonces_etablissements.annonces_id', '=', 'annonces.id')
        ->join('calendriers', 'annonces.calendriers_id', '=', 'calendriers.id')
        ->join('sous_categories', 'annonces.sous_categories_id', '=', 'sous_categories.id')
        ->join('categories', function($join)
            {
                $join->on('categories.id', '=', 'sous_categories.categories_id');
            })
        ->select(                    
                'annonces.id',
                'annonces.titre',
                'annonces.description',
                'annonces.date',
                'annonces.type',
                'annonces.image_couverture',
                'annonces.lieu',
                'annonces.latitude',
                'annonces.longitude',
                'annonces.etablissement',
                'annonces.nom_etablissement',
                'calendriers.date_evenement',
                'calendriers.label',
                'calendriers.heure_debut',
                'calendriers.heure_fin',
                'annonces.sous_categories_id',
                'sous_categories.nom_sous_categorie',
                'categories.nomCategorie',
                'categories.image',
                'categories.titre',
                // 'etablissements.id',
                    )->get();



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


    // Affichage les utilisateurs a partir de l'etablissement

    public function Utilisateur($id){

        $utilisateur = etablissements::from('etablissements')->where('etablissements.id', '=', $id)
        ->join('utilisateurs', 'etablissements.utilisateurs_id', '=', 'utilisateurs.id')
        ->select(                    
                    'etablissements.utilisateurs_id',
                    'utilisateurs.login',
                    'utilisateurs.email',
                    'utilisateurs.photo',
                    'utilisateurs.role',
                    'utilisateurs.date_creation',
                    'utilisateurs.nomAdministrateur',
                    'utilisateurs.prenomAdministrateur',
                    'utilisateurs.telephoneAdministrateur',
                    'etablissements.id',
                    'etablissements.nom_etablissement',
                    )->get();

        if ($utilisateur == true) {
            
            return response([
                'message' => 'success',
                'data' => $utilisateur
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => null
            ], 201);

        }
        
    }




    // Affichage des notes a partir des etablissements

    public function Notes($id){

        // $Notes = etablissements::find($id)->Notes;

        $Notes = etablissements::where('etablissements.id', '=', $id)
        ->join('notes', 'notes.etablissements_id', '=', 'etablissements.id')
        ->join('utilisateurs', 'notes.utilisateurs_id', '=', 'utilisateurs.id')
        // ->join('categories', function($join)
        //     {
        //         $join->on('categories.id', '=', 'sous_categories.categories_id');
        //     })
        ->select(
                'notes.id',
                'notes.commentaire',
                'notes.score',
                'notes.created_at',
                'utilisateurs.login',
                'utilisateurs.email',
                // 'etablissements.id',
                'etablissements.nom_etablissement',)
        ->get();

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

        // $sousCategories = etablissements::find($id);
        // $sousCat = $sousCategories->sousCategories;
        // return $sousCat;

        $sousCat = etablissements::where('etablissements.id', '=', $id)
        ->join('etablissements_sous_categories', 'etablissements_sous_categories.etablissements_id', '=', 'etablissements.id')
        ->join('sous_categories', 'etablissements_sous_categories.sous_categories_id', '=', 'sous_categories.id')
        ->join('categories', function($join)
            {
                $join->on('categories.id', '=', 'sous_categories.categories_id');
            })
        ->select(
                'sous_categories.id',
                'sous_categories.nom_sous_categorie',
                'categories.nomCategorie',
                'categories.image',
                'categories.titre',
                // 'etablissements.id',
                'etablissements.nom_etablissement',)
        ->get();

        if ($sousCat) {
            
            return response([
                'message' => 'success',
                'data' => $sousCat
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => null
            ], 201);

        }

    }



    // Affichage des categories a partir des etablissements

    public function Categories($id){

        $sousCat = etablissements::where('etablissements.id', '=', $id)
        ->join('etablissements_sous_categories', 'etablissements_sous_categories.etablissements_id', '=', 'etablissements.id')
        ->join('sous_categories', 'etablissements_sous_categories.sous_categories_id', '=', 'sous_categories.id')
        ->join('categories', function($join)
            {
                $join->on('categories.id', '=', 'sous_categories.categories_id');
            })
        ->select(
                'categories.id',
                'categories.nomCategorie',
                'categories.image',
                'categories.titre',
                // 'etablissements.id',
                'etablissements.nom_etablissement',)
        ->get();

        if ($sousCat) {
            
            return response([
                'message' => 'success',
                'data' => $sousCat
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => null
            ], 201);

        }

    }



    // Acceder aux images
     
    public function image($fileName){
        
        return response()->download(public_path('/uploads/images/' . $fileName));

    }


    // Afficher les etablissements les plus visite

    public function plusVisiter(){

        $etablissement = etablissements::from('etablissements')->where('etablissements.nombre_visite', '>=', 10)
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
                    'etablissements.nombre_visite',
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

    public function moinsVisiter(){

        $etablissement = etablissements::from('etablissements')->where('etablissements.nombre_visite', '<', 10)
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
                    'etablissements.nombre_visite',
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
    

}
