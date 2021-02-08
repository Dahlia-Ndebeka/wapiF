<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\categories;
use Illuminate\Support\Facades\Validator;

class categoriesController extends Controller
{
    //
    public function Categories(){

        $categorie = categories::all();

        if ($categorie) {
            
            return 'Message : succes 200';
            return $categorie;

        }else {
            
            return 'Erreur 004, Aucune information existe, la table est vide';
        }

    }


    public function Categorie($id){

        $Categorie = categories::find($id);

        if ($Categorie) {
            
            return 'Message : succes 200';
            return $Categorie;
            
        }else {
            
            return 'Erreur 004, l\'identifiant n\'existe pas';

        }
    }


    public function createCategorie(Request $request){

        $validator = Validator::make($request->all(), [
            
            'nomCategorie' => 'required|unique:categories|max:100|regex:/[^0-9.-]/',
        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors(), 201);
            return $validator->errors();

            // return $erreur = "Erreur : 001, lie au champs de saisie";

        }else {

            $cat = categories::create($request->all());

            if ($cat) {
                
                return 'Message : succes 200';
                return $cat;
            }else {
                
                return 'Erreur 005 : Echec';
            }
            
        }

    }



    public function putCategorie(Request $request, $id)
    {
        //
        $categorie = categories::findOrFail($id);

        if ($categorie) {
            
            $validator = Validator::make($request->all(), [
            
                'nomCategorie' => 'required|unique:categories|max:100|regex:/[^0-9.-]/',
            ]);
    
            if ($validator->fails()) {
    
                // return response()->json($validator->errors(), 201);
                // return $validator->errors();
    
                return $erreur = "Erreur : 001, lie au champs de saisie";
    
            }else {
    
                $modif = $categorie->update($request->all());

                if ($modif) {
                    
                    return 'Message : succes 200';
                    return $modif;

                }else {

                    return 'Erreur 005 : Echec';
                    
                }
                
            }

        }else {
            
            return 'Erreur 004, l\'identifiant n\'existe pas';

        }

        
    }

}
