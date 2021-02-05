<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\etablissements;
use Illuminate\Support\Facades\Validator;

class etablissementsController extends Controller
{
    //
    public function createEtablissement(Request $request){

        $validator = Validator::make($request->all(), [
            
            'nom_etablissement'=> 'required|unique:etablissements|max:100|regex:/[^0-9.-]/', 
            'adresse'=> 'required|unique:etablissements|max:100', 
            'telephone'=> 'required|unique:etablissements|max:100|regex:/[^a-zA-Z]/', 
            'description'=> 'required|unique:etablissements|max:255|regex:/[^0-9.-]/', 
            'heure_ouverture'=> 'required|max:100', 
            'heure_fermeture'=> 'required|max:100', 
            'email'=> 'required|unique:etablissements|max:200|email', 
            'boite_postale'=> 'required|unique:etablissements|max:100', 
            'site_web'=> 'required|unique:etablissements|max:100|regex:/[^0-9.-]/', 
            'logo'=> 'required|unique:etablissements|max:100', 
            'actif'=> 'required', 
            'pays'=> 'required|max:200|regex:/[^0-9.-]/', 
            'departement'=> 'required|regex:/[^0-9.-]/', 
            'ville'=> 'required|max:200|regex:/[^0-9.-]/', 
            'arrondissement'=> 'required|max:200|regex:/[^0-9.]/', 
            'latitude'=> 'required|max:100', 
            'longitude'=> 'required|max:100', 
            'souscategories_id'=> 'required', 
            'utilisateurs_id'=> 'required'
        ]);

        if ($validator->fails()) {

            return response()->json($validator->errors(), 201);
            return $validator->errors();

            // return $erreur = "Erreur : 001, lie au champs de saisie";

        }else {

            return etablissements::create($request->all());
            
        }
    }


    public function Etablissements(){

        return etablissements::all();
    }


    public function Etablissement($id){

        return $etablissement = etablissements::find($id);
    }


    
    public function sousCategorie($id){

        return $etablissements = etablissements::find($id)->sousCategories;

    }

}
