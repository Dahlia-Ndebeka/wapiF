<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\arrondissements;

class arrondissementsController extends Controller
{
    
    //Afficher les arrondissements

    public function Arrondissements(){

        $arrondissement = arrondissements::all();

        if ($arrondissement) {

            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $arrondissement
            ], 200);

        }else {

            return response([
                'code' => '005',
                'message' => 'La table est vide',
                'data' => $arrondissement
            ], 201);

        }

    }


    // Consulter ou afficher un arrondissement

    public function getArrondissement($id){

        $arrondissement = arrondissements::find($id);

        if ($arrondissement) {
            
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $arrondissement
            ], 200);

        }else {
            
            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
            ], 201);

        }

    }


    // Creer un arrondissement

    public function createArrondissement(Request $request){

        $validator = Validator::make($request->all(), [
            
            'libelle' => 'required|unique:arrondissements|max:250|regex:/[^0-9.-]/',
            'villes_id' => 'required'
        ]);

        if ($validator->fails()) {

            $erreur = $validator->errors();
            
            return response([
                'code' => '001',
                'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                'data' => $erreur
            ], 201);

        }else {

            $arrondissement = arrondissements::create($request->all());

            if ($arrondissement) {
                
                return response([
                    'code' => '200',
                    'message' => 'success',
                    'data' => $arrondissement
                ], 200);

            }else {
                
                return response([
                    'code' => '005',
                    'message' => 'Echec lors de l\'opération',
                    'data' => 'null'
                ], 201);

            }
            
        }

    }


    // Modifier un arrondissement

    public function putArrondissement(Request $request, $id){

        $arrondissement = arrondissements::findOrFail($id);

        if ($arrondissement) {
            
            $validator = Validator::make($request->all(), [
            
                'libelle' => 'required|unique:pays|max:250|regex:/[^0-9.-]/',
                'villes_id' => 'required'
            ]);
    
            if ($validator->fails()) {
    
                $erreur = $validator->errors();
            
                return response([
                    'code' => '001',
                    'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                    'data' => $erreur
                ], 200);
    
            }else {
                
                $modif = $arrondissement->update($request->all());

                if ($modif) {

                    return response([
                        'code' => '200',
                        'message' => 'success',
                        'data' => $arrondissement
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


    // Affichage des etablissements a partir de l'arrondissement


    public function Etablissements($id){

        $ets = arrondissements::from('arrondissements')->where('arrondissements.id', '=', $id)
        ->join('villes', 'arrondissements.villes_id', '=', 'villes.id')
        ->join('departements', function($join)
            {
                $join->on('departements.id', '=', 'villes.departements_id');
            })
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

    // public function Etablissements($id){

    //     $etablissements = arrondissements::find($id)->Etablissements;

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



    // Supprimer un arrondissement
     
    public function deleteArrondissement($id){

        $delete = arrondissements::findOrFail($id)->delete();

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
