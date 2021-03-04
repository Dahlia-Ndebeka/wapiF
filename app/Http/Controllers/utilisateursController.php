<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\utilisateurs;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Image as InterventionImage;


class utilisateursController extends Controller
{
    
    //Affichage de tous les utilisateurs

    public function Utilisateurs(){

        $utilisateurs = Utilisateurs::All();

        if ($utilisateurs) {

            return response([
                'message' => 'success',
                'data' => $utilisateurs
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Table vide',
                'data' => 'null'
            ], 201);

        }
        
    }


    //Consulter ou afficher un utilisateur

    public function getUtilisateur($id){

        $Utilisateur = Utilisateurs::find($id);

        if ($Utilisateur) {

            return response([
                'message' => 'success',
                'data' => $Utilisateur
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant n\'existe pas',
                'data' => 'null'
            ], 200);

        }   

    }


    // Affichage des etablissements a partir a l'utilisateur

    // public function Etablissements($id){

    //     $etablissements = utilisateurs::find($id)->Etablissements;

    //     if ($etablissements) {
            
    //         return response([
    //             'message' => 'success',
    //             'data' => $etablissements
    //         ], 200);

    //     } else {

    //         return response([
    //             'code' => '004',
    //             'message' => 'Identifiant incorrect',
    //             'data' => 'null'
    //         ], 201);

    //     }
        
    // }



    // Affichage des etablissements a partir a l'utilisateur

    public function Etablissements($id){

        // $etablissements = utilisateurs::find($id)->Etablissements;

        $etablissements = utilisateurs::from('utilisateurs')
        ->where('utilisateurs.id', '=', $id)
        ->join('etablissements', 'etablissements.utilisateurs_id', '=', 'utilisateurs.id')
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
                    'arrondissements.libelle_arrondissement', 
                    'villes.libelle_ville', 
                    'departements.libelle_departement',
                    'pays.libelle_pays',
                    'utilisateurs.login')->get();

        if ($etablissements) {
            
            return response([
                'message' => 'success',
                'data' => $etablissements
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
            ], 201);

        }
        
    }




    // // Affichage des annonces à partir de l'utilisateur

    // public function Annonce($id){

    //     $annonces = utilisateurs::find($id)->Annonces;

    //     if ($annonces) {
            
    //         return response([
    //             'message' => 'success',
    //             'data' => $annonces
    //         ], 200);

    //     } else {

    //         return response([
    //             'code' => '004',
    //             'message' => 'Identifiant incorrect',
    //             'data' => 'null'
    //         ], 201);

    //     }
        
    // }



    // Affichage des annonces à partir de l'utilisateur

    public function Annonce($id){

        $annonces = utilisateurs::from('utilisateurs')
        ->where('utilisateurs.id', '=', $id)
        ->join('annonces', 'annonces.utilisateurs_id', '=', 'utilisateurs.id')
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
                    'annonces.etablissement',
                    'annonces.nom_etablissement',
                    'annonces.etat',
                    'annonces.sous_categories_id',
                    'sous_categories.nom_sous_categorie',
                    'categories.nomCategorie',
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



    // Creer un utilisateur

    public function createUtilisateur(Request $request){

        $Utilisateur = $request->all();

        $validator = Validator::make($request->all(), [
            
            'login' => 'required|unique:utilisateurs|max:100|regex:/[^0-9.-]/',
            'password' => 'required|unique:utilisateurs',
            'email' => 'required|email|unique:utilisateurs',
        ]);

        if ($validator->fails()) {

            $erreur = $validator->errors();
            
            return response([
                'code' => '001',
                'message' => $erreur,
                'info' => 'erreur lie au champs de saisie' 
            ], 202);
    
        }else {

            $Utilisateur['password'] = Hash::make($Utilisateur['password']);

            $util = utilisateurs::create($Utilisateur);

            if ($util) {

                return response([
                    'message' => 'success',
                    'data' => $util
                ], 200);

            }else {

                return response([
                    'code' => '005',
                    'message' => 'Echec lors de l\'ajout',
                    'data' => 'null'
                ], 201);

            }
        }
            
    }



    /**** Modifier un utilisateur ****/ 

    // Modifier tous les champs

    public function putUtilisateur(Request $request, $id){

        $Utilisateur = utilisateurs::findOrFail($id);

        $val1 = $Utilisateur['id'];

            $role = $Utilisateur['role'];

            $identif = utilisateurs::where('id', $request->id)->first();
    
            $val2 = $identif['id'];
    
            if ($val1 == $val2 ) {
                
                if ($role == 'administrateur') {
    
                    $validator = Validator::make($request->all(), [
                        
                        'login' => 'required|unique:utilisateurs|max:100|regex:/[^0-9.-]/',
                        'email' => 'required|email|unique:utilisateurs',
                        'role' => 'required|max:50',
                        'actif' => 'required',
                        'nomAdministrateur' => 'required|max:100|regex:/[^0-9.-]/',
                        'prenomAdministrateur' => 'required|max:100|regex:/[^0-9.-]/',
                        'telephoneAdministrateur' => 'required|unique:utilisateurs|regex:/[^a-zA-Z]/',
                    ]);
        
                    if ($validator->fails()) {
            
                        $erreur = $validator->errors();
        
                        return response([
                            'code' => '001',
                            'message' => $erreur,
                            'info' => 'erreur lie au champs de saisie' 
                        ], 202);
                
                    }else {
                        
                        $util = $Utilisateur->update($request->all());
        
                        if ($util) {
        
                            return response([
                                'message' => 'success',
                                'data' => $Utilisateur
                            ], 200);
        
                        }else {
        
                            return response([
                                'code' => '005',
                                'message' => 'Echec lors de la modification',
                                'data' => 'null'
                            ], 201);
        
                        }       
                    } 
                }else {
                    
                    $validator = Validator::make($request->all(), [
                        'login' => 'required|regex:/[^0-9.-]/',
                        'email' => 'email|required|unique:utilisateurs'
                    ]);
        
                    if ($validator->fails()) {
            
                        $erreur = $validator->errors();
                        
                        return response([
                            'code' => '001',
                            'message' => $erreur,
                            'info' => 'erreur lie au champs de saisie' 
                        ], 202);
                
                    }else {
        
                        $util = $Utilisateur->update($request->all());
                        
                        if ($util) {
        
                            return response([
                                'message' => 'success',
                                'data' => $Utilisateur
                            ], 200);
        
                        }else {
        
                            return response([
                                'code' => '005',
                                'message' => 'Echec lors de la modification',
                                'data' => 'null'
                            ], 201);
        
                        }
        
                    }
                }
    
            }elseif($val1 != $val2) {
                
                return response([
                    'code' => '004',
                    'message' => 'Aucune information ne correspond à cet identifiant',
                    'data' => 'null'
                ], 201);
    
            }else {
                
                return response([
                    'code' => '004',
                    'message' => 'Desole vous n\'avez saisie aucun identifiant',
                    'data' => 'null'
                ], 201);
            }
        
    }


    // Modifier le mot de passe

    public function putPassword(Request $request, $id){

        $donnees = utilisateurs::findOrFail($id);

        $validator = Validator::make($request->all(), [

            'password' => 'required|unique:utilisateurs',
            
        ]);

        if ($validator->fails()) {

            $erreur = $validator->errors();

            return response([
                'code' => '001',
                'message' => $erreur,
                'info' => 'erreur lie au champs de saisie' 
            ], 202);
    
        }else {

            $Utilisateur = $request->all();

            $Utilisateur['password'] = Hash::make($Utilisateur['password']);
            
            $update = $donnees->update($Utilisateur);

            if ($update) {
                
                return response([
                    'message' => 'mot de passe modifié',
                    'info' => $donnees 
                ], 200);

            } else {
                
                return response([
                    'code' => '005',
                    'message' => 'echec lors de la modification',
                    'info' => 'null' 
                ], 200);

            }
            
        }

    }


    // Modifier la photo d'utilisateur

    public function putImage(Request $request, $id)
    {
        $donnees = utilisateurs::findOrFail($id);
    
        $validator = Validator::make($request->all(), [
            
            'photo' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
        ]);

        if ($validator->fails()) {

            $erreur = $validator->errors();

            return response([
                'code' => '001',
                'message' => $erreur,
                'info' => 'erreur lie au champs de saisie' 
            ], 202);
    
        }else {

            $img = $request->file('photo');

            if($request->hasFile('photo')){

                $fileName = $request->file('photo')->getClientOriginalName();

                $path = $img->move(public_path("/utilisateurs/images/"), $fileName);

                $photoURL = url('/utilisateurs/images/'.$fileName);

                $Utilisateur['photo'] = $fileName;

                $update = $donnees->update($Utilisateur);

                if ($update) {
                        
                    return response([
                        'message' => 'mot de passe modifié',
                        'info' => $donnees 
                    ], 200);

                } else {
                    
                    return response([
                        'code' => '005',
                        'message' => 'echec lors de la modification',
                        'info' => 'null' 
                    ], 200);

                }

            }else {

                return response([
                    'code' => '001',
                    'message' => 'image nulle',
                    'data' => 'null'
                ], 201);

            }

        }
        

    }


    // Ajout des autres informations complementaires

    public function addUtilisateur(Request $request, $id)
    {
        $donnees = utilisateurs::findOrFail($id);

        if ($donnees) {
            
            $Utilisateur = $request->all();
        
            $role = $Utilisateur['role'];

            if ($role) {

                if ($role == 'administrateur') {

                    $validator = Validator::make($request->all(), [

                        'role' => 'required|max:50',
                        'actif' => 'required',
                        'nomAdministrateur' => 'required|max:100|regex:/[^0-9.-]/',
                        'prenomAdministrateur' => 'required|max:100|regex:/[^0-9.-]/',
                        'telephoneAdministrateur' => 'required|unique:utilisateurs|regex:/[^a-zA-Z]/',
                    ]);

                    if ($validator->fails()) {
            
                        $erreur = $validator->errors();

                        return response([
                            'code' => '001',
                            'message' => $erreur,
                            'info' => 'erreur lie au champs de saisie' 
                        ], 202);
                
                    }else {
                        
                        $util = $donnees->update($request->all());

                        if ($util) {

                            return response([
                                'message' => 'success',
                                'data' => $donnees
                            ], 200);
        
                        }else {

                            return response([
                                'code' => '005',
                                'message' => 'Echec lors de la modification',
                                'data' => 'null'
                            ], 201);

                        }
                        
                    }

                } elseif($role == 'mobinaute') {

                    $validator = Validator::make($request->all(), [
                        'role' => 'required|max:50',
                        'actif' => 'required'
                    ]);

                    if ($validator->fails()) {
            
                        $erreur = $validator->errors();

                        return response([
                            'code' => '001',
                            'message' => $erreur,
                            'info' => 'erreur lie au champs de saisie' 
                        ], 202);
                
                    }else {
        
                        $util = $donnees->update($request->all());
                        
                        if ($util) {

                            return response([
                                'message' => 'success',
                                'data' => $donnees
                            ], 200);
        
                        }else {

                            return response([
                                'code' => '005',
                                'message' => 'Echec lors de la modification',
                                'data' => 'null'
                            ], 201);

                        }
                    }
                }else{

                    return response([
                        'code' => '001',
                        'message' => 'Echec vous devez indiquer le role de l\'utilisateur pour pouvoir ajouter les champs manquant',
                        'data' => 'null'
                    ], 201);
                    
                }

            }

        } else {

            return response([
                'code' => '004',
                'message' => 'Desole l\'identidiant n\'existe pas',
                'data' => 'null'
            ], 201);
            
        }
         
    }


    // Affichage des commentaires a partir de l'utilisateur

    public function Commentaires($id){

        $commentaires = utilisateurs::from('utilisateurs')
        ->where('utilisateurs.id', '=', $id)
        ->join('commentaires', 'commentaires.utilisateurs_id', '=', 'utilisateurs.id')
        ->join('annonces', function($join)
            {
                $join->on('annonces.id', '=', 'commentaires.annonces_id');
            })
        ->select('commentaires.commentaire',
                    'commentaires.created_at',
                    'annonces.titre',
                    'utilisateurs.login',
                    'utilisateurs.email',

                    )->get();

        if ($commentaires) {
            
            return response([
                'message' => 'success',
                'data' => $commentaires
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
            ], 201);

        }
        
    }


    // Affichage des commentaires a partir de l'utilisateur

    // public function Commentaires($id){

    //     $commentaires = utilisateurs::find($id)->Commentaires;

    //     if ($commentaires) {
            
    //         return response([
    //             'message' => 'success',
    //             'data' => $commentaires
    //         ], 200);

    //     } else {

    //         return response([
    //             'code' => '004',
    //             'message' => 'Identifiant incorrect',
    //             'data' => 'null'
    //         ], 201);

    //     }
        
    // }



    // Affichage des notes a partir de l'utilisateur

    public function Notes($id){

        $Notes = utilisateurs::find($id)->Notes;

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


    // Rechercher un utilisateur à partir des ces champs

    public function rechercheUtilisateur($valeur){

        $data = utilisateurs::where("login", "like", "%".$valeur."%" )
                                    ->orWhere("email", "like", "%".$valeur."%" )
                                    ->orWhere("photo", "like", "%".$valeur."%" )
                                    ->orWhere("role", "like", "%".$valeur."%" )
                                    ->orWhere("actif", "like", "%".$valeur."%" )
                                    ->orWhere("date_creation", "like", "%".$valeur."%" )
                                    ->orWhere("nomAdministrateur", "like", "%".$valeur."%" )
                                    ->orWhere("prenomAdministrateur", "like", "%".$valeur."%" )
                                    ->orWhere("telephoneAdministrateur", "like", "%".$valeur."%" )->get(); 
            
        return response([
            'code' => '200',
            'message' => 'success',
            'data' => $data
        ], 200);
                                    

    }

    // Acceder aux images
     
    public function image($fileName){
        
        return response()->download(public_path('/utilisateurs/images/' . $fileName));

    }


}