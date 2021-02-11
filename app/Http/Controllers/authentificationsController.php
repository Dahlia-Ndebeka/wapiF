<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\utilisateurs;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class authentificationsController extends Controller
{
    //
    public function login(Request $request){

        $utilisateur = utilisateurs::where('email', $request->email)->first();

        $data = utilisateurs::where('email', $request->all())->first();

        if (!$utilisateur || !Hash::check($request->password, $utilisateur->password)) {

            return response([
                'code' => '001',
                'message' => 'mot de passe ou email incoorecte, lie au champs de saisie'
            ], 404);
        }

        $token = $utilisateur->createToken('token-name')->plainTextToken;

        return response([
            'message' => 'success',
            'token' => $token,
            'data' => $data
        ], 200);
        
    }
}
