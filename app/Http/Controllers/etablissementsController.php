<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\etablissements;
use App\Models\sous_categories;
use App\Models\etablissements_sous_categories;
use App\Models\utilisateurs;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class etablissementsController extends Controller
{
    
    //Creer un etablissement

    public function createEtablissement(Request $request){

        if (Auth::check()) {
            
            // utilisateur actuellement authentifie

            $user = Auth::user();

            $idAuth = Auth::id();

            $donnees = utilisateurs::where('utilisateurs.id', '=', $idAuth)->addSelect('id')->first();

            $idUtilisateur = $donnees['id'];

            $role = $user['role'];

            if ($role == 'administrateur') {

                $etablissement = $request->all();

                $validator = Validator::make($etablissement, [
                    
                    'nom_etablissement'=> 'required|unique:etablissements|max:100|regex:/[^0-9.-]/', 
                    'adresse'=> 'required', 
                    'telephone'=> 'required|unique:etablissements|max:100|regex:/[^a-zA-Z]/', 
                    'description'=> 'unique:etablissements|max:255|regex:/[^0-9.-]/', 
                    'heure_ouverture'=> 'required|int', 
                    'heure_fermeture'=> 'required|int', 
                    'email'=> 'required|unique:etablissements|max:200|email', 
                    'boite_postale'=> 'unique:etablissements|max:100',
                    'site_web'=> 'unique:etablissements',
                    'latitude'=> 'required|max:100', 
                    'longitude'=> 'required|max:100', 
                    'arrondissements_id'=> 'required',
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

                    $heure_ouverture = $etablissement['heure_ouverture'];

                    $heure_fermeture = $etablissement['heure_fermeture'];

                    $valeur_ouverture = ($heure_ouverture * 3600);

                    $valeur_fermeture = ($heure_fermeture * 3600);

                    $etablissement['heure_ouverture'] = $valeur_ouverture;

                    $etablissement['heure_fermeture'] = $valeur_fermeture;

                    $etablissement['utilisateurs_id'] = $idUtilisateur;

                    $idSousCat = $etablissement['sous_categories_id'];

                    $result = sous_categories::where('sous_categories.id', '=', $idSousCat)->addSelect('id')->first();

                    $id2 = $result->id;

                    if ($id2) {

                        $ets = etablissements::create($etablissement);

                        $id1 = $ets['id'];

                        $EtsSousCat = etablissements_sous_categories::firstOrCreate([
                            'etablissements_id' => $id1,
                            'sous_categories_id' => $id2,
                        ]);

                        foreach ($ets as $etablissements) {
                
                            return response([
                                'code' => '200',
                                'message' => 'success',
                                'data' => $ets
                            ], 200);
                            
                        }

                        return response([
                            'code' => '005',
                            'message' => 'Erreur lors de l\'operation',
                            'data' => null
                        ], 201);

                    }else {

                        return response([
                            'code' => '004',
                            'message' => 'La sous categorie n\'existe pas',
                            'data' => null
                        ], 201);

                    }

                }

            }else {

                return response([
                    'code' => '005',
                    'message' => 'Acces non autorise',
                    'data' => null
                ], 201);

            }
            
        }

    }


    
    // Ajouter ou modifier le logo d'un etablissement

    public function logoEts(Request $request, $id){


        if (Auth::check()) {

            $user = Auth::user();

            $role = $user['role'];

            $idAuth = Auth::id();

            $donnees = etablissements::where('utilisateurs_id', '=', $idAuth)->addSelect('id')->first();

            $idU = $donnees['id'];

            if ($idAuth == $idU || $role == "administrateur") {

                $imageEts = $request->all();

                $data = etablissements::findOrFail($id);

                $validator = Validator::make($request->all(),[
                    'logo' => 'required|mimes:png,jpg,jpeg'
                ]);
                
                if($validator->fails()) {

                    $erreur = $validator->errors();

                    return response([
                        'code' => '001',
                        'message' => 'erreur lie au champs de saisie',
                        'data' =>  $erreur
                    ], 401);
                }
                
                if ($file = $request->file('logo')) {

                    $fileName = $file->getClientOriginalName();

                    $path = $file->move(public_path('/etablissements/images/'), $fileName);

                    $photoURL = url('/etablissements/images/'.$fileName);

                    $imageEts['logo'] = $fileName;
                
                    $update = $data->update($imageEts);
                    
                    if ($update) {

                        return response([
                            'code' => '200',
                            'message' => 'success',
                            'data' => $data
                        ], 200);

                    } else {
                        
                        return response([
                            'code' => '005',
                            'message' => 'echec lors de la modification',
                            'data' => null
                        ], 200);
                    }
                }else{

                    return response()->json([
                        "code" => '004',
                        "message" => "Verifiez les données envoyées",
                        "data" => $file
                    ], 201);
                    
                }
            }else {

                return response([
                    'code' => '004',
                    'message' => 'Acces non autorise',
                    'data' => null
                ], 201);

            }
            
        }

    }


    
    // Afficher les etablissements

    public function Etablissements(){

        $ets = etablissements::where('etablissements.actif', '=', true)
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
            'arrondissements.libelle_arrondissement', 
            'villes.libelle_ville', 
            'departements.libelle_departement',
            'pays.libelle_pays'
        )->get();

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

        $etablissement = etablissements::from('etablissements')
        ->where('etablissements.id', '=', $id)
        ->where('etablissements.actif', '=', true)
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
            'arrondissements.libelle_arrondissement', 
            'villes.libelle_ville', 
            'departements.libelle_departement',
            'pays.libelle_pays'
        )->get();

        foreach ($etablissement as $ets) {

            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $ets
            ], 200);
            
        }

        return response([
            'code' => '004',
            'message' => 'Identifiant incorrect',
            'data' => null
        ], 201);
        
    }



    // Rechercher un etablissement

    public function rechercheEtablissement($valeur){

        $data = etablissements::where('etablissements.actif', '=', true)
        ->where("nom_etablissement", "like", "%".$valeur."%" )
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
                    'message' => 'Acces non autorise',
                    'data' => null
                ], 201);

            }else {

                if ($role == 'administrateur') {

                    $identif = etablissements::findOrFail($id);

                    $etablissement = $request->all();

                    $validator = Validator::make($request->all(), [
                        
                        'nom_etablissement'=> 'required|max:100|regex:/[^0-9.-]/', 
                        'adresse'=> 'required|max:100', 
                        'telephone'=> 'max:100|regex:/[^a-zA-Z]/', 
                        'description'=> 'required|max:255|regex:/[^0-9.-]/', 
                        'heure_ouverture'=> 'required', 
                        'heure_fermeture'=> 'required', 
                        'email'=> 'required|max:200|email', 
                        'boite_postale'=> 'required|max:100', 
                        'site_web'=> 'required|max:100|regex:/[^0-9.-]/',
                        'latitude'=> 'required|max:100', 
                        'longitude'=> 'required|max:100', 
                        'arrondissements_id'=> 'required', 
                    ]);

                    if ($validator->fails()) {

                        $erreur = $validator->errors();
                        
                        return response([
                            'code' => '001',
                            'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                            'data' => $erreur
                        ], 201);

                    }else {

                        $heure_ouverture = $etablissement['heure_ouverture'];

                        $heure_fermeture = $etablissement['heure_fermeture'];

                        $valeur_ouverture = ($heure_ouverture * 3600);

                        $valeur_fermeture = ($heure_fermeture * 3600);

                        $etablissement['heure_ouverture'] = $valeur_ouverture;

                        $etablissement['heure_fermeture'] = $valeur_fermeture;

                        $etablissement['utilisateurs_id'] = $idUtilisateur;

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
                                'message' => 'Echec lors de l\'operation',
                                'data' => null
                            ], 201);
                            
                        }
                        
                    }

                }else {

                    return response([
                        'code' => '005',
                        'message' => 'Vous n\'avez pas le droit d\'effectue cette operation',
                        'data' => null
                    ], 201);

                }

            }
 
        }   

    }



    // Modifier le nom d'un etablissement

    public function putEtablissementNom(Request $request, $id){

        if (Auth::check()) {
            
            // utilisateur actuellement authentifie

            $user = Auth::user();

            $role = $user['role'];

            if ($role == null) {

                return response([
                    'code' => '001',
                    'message' => 'Acces non autorise',
                    'data' => null
                ], 201);

            }else {

                if ($role == 'administrateur') {

                    $identif = etablissements::findOrFail($id);

                    $etablissement = $request->all();

                    $validator = Validator::make($request->all(), [
                        
                        'nom_etablissement'=> 'required|max:100|regex:/[^0-9.-]/',  
                    ]);

                    if ($validator->fails()) {

                        $erreur = $validator->errors();
                        
                        return response([
                            'code' => '001',
                            'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                            'data' => $erreur
                        ], 201);

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
                                'code' => '005',
                                'message' => 'Echec lors de l\'operation',
                                'data' => null
                            ], 201);
                            
                        }
                        
                    }

                }else {

                    return response([
                        'code' => '005',
                        'message' => 'Vous n\'avez pas le droit d\'effectue cette operation',
                        'data' => null
                    ], 201);

                }

            }
    
        }   

    }
    
    
    
    
    // Modifier l'adresse d'un etablissement

    public function putEtablissementAdresse(Request $request, $id){

        if (Auth::check()) {
            
            // utilisateur actuellement authentifie

            $user = Auth::user();

            $role = $user['role'];

            if ($role == null) {

                return response([
                    'code' => '001',
                    'message' => 'Acces non autorise',
                    'data' => null
                ], 201);

            }else {

                if ($role == 'administrateur') {

                    $identif = etablissements::findOrFail($id);

                    $etablissement = $request->all();

                    $validator = Validator::make($request->all(), [
                        'adresse'=> 'required|max:100', 
                    ]);

                    if ($validator->fails()) {

                        $erreur = $validator->errors();
                        
                        return response([
                            'code' => '001',
                            'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                            'data' => $erreur
                        ], 201);

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
                                'code' => '005',
                                'message' => 'Echec lors de l\'operation',
                                'data' => null
                            ], 201);
                            
                        }
                        
                    }

                }else {

                    return response([
                        'code' => '005',
                        'message' => 'Vous n\'avez pas le droit d\'effectue cette operation',
                        'data' => null
                    ], 201);

                }

            }
    
        }   

    }
    
    
    
    // Modifier le telephone d'un etablissement

        public function putEtablissementTel(Request $request, $id){

            if (Auth::check()) {
                
                // utilisateur actuellement authentifie

                $user = Auth::user();

                $role = $user['role'];

                if ($role == null) {

                    return response([
                        'code' => '001',
                        'message' => 'Acces non autorise',
                        'data' => null
                    ], 201);

                }else {

                    if ($role == 'administrateur') {

                        $identif = etablissements::findOrFail($id);

                        $etablissement = $request->all();

                        $validator = Validator::make($request->all(), [ 
                            'telephone'=> 'max:100|regex:/[^a-zA-Z]/' 
                        ]);

                        if ($validator->fails()) {

                            $erreur = $validator->errors();
                            
                            return response([
                                'code' => '001',
                                'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                                'data' => $erreur
                            ], 201);

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
                                    'code' => '005',
                                    'message' => 'Echec lors de l\'operation',
                                    'data' => null
                                ], 201);
                                
                            }
                            
                        }

                    }else {

                        return response([
                            'code' => '005',
                            'message' => 'Vous n\'avez pas le droit d\'effectue cette operation',
                            'data' => null
                        ], 201);

                    }

                }
        
            }   

        }
    
    
    // Modifier la description d'un etablissement

    public function putEtablissementDescription(Request $request, $id){

        if (Auth::check()) {
            
            // utilisateur actuellement authentifie

            $user = Auth::user();

            $role = $user['role'];

            if ($role == null) {

                return response([
                    'code' => '001',
                    'message' => 'Acces non autorise',
                    'data' => null
                ], 201);

            }else {

                if ($role == 'administrateur') {

                    $identif = etablissements::findOrFail($id);

                    $etablissement = $request->all();

                    $validator = Validator::make($request->all(), [ 
                        'description'=> 'required|max:255|regex:/[^0-9.-]/', 
                    ]);

                    if ($validator->fails()) {

                        $erreur = $validator->errors();
                        
                        return response([
                            'code' => '001',
                            'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                            'data' => $erreur
                        ], 201);

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
                                'code' => '005',
                                'message' => 'Echec lors de l\'operation',
                                'data' => null
                            ], 201);
                            
                        }
                        
                    }

                }else {

                    return response([
                        'code' => '005',
                        'message' => 'Vous n\'avez pas le droit d\'effectue cette operation',
                        'data' => null
                    ], 201);

                }

            }
    
        }   

    }
    

    // Modifier l'heure d'ouverture et fermeture d'un etablissement

    public function putEtablissementHeure(Request $request, $id){

        if (Auth::check()) {
            
            // utilisateur actuellement authentifie

            $user = Auth::user();

            $role = $user['role'];

            if ($role == null) {

                return response([
                    'code' => '001',
                    'message' => 'Acces non autorise',
                    'data' => null
                ], 201);

            }else {

                if ($role == 'administrateur') {

                    $identif = etablissements::findOrFail($id);

                    $etablissement = $request->all();

                    $validator = Validator::make($request->all(), [ 
                        'heure_ouverture'=> 'required', 
                        'heure_fermeture'=> 'required'
                    ]);

                    if ($validator->fails()) {

                        $erreur = $validator->errors();
                        
                        return response([
                            'code' => '001',
                            'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                            'data' => $erreur
                        ], 201);

                    }else {

                        $heure_ouverture = $etablissement['heure_ouverture'];

                        $heure_fermeture = $etablissement['heure_fermeture'];

                        $valeur_ouverture = ($heure_ouverture * 3600);

                        $valeur_fermeture = ($heure_fermeture * 3600);

                        $etablissement['heure_ouverture'] = $valeur_ouverture;

                        $etablissement['heure_fermeture'] = $valeur_fermeture;

                        $etablissement['utilisateurs_id'] = $idUtilisateur;

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
                                'message' => 'Echec lors de l\'operation',
                                'data' => null
                            ], 201);
                            
                        }
                        
                    }

                }else {

                    return response([
                        'code' => '005',
                        'message' => 'Vous n\'avez pas le droit d\'effectue cette operation',
                        'data' => null
                    ], 201);

                }

            }
    
        }   

    }



    // Modifier l'email d'un etablissement

    public function putEtablissementEmail(Request $request, $id){

        if (Auth::check()) {
            
            // utilisateur actuellement authentifie

            $user = Auth::user();

            $role = $user['role'];

            if ($role == null) {

                return response([
                    'code' => '001',
                    'message' => 'Acces non autorise',
                    'data' => null
                ], 201);

            }else {

                if ($role == 'administrateur') {

                    $identif = etablissements::findOrFail($id);

                    $etablissement = $request->all();

                    $validator = Validator::make($request->all(), [ 
                        'email'=> 'required|max:200|email', 
                    ]);

                    if ($validator->fails()) {

                        $erreur = $validator->errors();
                        
                        return response([
                            'code' => '001',
                            'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                            'data' => $erreur
                        ], 201);

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
                                'code' => '005',
                                'message' => 'Echec lors de l\'operation',
                                'data' => null
                            ], 201);
                            
                        }
                        
                    }

                }else {

                    return response([
                        'code' => '005',
                        'message' => 'Vous n\'avez pas le droit d\'effectue cette operation',
                        'data' => null
                    ], 201);

                }

            }
    
        }   

    }



    // Modifier la boite postale d'un etablissement

    public function putEtablissementBoitePostale(Request $request, $id){

        if (Auth::check()) {
            
            // utilisateur actuellement authentifie

            $user = Auth::user();

            $role = $user['role'];

            if ($role == null) {

                return response([
                    'code' => '001',
                    'message' => 'Acces non autorise',
                    'data' => null
                ], 201);

            }else {

                if ($role == 'administrateur') {

                    $identif = etablissements::findOrFail($id);

                    $etablissement = $request->all();

                    $validator = Validator::make($request->all(), [ 
                        'boite_postale'=> 'required|max:100', 
                    ]);

                    if ($validator->fails()) {

                        $erreur = $validator->errors();
                        
                        return response([
                            'code' => '001',
                            'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                            'data' => $erreur
                        ], 201);

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
                                'code' => '005',
                                'message' => 'Echec lors de l\'operation',
                                'data' => null
                            ], 201);
                            
                        }
                        
                    }

                }else {

                    return response([
                        'code' => '005',
                        'message' => 'Vous n\'avez pas le droit d\'effectue cette operation',
                        'data' => null
                    ], 201);

                }

            }
    
        }   

    }



    // Modifier le site web d'un etablissement

    public function putEtablissementSiteWeb(Request $request, $id){

        if (Auth::check()) {
            
            // utilisateur actuellement authentifie

            $user = Auth::user();

            $role = $user['role'];

            if ($role == null) {

                return response([
                    'code' => '001',
                    'message' => 'Acces non autorise',
                    'data' => null
                ], 201);

            }else {

                if ($role == 'administrateur') {

                    $identif = etablissements::findOrFail($id);

                    $etablissement = $request->all();

                    $validator = Validator::make($request->all(), [
                        'site_web'=> 'required|max:100|regex:/[^0-9.-]/',
                    ]);

                    if ($validator->fails()) {

                        $erreur = $validator->errors();
                        
                        return response([
                            'code' => '001',
                            'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                            'data' => $erreur
                        ], 201);

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
                                'code' => '005',
                                'message' => 'Echec lors de l\'operation',
                                'data' => null
                            ], 201);
                            
                        }
                        
                    }

                }else {

                    return response([
                        'code' => '005',
                        'message' => 'Vous n\'avez pas le droit d\'effectue cette operation',
                        'data' => null
                    ], 201);

                }

            }
    
        }   

    }



    // Modifier la latitude et la longitude d'un etablissement

    public function putEtablissementLatiLong(Request $request, $id){

        if (Auth::check()) {
            
            // utilisateur actuellement authentifie

            $user = Auth::user();

            $role = $user['role'];

            if ($role == null) {

                return response([
                    'code' => '001',
                    'message' => 'Acces non autorise',
                    'data' => null
                ], 201);

            }else {

                if ($role == 'administrateur') {

                    $identif = etablissements::findOrFail($id);

                    $etablissement = $request->all();

                    $validator = Validator::make($request->all(), [
                        'latitude'=> 'required|max:100', 
                        'longitude'=> 'required|max:100', 
                    ]);

                    if ($validator->fails()) {

                        $erreur = $validator->errors();
                        
                        return response([
                            'code' => '001',
                            'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                            'data' => $erreur
                        ], 201);

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
                                'code' => '005',
                                'message' => 'Echec lors de l\'operation',
                                'data' => null
                            ], 201);
                            
                        }
                        
                    }

                }else {

                    return response([
                        'code' => '005',
                        'message' => 'Vous n\'avez pas le droit d\'effectue cette operation',
                        'data' => null
                    ], 201);

                }

            }
    
        }   

    }



    // Modifier l'arrondissement d'un etablissement

    public function putEtablissementArrondi(Request $request, $id){

        if (Auth::check()) {
            
            // utilisateur actuellement authentifie

            $user = Auth::user();

            $role = $user['role'];

            if ($role == null) {

                return response([
                    'code' => '001',
                    'message' => 'Acces non autorise',
                    'data' => null
                ], 201);

            }else {

                if ($role == 'administrateur') {

                    $identif = etablissements::findOrFail($id);

                    $etablissement = $request->all();

                    $validator = Validator::make($request->all(), [ 
                        'arrondissements_id'=> 'required', 
                    ]);

                    if ($validator->fails()) {

                        $erreur = $validator->errors();
                        
                        return response([
                            'code' => '001',
                            'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                            'data' => $erreur
                        ], 201);

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
                                'code' => '005',
                                'message' => 'Echec lors de l\'operation',
                                'data' => null
                            ], 201);
                            
                        }
                        
                    }

                }else {

                    return response([
                        'code' => '005',
                        'message' => 'Vous n\'avez pas le droit d\'effectue cette operation',
                        'data' => null
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
        ->join('annonces', function($join)
            {
                $join->on('annonces_etablissements.annonces_id', '=', 'annonces.id')
                ->where('annonces.actif', '=', true)
                ->where('annonces.etat', '=', true);
            })
        ->join('calendriers', 'annonces.calendriers_id', '=', 'calendriers.id')
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
            'calendriers.date_evenement',
            'calendriers.label',
            'calendriers.heure_debut',
            'calendriers.heure_fin',
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


    
    // Affichage les utilisateurs a partir de l'etablissement

    public function Utilisateur($id){

        $utilisateur = etablissements::from('etablissements')
        ->where('etablissements.id', '=', $id)
        ->join('utilisateurs', function($join)
        {
            $join->on('etablissements.utilisateurs_id', '=', 'utilisateurs.id')
            ->where('utilisateurs.actif', '=', true);
        })
        ->select(                    
            'utilisateurs.id',
            'utilisateurs.login',
            'utilisateurs.email',
            'utilisateurs.photo',
            'utilisateurs.role',
            'utilisateurs.date_creation',
            'utilisateurs.nomAdministrateur',
            'utilisateurs.prenomAdministrateur',
            'utilisateurs.telephoneAdministrateur'
        )->get();

        if ($utilisateur == true) {
            
            return response([
                'code' => '200',
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
        ->select('notes.id',
                'notes.commentaire',
                'notes.score',
                'notes.created_at',
                'utilisateurs.login')
        ->get();

        if ($Notes) {
            
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $Notes
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => null
            ], 201);

        }
        
    }



    // Affichage des sous categories a partir des etablissements

    public function sousCategories($id){

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
                'categories.nomCategorie')
        ->get();

        if ($sousCat) {
            
            return response([
                'code' => '200',
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
        ->select('categories.id',
                'categories.nomCategorie',
                'categories.image',
                'categories.titre')
        ->get();

        if ($sousCat) {
            
            return response([
                'code' => '200',
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

        $etablissement = etablissements::from('etablissements')
        ->where('etablissements.actif', '=', true)
        ->orderBy('etablissements.nombre_visite', 'desc')->limit(10)
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
            'arrondissements.libelle_arrondissement', 
            'villes.libelle_ville', 
            'departements.libelle_departement',
            'pays.libelle_pays'
        )->get();

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
                'data' => null
            ], 201);

        }                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             

    }


    // supprimer un etablissement
    
    public function deleteEtablissement($id){

        if (Auth::check()) {
            
            $user = Auth::user();

            $role = $user['role'];

            if ($role == "administrateur") {

                $valeur = etablissements::findOrFail($id);

                $valeur['actif'] = 0;

                $modif = $valeur->update();

                return response([
                    'code' => '200',
                    'message' => 'success',
                    'data' => null
                ], 201);

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
