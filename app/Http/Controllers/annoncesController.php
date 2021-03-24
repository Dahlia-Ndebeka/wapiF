<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\annonces;
use App\Models\annonces_etablissements;
use App\Models\etablissements;
use App\Models\utilisateurs;
use Illuminate\Support\Facades\Auth;


class annoncesController extends Controller
{
    
    // Affichage des images annonces a partir de l'annonce

    public function imageAnnonce($id){

        // $imageAnnonce = annonces::find($id)->AnnonceImage;

        $imageAnnonce = annonces::from('annonces')->where('annonces.id', '=', $id)
        ->join('annonce_images', 'annonce_images.annonces_id', '=', 'annonces.id')
        ->select(
                    'annonce_images.id',
                    'annonce_images.image',
                    'annonce_images.annonces_id',
                    // 'annonces.id',
                    'annonces.titre',
                    'annonces.description',
                    )->get();

        if ($imageAnnonce == true) {
            
            return response([
                'message' => 'success',
                'data' => $imageAnnonce
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => null
            ], 201);

        }
        
    }




    // Affichage des etablissements a partir de l'annonce

    public function Etablissements($id){

        if (condition) {
            # code...
        } else {
            # code...
        }
        
            
        $ets = annonces::from('annonces')
        ->where('annonces.id', '=', $id)
        ->join('annonces_etablissements', 'annonces_etablissements.annonces_id', '=', 'annonces.id')
        ->join('sous_categories', 'annonces.sous_categories_id', '=', 'sous_categories.id')
        ->join('categories', function($join)
            {
                $join->on('categories.id', '=', 'sous_categories.categories_id');
            })
        ->join('etablissements', function($join)
            {
                $join->on('etablissements.id', '=', 'annonces_etablissements.etablissements_id');
            })
        ->join('utilisateurs', function($join)
            {
                $join->on('utilisateurs.id', '=', 'etablissements.utilisateurs_id');
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
                    'categories.id',
                    'categories.nomCategorie',
                    'categories.image',
                    'categories.titre',
                    'arrondissements.libelle_arrondissement', 
                    'villes.libelle_ville', 
                    'departements.libelle_departement',
                    'pays.libelle_pays',
                    'annonces.id',
                    'annonces.titre',
                    'annonces.description',)->get();

        if ($ets) {
            
            return response([
                'message' => 'success',
                'data' => $ets
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => null
            ], 201);

        }
        
    }




    // Affichage des utilisateurs a partir de l'annonce

    public function Utilisateur($id){

        // $utilisateur = annonces::find($id)->Utilisateurs;

        $utilisateur = annonces::from('annonces')->where('annonces.id', '=', $id)
        ->join('utilisateurs', 'utilisateurs.id', '=', 'annonces.utilisateurs_id')
        ->select(
            'utilisateurs.id',
            'utilisateurs.login',
            'utilisateurs.email',
            'utilisateurs.photo',
            'utilisateurs.role',
            'utilisateurs.date_creation',
            'utilisateurs.nomAdministrateur',
            'utilisateurs.prenomAdministrateur',
            'utilisateurs.telephoneAdministrateur',
            'annonces.id',
            'annonces.titre',
            'annonces.description',
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
    
    
    
    
    // Affichage du calendrier a partir de l'annonce

    public function Calendrier($id){

        // $calendrier = annonces::find($id)->Calendriers;

        $calendrier = annonces::from('annonces')->where('annonces.id', '=', $id)
            ->join('annonces_etablissements', 'annonces_etablissements.annonces_id', '=', 'annonces.id')
            ->join('calendriers', 'annonces.calendriers_id', '=', 'calendriers.id')
            ->join('sous_categories', 'annonces.sous_categories_id', '=', 'sous_categories.id')
            ->join('categories', function($join)
                {
                    $join->on('categories.id', '=', 'sous_categories.categories_id');
                })
            ->join('etablissements', function($join)
                {
                    $join->on('etablissements.id', '=', 'annonces_etablissements.etablissements_id');
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
            ->select(
                'calendriers.id',
                'calendriers.date',
                'calendriers.heure_debut',
                'calendriers.heure_fin',
                'annonces.id',
                'annonces.titre',
                'annonces.description',
                'sous_categories.nom_sous_categorie',
                'categories.nomCategorie',
                'etablissements.nom_etablissement',
                'arrondissements.libelle_arrondissement', 
                'villes.libelle_ville', 
                'departements.libelle_departement',
                'pays.libelle_pays',
            )->get();

        if ($calendrier) {
            
            return response([
                'message' => 'success',
                'data' => $calendrier
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => null
            ], 201);

        }
        
    }



    // Affichage des sous categories a partir de l'annonce

    public function SousCategories($id){

        $SousCat = annonces::from('annonces')->where('annonces.id', '=', $id)
        ->join('sous_categories', 'annonces.sous_categories_id', '=', 'sous_categories.id')
        ->join('categories', function($join)
            {
                $join->on('categories.id', '=', 'sous_categories.categories_id');
            })
        ->select(
            // 'sous_categories.id',
            'annonces.sous_categories_id',
            'sous_categories.nom_sous_categorie',
            // 'categories.id',
            'categories.nomCategorie',
            'categories.image',
            'categories.titre',
            'annonces.id',
            'annonces.titre',
            'annonces.description',
        )->get();

        if ($SousCat) {

            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $SousCat
            ], 200);

        }else {

            return response([
                'code' => '005',
                'message' => 'La table est vide',
                'data' => null
            ], 201);

        }


    }



    // Affichage des categories a partir de l'annonce

    public function Categories($id){

        $cat = annonces::from('annonces')->where('annonces.id', '=', $id)
        ->join('sous_categories', 'annonces.sous_categories_id', '=', 'sous_categories.id')
        ->join('categories', function($join)
            {
                $join->on('categories.id', '=', 'sous_categories.categories_id');
            })
        ->select(
            // 'categories.id',
            'categories.nomCategorie',
            'categories.image',
            'categories.titre',
            'sous_categories.nom_sous_categorie',
            'annonces.id',
            'annonces.titre',
            'annonces.description',
        )->get();

        if ($cat == true) {

            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $cat
            ], 200);

        }else {

            return response([
                'code' => '005',
                'message' => 'La table est vide',
                'data' => null
            ], 201);

        }
        
    }



    // Affichage des commentaires a partir de l'annonce

    public function Commentaires($id){

        $com = annonces::from('annonces')->where('annonces.id', '=', $id)
        ->join('commentaires', 'commentaires.annonces_id', '=', 'annonces.id')
        ->join('utilisateurs', 'utilisateurs.id', '=', 'commentaires.utilisateurs_id')
        ->select(
            // 'annonces.id',
            'annonces.titre',
            'annonces.description',
            'commentaires.id',
            'commentaires.commentaire',
            'commentaires.created_at',
            'utilisateurs.login',
            'utilisateurs.email',
        )->get();

        if ($com == true) {

            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $com
            ], 200);

        }else {

            return response([
                'code' => '005',
                'message' => 'La table est vide',
                'data' => null
            ], 201);

        }
        
    }



    //Afficher toutes les annonces 

    public function Annonces(){

        $annonces = annonces::where('annonces.actif', '=', true)
        ->join('utilisateurs', 'annonces.utilisateurs_id', '=', 'utilisateurs.id')
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
            'annonces.nom_etablissement',
            'annonces.sous_categories_id',
            'sous_categories.nom_sous_categorie',
            'categories.nomCategorie',
            'categories.image',
            'categories.titre'
        )->get();

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
                'data' => null
            ], 201);

        }

    }



    //Afficher les annonces publiees

    public function AnnoncesPublier(){

        $annonces = annonces::where('etat', '=', true)
        ->where('annonces.actif', '=', true)
        ->join('utilisateurs', 'annonces.utilisateurs_id', '=', 'utilisateurs.id')
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
            'annonces.nom_etablissement',
            'annonces.sous_categories_id',
            'sous_categories.nom_sous_categorie',
            'categories.nomCategorie',
            'categories.image',
            'categories.titre'
        )->get();

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
                'data' => null
            ], 201);

        }


    }



    // publier une annonce

    public function Publier(Request $request, $id){

        if (Auth::check()) {
            
            // utilisateur actuellement authentifie
            $user = Auth::user();

            $idAuth = Auth::id();

            $role = $user['role'];

            if ($role == null) {

                return response([
                    'code' => '001',
                    'message' => 'Vous n\'avez aucun role veuillez completer vos informations avant de poursuivre ',
                    'data' => null
                ], 201);

            }else {

                // On verifie l'utilisateur connecte

                if ($role == 'administrateur' || $role == 'mobinaute') {

                    $annonces = annonces::findOrFail($id);

                    if ($annonces == true) {
                        
                        $idU = $annonces['utilisateurs_id'];

                        if ($role == 'mobinaute' && $idU == $idAuth) {
                            
                            $data = $request->all();

                            $validator = Validator::make($data, [
                                'etat'=> 'required',
                            ]);

                            if ($validator->fails()) {
    
                                $erreur = $validator->errors();
                                
                                return response([
                                    'code' => '001',
                                    'message' => 'L\'un des champs est vide ou ne respecte pas au format',
                                    'data' => $erreur
                                ], 201);

                            }else {

                                $publier = $annonces->update($data);

                                return response([
                                    'code' => '200',
                                    'message' => 'succes',
                                    'data' => $annonces
                                ], 200);
                            }
                            
                        } elseif ($role == 'administrateur') {

                            $data = $request->all();

                            $validator = Validator::make($data, [
                                'etat'=> 'required',
                            ]);

                            if ($validator->fails()) {
    
                                $erreur = $validator->errors();
                                
                                return response([
                                    'code' => '001',
                                    'message' => 'L\'un des champs est vide ou ne respecte pas au format',
                                    'data' => $erreur
                                ], 201);

                            }else {

                                $publier = $annonces->update($data);

                                return response([
                                    'code' => '200',
                                    'message' => 'succes',
                                    'data' => $annonces
                                ], 200);
                            }

                        }else {
                            
                            return response([
                                'code' => '005',
                                'message' => 'Vous ne pouvez pas effectuer cette operation. Acces interdit',
                                'data' => $erreur
                            ], 201);

                        }

                    }else {

                        return response([
                            'code' => '004',
                            'message' => 'Desole identifiant incorrect',
                            'data' => $erreur
                        ], 201);

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




    // Consulter ou afficher une annonce

    public function getAnnonce($id){

        $datas = annonces::findOrFail($id);

        $etablissement = $datas['nom_etablissement'];

        if ($etablissement == true) {
            
            $annonces = annonces::where('annonces.id', '=', $id)
            ->where('etat', '=', true)
            ->where('annonces.actif', '=', true)
            ->join('utilisateurs', 'annonces.utilisateurs_id', '=', 'utilisateurs.id')
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
                'annonces.nom_etablissement',
                'annonces.sous_categories_id',
                'sous_categories.nom_sous_categorie',
                'categories.nomCategorie',
                'categories.image',
                'categories.titre',
                'calendriers.id',
                'calendriers.date_evenement',
                'calendriers.heure_debut',
                'calendriers.heure_fin',
            )->get();

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
                    'data' => null
                ], 201);

            }

        } else {
            
            $annonces = annonces::where('annonces.id', '=', $id)
            // ->whereColumn([
            //     ['annonces.actif', '=', true],
            //     ['annonces.etat', '=', true],
            // ])
            ->where('etat', '=', true)
            ->where('annonces.actif', '=', true)
            ->join('utilisateurs', 'annonces.utilisateurs_id', '=', 'utilisateurs.id')
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
                'annonces.sous_categories_id',
                'sous_categories.nom_sous_categorie',
                'categories.nomCategorie',
                'categories.image',
                'categories.titre'
            )->get();

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
                    'data' => null
                ], 201);

            }

        }

    }



    // Rechercher une annonce

    public function rechercheAnnonce($valeur){

        $data = annonces::where('etat', '=', true )
            ->where('annonces.actif', '=', true)
            ->where("annonces.titre", "like", "%".$valeur."%" )
            ->orWhere("description", "like", "%".$valeur."%" )
            ->orWhere("etat", "like", "%".$valeur."%" )
            ->orWhere("date", "like", "%".$valeur."%" )
            ->orWhere("type", "like", "%".$valeur."%" )
            ->orWhere("image_couverture", "like", "%".$valeur."%" )
            ->orWhere("lieu", "like", "%".$valeur."%" )
            ->orWhere("nom_etablissement", "like", "%".$valeur."%" )
            ->join('utilisateurs', 'annonces.utilisateurs_id', '=', 'utilisateurs.id')
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
                'annonces.nom_etablissement',
                'annonces.sous_categories_id',
                'sous_categories.nom_sous_categorie',
                'categories.nomCategorie',
                'categories.image',
                'categories.titre')
            ->get();

        if ($data) {
            
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $data
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Aucun resultat ne correspond a votre recherche',
                'data' => "null"
            ], 201);

        }
                                    
    }


    // Acceder a l'images de couvertures de l'annonce
     
    public function image($fileName){
        
        return response()->download(public_path('/annonces/images/' . $fileName));

    }


    
    // Creer une annonce

    public function createAnnonce(Request $request){

        if (Auth::check()) {
            
            $user = Auth::user();

            $idUser = Auth::id();

            $role = $user['role'];

            if ($role == 'administrateur' || $role == 'mobinaute') {

                $data = $request->all();

                $data['utilisateurs_id'] = $idUser;
                $data['date'] = date_create(now());

                $validator = Validator::make($data, [
                    'titre' => 'required|unique:annonces|max:250|regex:/[^0-9.-]/',
                    'description' => 'required',
                    'type' => 'required',
                    'sous_categories_id' => 'required',
                ]);

                if ($validator->fails()) {

                    $erreur = $validator->errors();
                    
                    return response([
                        'code' => '001',
                        'message' => 'L\'un des champs est vide ou ne respecte pas au format',
                        'data' => $erreur
                    ], 201);

                }else {

                    $type = $data['type'];

                    if ($type == "evenement") {

                        $validator = Validator::make($data, [
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
                                    'data' => null
                                ], 201);

                            }

                        }

                    }

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


    // Creer une annonce liée à un établissement

    public function createAnnonceEtablissement(Request $request){

        if (Auth::check()) {
            
            $user = Auth::user();

            $idUser = Auth::id();

            $role = $user['role'];

            if ($role == 'administrateur') {

                $data = $request->all();

                $data['utilisateurs_id'] = $idUser;

                $data['date'] = date_create(now());

                $validator = Validator::make($data, [
                    'titre' => 'required|unique:annonces|max:250|regex:/[^0-9.-]/',
                    'description' => 'required',
                    'type' => 'required',
                    'nom_etablissement' => 'required',
                    'sous_categories_id' => 'required',
                ]);

                if ($validator->fails()) {

                    $erreur = $validator->errors();
                    
                    return response([
                        'code' => '001',
                        'message' => 'L\'un des champs est vide ou ne respecte pas au format',
                        'data' => $erreur
                    ], 201);

                }else {

                    $type = $data['type'];

                    if ($type == "evenement") {

                        $validator = Validator::make($data, [
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
                            
                            $nomEts = $data['nom_etablissement'];

                            $result = etablissements::where('nom_etablissement', '=', $nomEts)->addSelect('id')->first();

                            if ($result == true) {
                                
                                $annonces = annonces::create($data);

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
                                        'data' => null
                                    ], 201);
            
                                }

                            } else {

                                return response([
                                    'code' => '005',
                                    'message' => "L'etablissement n'existe pas",
                                    'data' => null
                                ], 201);

                            }

                        }

                    }

                    $nomEts = $data['nom_etablissement'];

                    $result = etablissements::where('nom_etablissement', '=', $nomEts)->addSelect('id')->first();

                    if ($result == true) {
                        
                        $annonces = annonces::create($data);

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
                                'data' => null
                            ], 201);
    
                        }

                    } else {

                        return response([
                            'code' => '005',
                            'message' => "L'etablissement n'existe pas",
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



    // Ajouter ou modifier l'image d'une annonce

    public function createImageAnnonce(Request $request, $id)
    {

        if (Auth::check()) {
            
            // utilisateur actuellement authentifie
            $user = Auth::user();

            $idAuth = Auth::id();

            $donnees = utilisateurs::where('utilisateurs.id', '=', $idAuth)->addSelect('id')->first();

            $idU = $donnees['id'];

            if (($idAuth == $idU) || $role == "administrateur") {

                $data = annonces::findOrFail($id);

                $imageAnnonce = $request->all();

                $validator = Validator::make($imageAnnonce, [
            
                    'image_couverture' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
                ]);
        
                if ($validator->fails()) {
        
                    $erreur = $validator->errors();
        
                    return response([
                        'code' => '001',
                        'message' => 'erreur lie au champs de saisie',
                        'data' => $erreur
                    ], 202);
            
                }else {

                    $img = $request->file('image_couverture');

                    if($request->hasFile('image_couverture')){

                        $fileName = $request->file('image_couverture')->getClientOriginalName();

                        $path = $img->move(public_path("/annonces/images/"), $fileName);

                        $photoURL = url('/annonces/images/'.$fileName);

                        $imageAnnonce['image_couverture'] = $fileName;

                        $annonces = $data->update($imageAnnonce);

                        if ($annonces) {

                            return response([
                                'code' => '200',
                                'message' => 'success',
                                'data' => $data
                            ], 200);

                        }else {

                            return response([
                                'code' => '005',
                                'message' => 'Echec lors de l\'operation',
                                'data' => null
                            ], 201);

                        }
        
                    }else {
        
                        return response([
                            'code' => '001',
                            'message' => 'image nulle',
                            'data' => null
                        ], 201);
        
                    }
        
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




    // Modifier une annonce creer par un mobinaute

    public function putAnnonce(Request $request, $id){
        
        if (Auth::check()) {
            
            $user = Auth::user();

            $idUser = Auth::id();

            $role = $user['role'];

            $annonce = annonces::findOrFail($id);

            $donnees = utilisateurs::where('utilisateurs.id', '=', $idUser)->addSelect('id')->first();

            $idU = $donnees['id'];

            if ( ($idUser == $idU) || $role == "administrateur") {

                $data = $request->all();

                $data['utilisateurs_id'] = $idUser;

                $validator = Validator::make($data, [
                    'titre' => 'required|max:250|regex:/[^0-9.-]/',
                    'description' => 'required',
                    'type' => 'required',
                    'sous_categories_id' => 'required',
                ]);

                if ($validator->fails()) {

                    $erreur = $validator->errors();
                    
                    return response([
                        'code' => '001',
                        'message' => 'L\'un des champs est vide ou ne respecte pas au format',
                        'data' => $erreur
                    ], 201);

                }else {

                    $type = $data['type'];

                    if ($type == "evenement") {

                        $validator = Validator::make($data, [
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
                            
                            $annonces = $annonce->update($data);

                            if ($annonces) {

                                return response([
                                    'code' => '200',
                                    'message' => 'success',
                                    'data' => $annonce
                                ], 200);

                            }else {

                                return response([
                                    'code' => '005',
                                    'message' => 'Echec lors de l\'operation',
                                    'data' => null
                                ], 201);

                            }

                        }

                    }

                    $annonces = $annonce->update($data);

                    if ($annonces) {

                        return response([
                            'code' => '200',
                            'message' => 'success',
                            'data' => $annonce
                        ], 200);

                    }else {

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
                    'message' => 'Acces non autorise',
                    'data' => null
                ], 201);

            }

        }
        
    }



    // Modifier une annonce liée à un établissement

    public function putAnnonceEtablissement(Request $request){

        if (Auth::check()) {
            
            $user = Auth::user();

            $idUser = Auth::id();

            $role = $user['role'];

            $annonce = annonces::findOrFail($id);

            $donnees = utilisateurs::where('utilisateurs.id', '=', $idAuth)->addSelect('id')->first();

            $idU = $donnees['id'];

            if ( ($idUser == $idU && $role == "administrateur") || $role == "administrateur") {

                $data = $request->all();

                $data['utilisateurs_id'] = $idUser;

                $validator = Validator::make($data, [
                    'titre' => 'required|unique:annonces|max:250|regex:/[^0-9.-]/',
                    'description' => 'required',
                    'date' => 'required|date',
                    'type' => 'required',
                    'nom_etablissement' => 'required',
                    'sous_categories_id' => 'required',
                ]);

                if ($validator->fails()) {

                    $erreur = $validator->errors();
                    
                    return response([
                        'code' => '001',
                        'message' => 'L\'un des champs est vide ou ne respecte pas au format',
                        'data' => $erreur
                    ], 201);

                }else {

                    $type = $data['type'];

                    if ($type == "evenement") {

                        $validator = Validator::make($data, [
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
                            
                            $nomEts = $data['nom_etablissement'];

                            $result = etablissements::where('nom_etablissement', '=', $nomEts)->addSelect('id')->first();

                            if ($result == true) {
                                
                                $annonces = $annonce->update($data);

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
                                        'data' => null
                                    ], 201);
            
                                }

                            } else {

                                return response([
                                    'code' => '005',
                                    'message' => "L'etablissement n'existe pas",
                                    'data' => null
                                ], 201);

                            }

                        }

                    }

                    $nomEts = $data['nom_etablissement'];

                    $result = etablissements::where('nom_etablissement', '=', $nomEts)->addSelect('id')->first();

                    if ($result == true) {
                        
                        $annonces = $annonce->update($data);

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
                                'data' => null
                            ], 201);
    
                        }

                    } else {

                        return response([
                            'code' => '005',
                            'message' => "L'etablissement n'existe pas",
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


    
    // supprimer une annonce
    
    public function deleteAnnonce($id){

        if (Auth::check()) {
            
            $user = Auth::user();

            $idUser = Auth::id();

            $role = $user['role'];

            $donnees = utilisateurs::where('utilisateurs.id', '=', $idUser)->addSelect('id')->first();

            $idU = $donnees['id'];

            if ( ($idUser == $idU) || $role == "administrateur") {

                $valeur = annonces::findOrFail($id);

                $valeur['actif'] = 0;

                $modif = $valeur->update();

                return response([
                    'code' => '200',
                    'message' => 'succes',
                    'data' => null
                ], 201);

            }

        }

    }

}
