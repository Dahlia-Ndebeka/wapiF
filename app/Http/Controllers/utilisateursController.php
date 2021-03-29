<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\utilisateurs;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Image as InterventionImage;
use Illuminate\Support\Facades\Auth;



class utilisateursController extends Controller
{
    
    //Affichage de tous les utilisateurs

    public function Utilisateurs(){

        if (Auth::check()) {
            
            $user = Auth::user();

            $role = $user['role'];

            if ($role == 'administrateur') { 
                
                // $utilisateurs = Utilisateurs::All();

                $utilisateurs = utilisateurs::where('utilisateurs.actif', '=', true)->get();
        
                if ($utilisateurs) {

                    return response([
                        'code' => '200',
                        'message' => 'success',
                        'data' => $utilisateurs
                    ], 200);

                } else {

                    return response([
                        'code' => '004',
                        'message' => 'Table vide',
                        'data' => null
                    ], 201);

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


    //Consulter ou afficher un utilisateur

    public function getUtilisateur($id){

        if (Auth::check()) {
            
            $user = Auth::user();

            $role = $user['role'];

            if ($role == 'administrateur') {

                // $Utilisateur = Utilisateurs::find($id);

                $Utilisateur = utilisateurs::where('utilisateurs.id', '=', $id)
                ->where('utilisateurs.actif', '=', true)
                ->get();

                if ($Utilisateur) {

                    return response([
                        'code' => '200',
                        'message' => 'success',
                        'data' => $Utilisateur
                    ], 200);
        
                } else {
        
                    return response([
                        'code' => '004',
                        'message' => 'Identifiant n\'existe pas',
                        'data' => null
                    ], 200);
        
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



    // Affichage des etablissements a partir de l'utilisateur

    public function Etablissements($id){

        if (Auth::check()) {

            $user = Auth::user();

            $idAuth = Auth::id();

            $role = $user['role'];

            $donnees = utilisateurs::findOrFail($id);

            $idU = $donnees['id'];

            if ( $idAuth == $idU || $role == 'administrateur') {

                $etablissements = utilisateurs::from('utilisateurs')
                ->where('utilisateurs.id', '=', $id)
                ->join('etablissements', function($join)
                    {
                        $join->on('etablissements.utilisateurs_id', '=', 'utilisateurs.id')
                            ->where('etablissements.actif', '=', true);
                    })
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
                    'utilisateurs.login'
                )->get();

                if ($etablissements) {
                    
                    return response([
                        'code' => '200',
                        'message' => 'success',
                        'data' => $etablissements
                    ], 200);

                }

            }else {

                return response([
                    'code' => '004',
                    'message' => 'Acces non autorise',
                    'data' => null
                ], 200);

            }
        }
        
    }



    // Affichage des annonces à partir de l'utilisateur

    public function Annonce($id){

        if (Auth::check()) {

            $user = Auth::user();

            $idAuth = Auth::id();

            $role = $user['role'];

            $donnees = utilisateurs::findOrFail($id);

            $idU = $donnees['id'];

            if ( $idAuth == $idU || $role == 'administrateur') {

                $annonces = utilisateurs::from('utilisateurs')
                ->where('utilisateurs.id', '=', $id)
                ->join('annonces', function($join)
                    {
                        $join->on('annonces.utilisateurs_id', '=', 'utilisateurs.id')
                        ->where('annonces.actif', '=', true)
                        ->where('annonces.etat', '=', true);
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
                    'annonces.nom_etablissement',
                    'sous_categories.nom_sous_categorie',
                    'categories.nomCategorie',
                    'utilisateurs.login'
                )->get();

                foreach ($annonces as $annonce) {
                    
                    return response([
                        'code' => '200',
                        'message' => 'success',
                        'data' => $annonces
                    ], 200);

                }

                return response([
                    'code' => '004',
                    'message' => 'Identifiant incorrect',
                    'data' => null
                ], 201);

            }else {

                return response([
                    'code' => '004',
                    'message' => 'Acces non autorise',
                    'data' => null
                ], 201);

            }

        }
        
    }


    // *******Les methodes de creation*******//

                // Creer un mobinaute

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
                'message' => 'erreur lie au champs de saisie',
                'data' => $erreur
            ], 202);
    
        }else {

            $Utilisateur['date_creation'] = date_create(now());

            $Utilisateur['password'] = Hash::make($Utilisateur['password']);

            $Utilisateur['role'] = 'mobinaute';

            $util = utilisateurs::create($Utilisateur);

            if ($util) {

                return response([
                    'code' => '200',
                    'message' => 'success',
                    'data' => $util
                ], 200);

            }else {

                return response([
                    'code' => '005',
                    'message' => 'Echec lors de l\'ajout',
                    'data' => null
                ], 201);

            }
        }
            
    }



            // Creer un administrateur

    public function createUtilisateurAdministrateur(Request $request){

        $Utilisateur = $request->all();

        $validator = Validator::make($request->all(), [
            
            'login' => 'required|unique:utilisateurs|max:100|regex:/[^0-9.-]/',
            'password' => 'required|unique:utilisateurs',
            'email' => 'required|email|unique:utilisateurs',
            'nomAdministrateur' => 'required|max:100|regex:/[^0-9.-]/',
            'prenomAdministrateur' => 'required|max:100|regex:/[^0-9.-]/',
            'telephoneAdministrateur' => 'required|unique:utilisateurs|regex:/[^a-zA-Z]/',
        ]);

        if ($validator->fails()) {

            $erreur = $validator->errors();
            
            return response([
                'code' => '001',
                'message' => 'erreur lie au champs de saisie',
                'data' => $erreur
            ], 202);
    
        }else {

            $Utilisateur['date_creation'] = date_create(now());

            $Utilisateur['password'] = Hash::make($Utilisateur['password']);

            $Utilisateur['password'] = 'administrateur';

            $util = utilisateurs::create($Utilisateur);

            if ($util) {

                return response([
                    'code' => '200',
                    'message' => 'success',
                    'data' => $util
                ], 200);

            }else {

                return response([
                    'code' => '005',
                    'message' => 'Echec lors de l\'ajout',
                    'data' => null
                ], 201);

            }
        }
            
    }



    /******* Modifier un utilisateur ******/ 


    // Modifier tous le mot de passe d'un mobinaute (partie mobile)

    public function putUtilisateurMobile(Request $request, $id){

        if (Auth::check()) {
            
            // utilisateur actuellement authentifie

            $user = Auth::user();

            $idAuth = Auth::id();

            $role = $user['role'];

            $donnees = utilisateurs::findOrFail($id);

            $idU = $donnees['id'];

            if ( $idAuth == $idU || $role == 'administrateur') {

                $validator = Validator::make($request->all(), [

                    'password' => 'required|unique:utilisateurs',
                    'oldPassword' => 'required',
                    
                ]);
        
                if ($validator->fails()) {
        
                    $erreur = $validator->errors();
        
                    return response([
                        'code' => '001',
                        'message' => $erreur,
                        'data' => 'erreur lie au champs de saisie' 
                    ], 202);
            
                }else {

                    $Utilisateur = $request->all();

                    if (!Hash::check($request->oldPassword, $donnees->password)) {

                        return response([
                            'code' => '001',
                            'message' => 'oldPassword incorrect',
                            'data' => null
                        ], 404);

                    }else {
                                
                        $Utilisateur['password'] = Hash::make($Utilisateur['password']);
                        
                        $update = $donnees->update($Utilisateur);
            
                        if ($update) {
                            
                            return response([
                                'code' => '200',
                                'message' => 'mot de passe modifié',
                                'data' => $donnees 
                            ], 200);
            
                        } else {
                            
                            return response([
                                'code' => '005',
                                'message' => 'echec lors de la modification',
                                'data' => null
                            ], 200);
            
                        }

                    }
                    
                }
    
            } else{

                return response([
                    'code' => '004',
                    'message' => 'Acces non autorise',
                    'data' => null
                ], 200);
            }
    
        }else {

            return response([
                'code' => '005',
                'message' => 'Veuillez vous connecter',
                'data' => null
            ], 200);

        }

    }


    // Modifier le login d'un mobinaute (partie mobile)

    public function putLoginMobile(Request $request, $id){

        if (Auth::check()) {
            
            // utilisateur actuellement authentifie

            $user = Auth::user();

            $idAuth = Auth::id();

            $role = $user['role'];

            $donnees = utilisateurs::findOrFail($id);

            $idU = $donnees['id'];

            if ( $idAuth == $idU) {

                $validator = Validator::make($request->all(), [

                    'login' => 'required|unique:utilisateurs'
                    
                ]);
        
                if ($validator->fails()) {
        
                    $erreur = $validator->errors();
        
                    return response([
                        'code' => '001',
                        'message' => 'erreur lie au champs de saisie',
                        'data' =>  $erreur
                    ], 202);
            
                }else {

                    $Utilisateur = $request->all();
                    
                    $update = $donnees->update($Utilisateur);
        
                    if ($update) {
                        
                        return response([
                            'code' => '200',
                            'message' => 'succes',
                            'data' => $donnees 
                        ], 200);
        
                    } else {
                        
                        return response([
                            'code' => '005',
                            'message' => 'echec lors de la modification',
                            'data' => null 
                        ], 200);
        
                    }
                    
                }
    
            } else{

                return response([
                    'code' => '004',
                    'message' => 'Acces non autorise',
                    'data' => null 
                ], 200);
            }
            
        }else {

            return response([
                'code' => '005',
                'message' => 'Veuillez vous connecter',
                'data' => null 
            ], 200);

        }

    }



    // Modifier les champs d'un administrateur

    public function putUtilisateur(Request $request, $id){

        if (Auth::check()) {

            $user = Auth::user();

            $idAuth = Auth::id();

            $role = $user['role'];

            $Utilisateur = utilisateurs::findOrFail($id);

            $idU = $Utilisateur['id'];

            if ($role == true && $role == 'administrateur') {

                if ($idAuth == $idU) {

                    $validator = Validator::make($request->all(), [
                        
                        'login' => 'required|unique:utilisateurs|max:100|regex:/[^0-9.-]/',
                        'email' => 'required|email|unique:utilisateurs',
                        'nomAdministrateur' => 'required|max:100|regex:/[^0-9.-]/',
                        'prenomAdministrateur' => 'required|max:100|regex:/[^0-9.-]/',
                        'telephoneAdministrateur' => 'required|unique:utilisateurs|regex:/[^a-zA-Z]/',
                    ]);
        
                    if ($validator->fails()) {
            
                        $erreur = $validator->errors();
        
                        return response([
                            'code' => '001',
                            'message' => 'erreur lie au champs de saisie',
                            'data' => $erreur
                        ], 202);
                
                    }else {
                        
                        $util = $Utilisateur->update($request->all());
        
                        if ($util) {
        
                            return response([
                                'code' => '200',
                                'message' => 'success',
                                'data' => $Utilisateur
                            ], 200);
        
                        }else {
        
                            return response([
                                'code' => '005',
                                'message' => 'Echec lors de la modification',
                                'data' => null
                            ], 201);
        
                        }       
                    }

                }else {
                    
                    return response([
                        'code' => '001',
                        'message' => 'Acces non autorise, Vous netes pas le proprietaire du compte',
                        'data' => null
                    ], 201);
                    
                }

            }else {

                return response([
                    'code' => '001',
                    'message' => 'Acces non autorise',
                    'data' => null
                ], 201);

            }

        }

    }


    
    // Modifier le mot de passe de l'administrateur

    public function putPassword(Request $request, $id){

        if (Auth::check()) {
            
            // utilisateur actuellement authentifie

            $user = Auth::user();

            $idAuth = Auth::id();

            $role = $user['role'];

            $donnees = utilisateurs::findOrFail($id);

            $idU = $donnees['id'];

            if ( ($idAuth == $idU && $role == 'administrateur') || $role == 'administrateur') {

                $validator = Validator::make($request->all(), [

                    'password' => 'required|unique:utilisateurs',
                    
                ]);
        
                if ($validator->fails()) {
        
                    $erreur = $validator->errors();
        
                    return response([
                        'code' => '001',
                        'message' => 'erreur lie au champs de saisie',
                        'data' =>  $erreur
                    ], 202);
            
                }else {
        
                    $Utilisateur = $request->all();
        
                    $Utilisateur['password'] = Hash::make($Utilisateur['password']);
                    
                    $update = $donnees->update($Utilisateur);
        
                    if ($update) {
                        
                        return response([
                            'code' => '200',
                            'message' => 'mot de passe modifié',
                            'data' => $donnees 
                        ], 200);
        
                    } else {
                        
                        return response([
                            'code' => '005',
                            'message' => 'echec lors de la modification',
                            'data' => null 
                        ], 200);
        
                    }
                    
                }
    
            } else{

                return response([
                    'code' => '004',
                    'message' => 'Acces non autorise',
                    'data' => null 
                ], 200);
            }
            
        }

    }



    //Ajouter ou Modifier la photo d'utilisateur(Mobinaute et administrateur)
    
    public function putImage(Request $request, $id){
        if (Auth::check()) {
            $user = Auth::user();
    
            $idAuth = Auth::id();
    
            $donnees = utilisateurs::findOrFail($id);
    
            $idU = $donnees['id'];
            if ($idAuth == $idU) {
                $validator = Validator::make($request->all(),
                ['photo' => 'required|mimes:png,jpg,jpeg']);
                
                if($validator->fails()) {
                    $erreur = $validator->errors();
                    return response([
                        'code' => '001',
                        'message' => 'erreur lie au champs de saisie',
                        'data' =>  $erreur
                    ], 401);
                }
                
                if ($file = $request->file('photo')) {
                    $fileName = $file->getClientOriginalName();
                    $path = $file->move(public_path("/utilisateurs/images/"), $fileName);
                    $photoURL = url('/utilisateurs/images/'.$fileName);
                    $Utilisateur['photo'] = $fileName;
                
                    $update = $donnees->update($Utilisateur);
                    
                    if ($update) {
                        return response([
                            'code' => '200',
                            'message' => 'succes',
                            'data' => $donnees], 
                            200);
                    } else {
                        return response([
                            'code' => '005',
                            'message' => 'echec lors de la modification',
                            'data' => null], 
                            200);
                    }
                }else{
                    return response()->json([
                        "code" => '004',
                        "message" => "Verifiez les données envoyées",
                        "data" => $file]
                        );
                    
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




    // Affichage des commentaires a partir de l'utilisateur

    public function Commentaires($id){

        $commentaires = utilisateurs::from('utilisateurs')
        ->where('utilisateurs.id', '=', $id)
        ->join('commentaires', 'commentaires.utilisateurs_id', '=', 'utilisateurs.id')
        ->join('annonces', function($join)
            {
                $join->on('annonces.id', '=', 'commentaires.annonces_id');
            })
        ->select('commentaires.id',
            'commentaires.commentaire',
            'commentaires.date_commentaire',
            'annonces.titre',
            'utilisateurs.login'
        )->get();

        if ($commentaires) {
            
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $commentaires
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => null
            ], 201);

        }
        
    }



    // Affichage des notes a partir de l'utilisateur

    public function Notes($id){

        // $Notes = utilisateurs::find($id)->Notes;

        $Notes = utilisateurs::where('utilisateurs.id', '=', $id)
        ->join('notes', 'notes.utilisateurs_id', '=', 'utilisateurs.id')
        ->join('etablissements', 'notes.etablissements_id', '=', 'etablissements.id')
        ->select('notes.id',
            'notes.commentaire',
            'notes.score',
            'notes.created_at',
            'etablissements.nom_etablissement',
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


    
    // Rechercher un utilisateur à partir des ces champs

    public function rechercheUtilisateur($valeur){

        if ($valeur == null) {

            return response([
                'code' => '001',
                'message' => 'Valeur entree est incorrecte',
                'data' => null
            ], 201);

        }else {
            
            $user = Auth::user();

            $role = $user['role'];

            if ($role == 'administrateur') {
                
                $data = utilisateurs::where('utilisateurs.actif', '=', true)->where("login", "like", "%".$valeur."%" )
                ->orWhere("email", "like", "%".$valeur."%" )
                ->orWhere("photo", "like", "%".$valeur."%" )
                ->orWhere("role", "like", "%".$valeur."%" )
                ->orWhere("actif", "like", "%".$valeur."%" )
                ->orWhere("date_creation", "like", "%".$valeur."%" )
                ->orWhere("nomAdministrateur", "like", "%".$valeur."%" )
                ->orWhere("prenomAdministrateur", "like", "%".$valeur."%" )
                ->orWhere("telephoneAdministrateur", "like", "%".$valeur."%" )
                ->get(); 

                return response([
                    'code' => '200',
                    'message' => 'success',
                    'data' => $data
                ], 200);

            } else {

                return response([
                    'code' => '004',
                    'message' => 'Acces nom autorise',
                    'data' => null
                ], 201);

            }  

        }                           

    }

    
    // Acceder aux images
     
    public function image($fileName){
        
        return response()->download(public_path('/utilisateurs/images/' . $fileName));

    }


    // supprimer un utilisateur

    public function deleteUtilisateur($id){

        if (Auth::check()) {
            
            $user = Auth::user();
    
            $role = $user['role'];
    
            if ($role == "administrateur") {
    
                $valeur = utilisateurs::findOrFail($id);

                $valeur['actif'] = 0;

                $modif = $valeur->update();
    
                if ($modif) {
    
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