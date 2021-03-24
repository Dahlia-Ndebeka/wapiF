<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\utilisateurs;
use App\Models\personal_access_tokens;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class authentificationsController extends Controller
{

    //Connexion

    public function login(Request $request){

        $utilisateur = utilisateurs::where('email', $request->email)
        ->where('utilisateurs.actif', '=', true)->first();

        if (!$utilisateur || !Hash::check($request->password, $utilisateur->password)) {

            return response([
                'code' => '001',
                'message' => 'Erreur lie au champs de saisie. Mot de passe ou email incoorecte'
            ], 404);
        }

        $token = $utilisateur->createToken('token-name')->plainTextToken;
            
        return response([
            'code' => '200',
            'message' => 'connecté',
            'token' => $token,
            'data' => $utilisateur
        ], 200);
        
    }


    // Deconnexion

    public function logOut(Request $request){

        $request->user()->currentAccessToken()->delete();
        return response([
            'message' => 'deconnectez',
        ], 200);

    }


}
