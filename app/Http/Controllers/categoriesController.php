<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\categories;
use Illuminate\Support\Facades\Validator;

class categoriesController extends Controller
{
    
    // Afficher les categories

    public function Categories(){

        $categorie = categories::all();

        if ($categorie) {
            
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $categorie
            ], 200);

        }else {

            return response([
                'code' => '004',
                'message' => 'La table est vide',
                'data' => null
            ], 201);

        }

    }


    // Consulter ou afficher une categorie

    public function Categorie($id){

        $categorie = categories::find($id);

        if (!$categorie) {
            
            return response([
                'code' => '004',
                'message' => 'L\'identifiant incorrect',
                'data' => null
            ], 201);
            
        }else {
        
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $categorie
            ], 200);

        }

    }


    // Creer une categorie

    public function createCategorie(Request $request){

        if (Auth::check()) {
            
            $user = Auth::user();

            $role = $user['role'];

            if ($role == 'administrateur') {

                $categorie = $request->all();

                $validator = Validator::make($request->all(), [
                    
                    'nomCategorie' => 'required|unique:categories|max:100|regex:/[^0-9.-]/',
                    'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
                    'titre' => 'required'
                ]);

                if ($validator->fails()) {

                    $erreur = $validator->errors();
                    
                    return response([
                        'code' => '001',
                        'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                        'data' => $erreur
                    ], 200);

                }else {

                    if ($file = $request->file('image')) {

                        $fileName = $file->getClientOriginalName();

                        $path = $file->move(public_path("/categories/images/"), $fileName);

                        $photoURL = url('/categories/images/'.$fileName);

                        $categorie['image'] = $fileName;

                        $cat = categories::create($categorie);

                        if ($cat) {

                            return response([
                                'code' => '200',
                                'message' => 'success',
                                'data' => $cat,
                                'url' => $photoURL
                            ], 200);

                        }else {

                            return response([
                                'code' => '005',
                                'message' => 'Echec lors de l\'operation',
                                'data' => null
                            ], 201);

                        }

                    }else {

                        return response([
                            'code' => '001',
                            'message' => 'image nulle',
                            'data' => null
                        ], 201);
                        
                    }

                }

            }else {

                return response([
                    'code' => '005',
                    'message' => 'Acces non autorise',
                    'data' => null
                ], 201);

            }

        }

    }



    // Modifier une categorie

    public function putCategorie(Request $request, $id)
    {

        if (Auth::check()) {
            
            $user = Auth::user();

            $role = $user['role'];

            if ($role == 'administrateur') {

                $identifiant = categories::findOrFail($id);

                if ($identifiant == null) {

                    $categorie = $request->all();
                    
                    return response([
                        'code' => '004',
                        'message' => 'Identifiant incorrect',
                        'data' => $erreur
                    ], 201);

                } else {

                    $validator = Validator::make($categorie, [
                    
                        'nomCategorie' => 'required|max:100|regex:/[^0-9.-]/',
                        'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',

                    ]);
            
                    if ($validator->fails()) {
            
                        $erreur = $validator->errors();
                    
                        return response([
                            'code' => '001',
                            'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                            'data' => $erreur
                        ], 200);
            
                    }else {

                        if ($file = $request->file('image')) {

                            $fileName = $file->getClientOriginalName();

                            $path = $file->move(public_path("/categories/images/"), $fileName);

                            $photoURL = url('/categories/images/'.$fileName);

                            $categorie['image'] = $fileName;

                            $cat = $identifiant->update($categorie);

                            if ($cat) {

                                return response([
                                    'code' => '200',
                                    'message' => 'success',
                                    'data' => $identifiant,
                                    'url' => $photoURL,
                                ], 200);

                            }else {

                                return response([
                                    'code' => '005',
                                    'message' => 'Erreur 005 : Echec lors de l\'operation',
                                    'data' => null
                                ], 201);
                                
                            }

                        }else {

                            $cat = $identifiant->update($categorie);

                            return response([
                                'code' => '200',
                                'message' => 'succes',
                                'data' => $identifiant
                            ], 201);

                        }
                        
                    }

                }

            }else {

                return response([
                    'code' => '005',
                    'message' => 'Acces non autorise',
                    'data' => null
                ], 201);

            }

        }
        
    }


    

    // Affichage des sous categories a partir des categories

    public function Souscategories($id){

        // $Souscategories = categories::find($id)->sousCategorie;

        $Souscategories = categories::where('categories.id', '=', $id)
        ->join('sous_categories', 'sous_categories.categories_id', '=', 'categories.id')
        ->select('sous_categories.id',
            'sous_categories.nom_sous_categorie',
            'categories.nomCategorie')
        ->get();

        if ($Souscategories) {
            
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $Souscategories
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => null
            ], 201);

        }
        
    }



    // Affichage des etablissements à partir de la categorie

    public function Etablissements($id){

        $cats = categories::from('categories')->where('categories.id', '=', $id)
        ->join('sous_categories', 'sous_categories.categories_id', '=', 'categories.id')
        ->join('etablissements_sous_categories', 'etablissements_sous_categories.sous_categories_id', '=', 'sous_categories.id')
        ->join('etablissements', function($join)
            {
                $join->on('etablissements.id', '=', 'etablissements_sous_categories.etablissements_id')
                ->where('etablissements.actif', '=', true);
            })
        ->join('arrondissements', 'etablissements.arrondissements_id', '=', 'arrondissements.id')
        ->join('villes', function($join)
            {
                $join->on('villes.id', '=', 'arrondissements.villes_id');
            })
        ->join('departements', function($join)
            {
                $join->on('departements.id', '=', 'villes.departements_id');
            })
        ->join('pays', function($join)
            {
                $join->on('pays.id', '=', 'departements.pays_id');
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
            'departements.libelle_departement',
            'pays.libelle_pays')
        ->get();

        if ($cats) {
            
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $cats
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => null
            ], 201);

        }
        
    }


    // Affichage des annonces à partir de la categorie

    public function Annonces($id){

        $cats = categories::from('categories')
        ->where('categories.id', '=', $id)
        ->join('sous_categories', 'categories.id', '=', 'sous_categories.categories_id')
        ->join('annonces', function($join)
            {
                $join->on('sous_categories.id', '=', 'annonces.sous_categories_id')
                ->where('annonces.actif', '=', true)
                ->where('annonces.etat', '=', true);
            })
        ->join('utilisateurs', 'annonces.utilisateurs_id', '=', 'utilisateurs.id')
        ->select('annonces.id',
            'annonces.titre',
            'annonces.description',
            'annonces.date',
            'annonces.type',
            'annonces.image_couverture',
            'annonces.lieu',
            'annonces.latitude',
            'annonces.longitude',
            'sous_categories.nom_sous_categorie',
            'categories.nomCategorie'
        )->get();

        if ($cats) {
            
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $cats
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => null
            ], 201);

        }
        
    }


    // Acceder aux images

    public function image($fileName){
        
        return response()->download(public_path('/categories/images/' . $fileName));

    }


    // Supprimer une categorie
     
    public function deleteCategorie($id){

        if (Auth::check()) {
            
            $user = Auth::user();

            $role = $user['role'];

            if ($role == "administrateur") {

                $delete = categories::findOrFail($id)->delete();

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

            }else{

                return response([
                    'code' => '004',
                    'message' => 'Acces non autorise',
                    'data' => null
                ], 201);
            }
            
        }
        
    }

}
