<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\utilisateurs;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class utilisateursController extends Controller
{
    //
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
                    'photo' => 'required|unique:utilisateurs',
                    'actif' => 'required',
                    'nomAdministrateur' => 'required|max:100|regex:/[^0-9.-]/',
                    'prenomAdministrateur' => 'required|max:100|regex:/[^0-9.-]/',
                    'telephoneAdministrateur' => 'required|unique:utilisateurs|regex:/[^a-zA-Z]/',
                ]);

                if ($validator->fails()) {
        
                    // $erreur = $validator->errors();
                    // return response()->json($validator->errors(), 202);
    
                    return $erreur = "Erreur : 001, lie au champs de saisie";
            
                }else {

                    $Utilisateur['password'] = Hash::make($Utilisateur['password']);
    
                    return utilisateurs::create($Utilisateur);
    
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

                    $Utilisateur['password'] = Hash::make($Utilisateur['password']);
    
                    return utilisateurs::create($Utilisateur);
    
                }
            }else{
                return $erreur = 'Erreur : 003, lie au serveur';
            }

        }
            
    }
}
