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

        $imageAnnonce = annonces::from('annonces')->where('annonces.id', '=', $id)
        ->join('annonce_images', 'annonce_images.annonces_id', '=', 'annonces.id')
        ->select(
            'annonce_images.id',
            'annonce_images.image'
        )->get();

        if ($imageAnnonce) {
            
            return response([
                'code' => '200',
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
                $join->on('etablissements.id', '=', 'annonces_etablissements.etablissements_id')
                ->where('etablissements.actif', '=', true);
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
            'categories.nomCategorie',
            'arrondissements.libelle_arrondissement',
            'villes.libelle_ville', 
            'departements.libelle_departement',
            'pays.libelle_pays')
        ->get();

        if ($ets) {
            
            return response([
                '200' => '200',
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

        $utilisateur = annonces::from('annonces')->where('annonces.id', '=', $id)
        ->join('utilisateurs', function($join)
            {
                $join->on('utilisateurs.id', '=', 'annonces.utilisateurs_id')
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

        if ($utilisateur) {
            
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
    
    
    
    
    // Afficher le calendrier a partir de l'annonce

    public function Calendrier($id){

        $calendrier = annonces::where('annonces.id', '=', $id)
        ->where('annonces.actif', '=', true)
        ->join('annonces_etablissements', 'annonces_etablissements.annonces_id', '=', 'annonces.id')
        ->join('calendriers', 'annonces.calendriers_id', '=', 'calendriers.id')
        ->select(
            'calendriers.id',
            'calendriers.label',
            'calendriers.date_evenement',
            'calendriers.heure_debut',
            'calendriers.heure_fin'
        )->get();

        if ($calendrier) {
            
            return response([
                'code' => '200',
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

        $SousCat = annonces::where('annonces.id', '=', $id)
        ->where('annonces.actif', '=', true)
        ->join('sous_categories', 'annonces.sous_categories_id', '=', 'sous_categories.id')
        ->join('categories', function($join)
            {
                $join->on('categories.id', '=', 'sous_categories.categories_id');
            })
        ->select(
            'sous_categories.id',
            'sous_categories.nom_sous_categorie',
            'categories.nomCategorie'
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

        $cat = annonces::where('annonces.id', '=', $id)
        ->where('annonces.actif', '=', true)
        ->join('sous_categories', 'annonces.sous_categories_id', '=', 'sous_categories.id')
        ->join('categories', function($join)
            {
                $join->on('categories.id', '=', 'sous_categories.categories_id');
            })
        ->select(
            'categories.id',
            'categories.nomCategorie',
            'categories.image',
            'categories.titre'
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

        $com = annonces::where('annonces.id', '=', $id)
        ->where('annonces.actif', '=', true)
        ->join('commentaires', 'commentaires.annonces_id', '=', 'annonces.id')
        ->join('utilisateurs', 'utilisateurs.id', '=', 'commentaires.utilisateurs_id')
        ->select('commentaires.id',
            'commentaires.commentaire',
            'commentaires.date_commentaire',
            'utilisateurs.login'
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
            'sous_categories.nom_sous_categorie',
            'categories.nomCategorie'
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

    public function Publier($id){

        if (Auth::check()) {
            
            // utilisateur actuellement authentifie

            $user = Auth::user();

            $idAuth = Auth::id();

            $role = $user['role'];

            $donnees = annonces::find($id);

            $idU = $donnees['utilisateurs_id'];

            if ($idU == $idAuth || $role == 'administrateur') {

                $valeur = annonces::findOrFail($id);

                $valeur['etat'] = 1;

                $modif = $valeur->update();

                return response([
                    'code' => '200',
                    'message' => 'success',
                    'data' => $valeur
                ], 200);
                
            }else {
                
                return response([
                    'code' => '005',
                    'message' => 'Acces non autorise',
                    'data' => $erreur
                ], 201);

            }
            
        }

    }




    // Consulter ou afficher une annonce

    public function getAnnonce($id){

        annonces::find($id)->increment('nombre_visite');

        $annonces = annonces::where('annonces.id', '=', $id)
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
            'sous_categories.nom_sous_categorie',
            'categories.nomCategorie'
        )->get();

        foreach ($annonces as $annonce) {
                    
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $annonce
            ], 200);

        }
            
        return response([
            'code' => '004',
            'message' => 'Identifiant incorrect',
            'data' => null
        ], 201);


    }



    // Rechercher une annonce

    public function rechercheAnnonce($valeur){

        $data = annonces::where('annonces.etat', '=', true )
        ->where('annonces.actif', '=', true)
        ->where("annonces.titre", "like", "%".$valeur."%" )
        ->orWhere("description", "like", "%".$valeur."%" )
        ->orWhere("etat", "like", "%".$valeur."%" )
        ->orWhere("date", "like", "%".$valeur."%" )
        ->orWhere("type", "like", "%".$valeur."%" )
        ->orWhere("image_couverture", "like", "%".$valeur."%" )
        ->orWhere("lieu", "like", "%".$valeur."%" )
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
            'sous_categories.nom_sous_categorie',
            'categories.nomCategorie')
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
                'data' => null
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

                    }elseif ($type == "vente") {

                        $validator = Validator::make($data, [
                            'prix' => 'required|int',
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
                    'etablissements_id' => 'required',
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
                            
                            $nomEts = $data['etablissements_id'];

                            $result = etablissements::where('etablissements.id', '=', $nomEts)->addSelect('id')->first();

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

                    }elseif ($type == "vente") {

                        $validator = Validator::make($data, [
                            'prix' => 'required|int',
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

                    }else {
                        
                        $nomEts = $data['etablissements_id'];

                        $result = etablissements::where('etablissements.id', '=', $nomEts)->addSelect('id')->first();

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

            $user = Auth::user();

            $role = $user['role'];

            $idAuth = Auth::id();

            $donnees = annonces::where('utilisateurs_id', '=', $idAuth)->addSelect('id')->first();

            $idU = $donnees['id'];

            if ($idAuth == $idU || $role == "administrateur") {

                $imageAnnonce = $request->all();

                $validator = Validator::make($request->all(),[
                    'image_couverture' => 'required|mimes:png,jpg,jpeg'
                ]);
                
                if($validator->fails()) {

                    $erreur = $validator->errors();

                    return response([
                        'code' => '001',
                        'message' => 'erreur lie au champs de saisie',
                        'data' =>  $erreur
                    ], 401);
                }
                
                if ($file = $request->file('image_couverture')) {

                    $fileName = $file->getClientOriginalName();

                    $path = $file->move(public_path('/annonces/images/'), $fileName);

                    $photoURL = url('/annonces/images/'.$fileName);

                    $imageAnnonce['image_couverture'] = $fileName;
                
                    $update = $donnees->update($imageAnnonce);
                    
                    if ($update) {

                        return response([
                            'code' => '200',
                            'message' => 'success',
                            'data' => $donnees
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

                    }elseif ($type == "vente") {

                        $validator = Validator::make($data, [
                            'prix' => 'required|int',
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

                    } else {
                        
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
                    'etablissements_id' => 'required',
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
                            
                            $nomEts = $data['etablissements_id'];

                            $result = etablissements::where('etablissements.id', '=', $nomEts)->addSelect('id')->first();

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

                    }elseif ($type == "vente") {

                        $validator = Validator::make($data, [
                            'prix' => 'required|int',
                        ]);

                        if ($validator->fails()) {

                            $erreur = $validator->errors();
                            
                            return response([
                                'code' => '001',
                                'message' => 'L\'un des champs est vide ou ne respecte pas au format',
                                'data' => $erreur
                            ], 201);
        
                        }else {
                            
                            $nomEts = $data['etablissements_id'];

                            $result = etablissements::where('etablissements.id', '=', $nomEts)->addSelect('id')->first();

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
                        
                        $nomEts = $data['etablissements_id'];

                        $result = etablissements::where('etablissements.id', '=', $nomEts)->addSelect('id')->first();

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

            }else {

                return response([
                    'code' => '005',
                    'message' => 'Acces non autorise',
                    'data' => null
                ], 201);

            }

        }

    }
    
    
    
    
    // Afficher les annonces les plus visite

    public function plusVisiter(){

        $annonces = annonces::where('annonces.etat', '=', true)
        ->where('annonces.actif', '=', true)
        ->orderBy('annonces.nombre_visite', 'desc')->limit(10)
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
            'annonces.nombre_visite',
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


    
    // supprimer une annonce
    
    public function deleteAnnonce($id){

        if (Auth::check()) {
            
            $user = Auth::user();

            $idUser = Auth::id();

            $role = $user['role'];

            $donnees = annonces::where('utilisateurs_id', '=', $idUser)->addSelect('id')->first();

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
