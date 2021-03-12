<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\villes;
use Illuminate\Support\Facades\Validator;


class villesController extends Controller
{

    //Afficher les villes

    public function Villes(){

        $villes = villes::all();

        if ($villes) {

            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $villes
            ], 200);

        }else {
    
            return response([
                'code' => '004',
                'message' => 'Table est vide',
                'data' => 'null'
            ], 201);

            
        }
    }


    // Consulter ou afficher une ville

    public function getVille($id){

        $ville = villes::find($id);

        if ($ville) {
            
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $ville
            ], 200);
            
        }else {
            
            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
            ], 201);

        }

    }


    // Creer une ville

    public function createVille(Request $request){

        $validator = Validator::make($request->all(), [
            
            'libelle' => 'required|unique:pays|max:250|regex:/[^0-9.-]/',
            'departements_id' => 'required'
        ]);

        if ($validator->fails()) {

            $erreur = $validator->errors();

            return response([
                'code' => '001',
                'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                'data' => $erreur
            ], 201);


        }else {

            $ville = villes::create($request->all());

            if ($ville) {
                
                return response([
                    'code' => '200',
                    'message' => 'success',
                    'data' => $ville
                ], 200);

            }else {
                
                return response([
                    'code' => '005',
                    'message' => 'Echec, lors de l\'operation',
                    'data' => 'null'
                ], 201);

            }
            
        }

    }


    // Modifier une ville

    public function putVille(Request $request, $id){

        $ville = villes::findOrFail($id);

        if ($ville) {
            
            $validator = Validator::make($request->all(), [
            
                'libelle' => 'required|unique:pays|max:250|regex:/[^0-9.-]/',
                'departements_id' => 'required'
            ]);
    
            if ($validator->fails()) {

                $erreur = $validator->errors();
    
                return response([
                    'code' => '001',
                    'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                    'data' => $erreur
                ], 201);

            }else {
                
                $modif = $ville->update($request->all());

                if ($modif) {

                    return response([
                        'code' => '200',
                        'message' => 'success',
                        'data' => $ville
                    ], 200);

                }else {
                    
                    return response([
                        'code' => '005',
                        'message' => 'Echec lors de l\'operation',
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


    // // Affichage des etablissements a partir de la ville

    // public function Etablissements($id){

    //     $etablissements = villes::find($id)->Etablissements;

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


    // Affichage des etablissements par rapport a la ville

    public function Etablissements($id){

        $ets = villes::from('villes')->where('villes.id', '=', $id)
        ->join('departements', 'villes.departements_id', '=', 'departements.id')
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
                    'villes.id', 
                    'villes.libelle_ville', 
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


}
