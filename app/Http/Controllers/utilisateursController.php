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
                'data' => 'Nul'
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
                'data' => 'Nul'
            ], 200);

        }   

    }


    // Affichage des etablissements a partir a l'utilisateur

    public function Etablissements($id){

        $etablissements = utilisateurs::find($id)->Etablissements;

        if ($etablissements) {
            
            return response([
                'message' => 'success',
                'data' => $etablissements
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'Nul'
            ], 201);

        }
        
    }


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
                    'data' => $util
                ], 201);

            }
        }
            
    }


    /** Modifier un utilisateur */ 

    // Modifier tous les champs

    public function putUtilisateur(Request $request, $id){

        $Utilisateur = utilisateurs::findOrFail($id);

        $role = $Utilisateur['role'];

        if ($role) {

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
                            'data' => 'Nul'
                        ], 201);

                    } 
    
                }

            } elseif($role == 'mobinaute') {

                $validator = Validator::make($request->all(), [
                    'login' => 'required|regex:/[^0-9.-]/',
                    'email' => 'email|required|unique:utilisateurs',
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
                            'data' => 'Nul'
                        ], 201);
    
                    }
    
                }
            }else{

                return response([
                    'code' => '004',
                    'message' => 'Echec l\'utilisateur n\'existe pas',
                    'data' => 'Nul'
                ], 200);
                
            }

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
                    'info' => 'Nul' 
                ], 200);

            }
            
        }

    }


    // Modifier la photo d'utilisateur

    public function putImage(Request $request, $id)
    {
        $donnees = utilisateurs::findOrFail($id);

        $img = $request->file('photo');

        if($request->hasFile('photo')){

            $imageName = rand() . '.' . $request->file('photo')->getClientOriginalExtension();

            $img->move(public_path('/uploads/images', $imageName));

            $Utilisateur['photo'] = $imageName;

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
                    'info' => 'Nul' 
                ], 200);

            }

        }else {

            return response([
                'code' => '001',
                'message' => 'image nulle',
                'data' => 'Nul'
            ], 201);

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
                                'data' => 'Nul'
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
                                'data' => 'Nul'
                            ], 201);

                        }
                    }
                }else{

                    return response([
                        'code' => '001',
                        'message' => 'Echec vous devez indiquer le role de l\'utilisateur pour pouvoir ajouter les champs manquant',
                        'data' => 'Nul'
                    ], 201);
                    
                }

            }

        } else {

            return response([
                'code' => '004',
                'message' => 'Desole l\'identidiant n\'existe pas',
                'data' => 'Nul'
            ], 201);
            
        }
         
    }
  


}