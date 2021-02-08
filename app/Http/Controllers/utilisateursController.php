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

        return Utilisateurs::All();
    }



    //Recherche d'un utilisateurs

    public function getUtilisateur($id){

        $Utilisateur = Utilisateurs::find($id);

        if ($Utilisateur) {

            return $Utilisateur = Utilisateurs::find($id);

        } else {

            return "Utilisateur n'existe pas";
        }
        

    }


    // Affichage des etablissements a partir de l4utilisateur

    public function Etablissements($id){

        return $etablissements = utilisateurs::find($id)->Etablissements;
    }


    //Creation d'un utilisateur 

    public function createUtilisateur(Request $request){

        $Utilisateur = $request->all();
        $role = $Utilisateur['role'];

        if ($role) {

            if ($role == 'administrateur') {

                $validator = Validator::make($request->all(), [
                    
                    'login' => 'required|unique:utilisateurs|max:100|regex:/[^0-9.-]/',
                    'password' => 'required|unique:utilisateurs',
                    'email' => 'required|email|unique:utilisateurs',
                    'role' => 'required|max:50',
                    // 'photo' => 'required|unique:utilisateurs|image|mimes:jpeg,png,jpg,gif,svg',
                    'photo' => 'image|mimes:jpeg,png,jpg,gif,svg',
                    'actif' => 'required',
                    'nomAdministrateur' => 'required|max:100|regex:/[^0-9.-]/',
                    'prenomAdministrateur' => 'required|max:100|regex:/[^0-9.-]/',
                    'telephoneAdministrateur' => 'required|unique:utilisateurs|regex:/[^a-zA-Z]/',
                ]);

                if ($validator->fails()) {
        
                    $erreur = $validator->errors();
                    return response()->json($validator->errors(), 202);
    
                    // return $erreur = "Erreur : 001, lie au champs de saisie";
            
                }else {

                    $img = $request->file('photo');

                    if($request->hasFile('photo')){

                        $imageName = rand(11111, 99999) . '.' . $request->file('photo')->getClientOriginalExtension();

                        $img->move(public_path('/uploads/images', $imageName));

                        $Utilisateur['photo'] = $imageName;

                        $Utilisateur['password'] = Hash::make($Utilisateur['password']);

                        return utilisateurs::create($Utilisateur);

                    }else {

                        $Utilisateur['password'] = Hash::make($Utilisateur['password']);

                        return utilisateurs::create($Utilisateur);

                    }

    
                }

            } elseif($role == 'mobinaute') {

                $validator = Validator::make($request->all(), [
                    'login' => 'required|regex:/[^0-9.-]/',
                    'email' => 'email|required|unique:utilisateurs',
                    'password' => 'required|unique:utilisateurs',
                    'role' => 'required|max:50',
                    'photo' => 'required|unique:utilisateurs',
                    'actif' => 'required',
                    'nomAdministrateur' => 'nullable',
                    'prenomAdministrateur' => 'nullable',
                    'telephoneAdministrateur' => 'nullable'
                ]);

                if ($validator->fails()) {
        
                    // $erreur = $validator->errors();
                    // return response()->json($validator->errors(), 202);
    
                    return $erreur = "Erreur : 001, lie au champs de saisie";
            
                }else {

                    $img = $request->file('photo');

                    if($request->hasFile('photo')){

                        $imageName = rand() . '.' . $request->file('photo')->getClientOriginalExtension();

                        $img->move(public_path('/uploads/images', $imageName));

                        $Utilisateur['photo'] = $imageName;

                        $Utilisateur['password'] = Hash::make($Utilisateur['password']);

                        return utilisateurs::create($Utilisateur);

                    }else {
                        
                        $Utilisateur['password'] = Hash::make($Utilisateur['password']);

                        return utilisateurs::create($Utilisateur);
                    }
    
                }
            }else{
                return $erreur = 'Erreur : 003, lie au serveur';
            }

        }
            
    }


    /** Modifier un utilisateur */ 

    // Modifier tout les champs

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
                    return response()->json($validator->errors(), 202);
    
                    // return $erreur = "Erreur : 001, lie au champs de saisie";
            
                }else {
                    
                    $Utilisateur->update($request->all());
                    return $Utilisateur;
    
                }

            } elseif($role == 'mobinaute') {

                $validator = Validator::make($request->all(), [
                    'login' => 'required|regex:/[^0-9.-]/',
                    'email' => 'email|required|unique:utilisateurs',
                    'role' => 'required|max:50',
                    'actif' => 'required'
                ]);

                if ($validator->fails()) {
        
                    // $erreur = $validator->errors();
                    // return response()->json($validator->errors(), 202);
    
                    return $erreur = "Erreur : 001, lie au champs de saisie";
            
                }else {
    
                    $Utilisateur->update($request->all());
                    return $Utilisateur;
    
                }
            }else{
                return $erreur = 'Erreur : 003, lie au serveur';
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
            return response()->json($validator->errors(), 202);

            // return $erreur = "Erreur : 001, lie au champs de saisie";
    
        }else {

            $Utilisateur = $request->all();

            $Utilisateur['password'] = Hash::make($Utilisateur['password']);
            
            $update = $donnees->update($Utilisateur);

            return response([
                'message' => 'mot de passe modifié',
                'info' => $update 
            ], 200);
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

        }else {
            return response()->json('image nulle');
        }

        $Utilisateur['photo'] = $imageName;

        $update = $donnees->update($Utilisateur);

        return $update;

    }



    // public function imageUtilisateur(Request $request)
    // {
        
    //     $img = $request->file('photo');

    //     if($request->hasFile('photo')){
    //         $imageName = rand() . '.' . $request->file('photo')->getClientOriginalExtension();
    //         $img->move(public_path('/uploads/images', $imageName));
    //         return response()->json($imageName);

    //     }else {
    //         return response()->json('image nulle');
    //     }
   
    // }
  


}