<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\departements;
use Illuminate\Support\Facades\Validator;


class departementsController extends Controller
{

    //Afficher tous les departements

    public function Departements(){

        $departements = departements::all();

        if($departements){

            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $departements
            ], 200);

        }else {

            return response([
                'code' => '004',
                'message' => 'Ta table est vide',
                'data' => 'null'
            ], 201);

        }
    }


    // Consulter ou afficher un departement

    public function getDepartement($id){

        $departements = departements::find($id);

        if($departements){
            
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $departements
            ], 200);

        }else {
            
            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
            ], 201);

        }
       
    }


    // Creer un departement

    public function createDepartement(Request $request){

        $validator = Validator::make($request->all(), [
            
            'libelle' => 'required|unique:pays|max:250|regex:/[^0-9.-]/',
            'pays_id' => 'required'
        ]);

        if ($validator->fails()) {

            $erreur = $validator->errors();
            
            return response([
                'code' => '001',
                'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                'data' => $erreur
            ], 201);


        }else {

            $departement = departements::create($request->all());

            if ($departement) {
                
                return response([
                    'code' => '200',
                    'message' => 'success',
                    'data' => $departement
                ], 200);

            }else {
                
                return response([
                    'message' => '005',
                    'message' => 'Echec lors de l\'operation',
                    'data' => 'null'
                ], 201);

            }
            
        }

    }


    // Modifier un departement

    public function putDepartement(Request $request, $id){

        $departement = departements::findOrFail($id);

        if ($departement) {
            
            $validator = Validator::make($request->all(), [
            
                'libelle' => 'required|unique:pays|max:250|regex:/[^0-9.-]/',
                'pays_id' => 'required'
            ]);
    
            if ($validator->fails()) {
    
                $erreur = $validator->errors();
            
                return response([
                    'code' => '001',
                    'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                    'data' => $erreur
                ], 201);
    
            }else {
                
                $modif = $departement->update($request->all());

                if ($modif) {

                    return response([
                        'code' => '200',
                        'message' => 'success',
                        'data' => $departement
                    ], 200);

                }else {
                    
                return response([
                    'code' => '005',
                    'message' => 'Erreur lors de l\'operation',
                    'data' => 'null'
                ], 201);

                }
                
            }
        }else {
            
            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
            ], 201);

        }

    }



    // Affichage des etablissements par rapport au departement

    public function Etablissements($id){

        $ets = departements::from('departements')->where('departements.id', '=', $id)
        ->join('villes', 'villes.departements_id', '=', 'departements.id')
        ->join('arrondissements', 'arrondissements.villes_id', '=', 'villes.id')
        ->join('pays', function($join)
            {
                $join->on('pays.id', '=', 'departements.pays_id');
            })
        ->join('etablissements', function($join)
            {
                $join->on('arrondissements.id', '=', 'etablissements.arrondissements_id');
            })
        ->join('etablissements_sous_categories', function($join)
            {
                $join->on('etablissements.id', '=', 'etablissements_sous_categories.etablissements_id');
            })
        ->join('sous_categories', function($join)
            {
                $join->on('etablissements_sous_categories.sous_categories_id', '=', 'sous_categories.id');
            })
        ->join('categories', function($join)
            {
                $join->on('categories.id', '=', 'sous_categories.categories_id');
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
                    'departements.id', 
                    'departements.libelle_departement',
                    'pays.libelle_pays'
                    )->get();

        if ($ets) {
            
            return response([
                'message' => 'success',
                'data' => $ets
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
            ], 201);

        }
        
    }

    

    // Supprimer un departement
     
    public function deleteDepartement($id){

        $delete = departements::findOrFail($id)->delete();

        if ($delete) {

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
        
    }


}
