<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\notes;

class notesController extends Controller
{
    
    //Afficher les notes

    public function Notes(){

        $notes = notes::join('etablissements', 'etablissements.id', '=', 'notes.etablissements_id')
        ->join('utilisateurs', function($join)
        {
            $join->on('notes.utilisateurs_id', '=', 'utilisateurs.id');
        })
        ->select('notes.id',
            'notes.commentaire',
            'notes.score',
            'utilisateurs.login',
            'etablissements.nom_etablissement',
        )->get();

        if ($notes) {

            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $notes
            ], 200);

        }else {

            return response([
                'code' => '005',
                'message' => 'La table est vide',
                'data' => $notes
            ], 201);

        }

    }


    // Consulter ou afficher une note

    public function getNote($id){

        $notes = notes::where('notes.id', '=', $id)
        ->join('etablissements', 'etablissements.id', '=', 'notes.etablissements_id')
        ->join('utilisateurs', function($join)
        {
            $join->on('notes.utilisateurs_id', '=', 'utilisateurs.id');
        })
        ->select('notes.id',
            'notes.commentaire',
            'notes.score',
            'utilisateurs.login',
            'etablissements.nom_etablissement',
        )->get();

        if ($notes) {
            
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $notes
            ], 200);

        }else {
            
            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => null
            ], 201);

        }

    }


    
    // Creer une note

    public function createNote(Request $request){

        if (Auth::check()) {
            
            $user = Auth::user();

            $idUser = Auth::id();

            $role = $user['role'];

            $donnees = utilisateurs::where('utilisateurs.id', '=', $idAuth)->addSelect('id')->first();

            $idU = $donnees['id'];

            if ($idUser == $idU) {

                $validator = Validator::make($request->all(), [
                    'commentaire' => 'required|regex:/[^0-9.-]/',
                    'score' => 'required',
                    'etablissements_id' => 'required'
                ]);
        
                if ($validator->fails()) {
        
                    $erreur = $validator->errors();
                    
                    return response([
                        'code' => '001',
                        'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                        'data' => $erreur
                    ], 201);
        
                }else {

                    $data = $request->all();

                    $data['utilisateurs_id'] = $idU;
        
                    $notes = notes::create($data);
        
                    if ($notes) {
                        
                        return response([
                            'code' => '200',
                            'message' => 'success',
                            'data' => $notes
                        ], 200);
        
                    }else {
                        
                        return response([
                            'code' => '005',
                            'message' => 'Echec lors de l\'opération',
                            'data' => null
                        ], 201);
        
                    }
                    
                }

            }
            
        }

    }


    // Modifier une note

    public function putNote(Request $request, $id){

        if (Auth::check()) {
            
            $user = Auth::user();

            $idUser = Auth::id();

            $role = $user['role'];

            $donnees = utilisateurs::where('utilisateurs.id', '=', $idAuth)->addSelect('id')->first();

            $idU = $donnees['id'];

            if ( ($idUser == $idU) || $role == "administrateur") {

                $identif = notes::findOrFail($id);

                if ($identif) {
                    
                    $validator = Validator::make($request->all(), [
                    
                        'commentaire' => 'required|regex:/[^0-9.-]/',
                        'score' => 'required',
                        'etablissements_id' => 'required'
                    ]);
            
                    if ($validator->fails()) {
            
                        $erreur = $validator->errors();
                    
                        return response([
                            'code' => '001',
                            'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                            'data' => $erreur
                        ], 200);
            
                    }else {

                        $data = $request->all();

                        $data['utilisateurs_id'] = $idU;
                        
                        $modif = $identif->update($data);

                        if ($modif) {

                            return response([
                                'code' => '200',
                                'message' => 'success',
                                'data' => $identif
                            ], 200);

                        }else {

                            return response([
                                'code' => '005',
                                'message' => 'Erreur lors de l\'operation',
                                'data' => null
                            ], 201);

                        }
                        
                    }

                }else {

                    return response([
                        'code' => '004',
                        'message' => 'Identifiant incorrect',
                        'data' => null
                    ], 201);

                }

            }
            
        }

    }


    
    // Affichage des utilisateurs a partir des notes

    public function Utilisateur($id){

        // $Utilisateur = notes::find($id)->Utilisateurs;

        $Utilisateur = notes::where('notes.id', '=', $id)
        ->join('utilisateurs', function($join)
        {
            $join->on('notes.utilisateurs_id', '=', 'utilisateurs.id')
            ->where('utilisateurs.actif', '=', true);
        })
        ->select(                    
            'utilisateurs.id',
            'utilisateurs.login',
            'utilisateurs.email',
            'utilisateurs.photo',
            'utilisateurs.role',
            'utilisateurs.date_creation',
            'utilisateurs.nomAdministrateur',
            'utilisateurs.prenomAdministrateur',
            'utilisateurs.telephoneAdministrateur',
        )->get();

        if ($Utilisateur) {
            
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $Utilisateur
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => null
            ], 201);

        }
        
    }


    // Affichage des etablissements a partir des notes

    public function Etablissements($id){

        $Etablissements = notes::where('notes.id', '=', $id)
        ->join('etablissements', function($join)
            {
                $join->on('etablissements.id', '=', 'notes.etablissements_id')
                    ->where('etablissements.actif', '=', true);
            })
        ->join('etablissements_sous_categories', 'etablissements_sous_categories.etablissements_id', '=', 'etablissements.id')
        ->join('sous_categories', 'etablissements_sous_categories.sous_categories_id', '=', 'sous_categories.id')
        ->join('categories', function($join)
            {
                $join->on('categories.id', '=', 'sous_categories.categories_id');
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
            'pays.libelle_pays',
            'utilisateurs.login',
        )->get();

        if ($Etablissements) {
            
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $Etablissements
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => null
            ], 201);

        }
        
    }



    // Supprimer une note
     
    public function deleteNote($id){

        if (Auth::check()) {
            
            $user = Auth::user();

            $idUser = Auth::id();

            $role = $user['role'];

            $donnees = notes::where('utilisateurs.id', '=', $idAuth)->addSelect('id')->first();

            $idU = $donnees['id'];

            if ( ($idUser == $idU) || $role == "administrateur") {

                $delete = notes::findOrFail($id)->delete();

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
