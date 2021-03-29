<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\commentaires;
use App\Models\utilisateurs;
use App\Models\annonces;
use Illuminate\Support\Facades\Auth;


class commentairesController extends Controller
{

    //Creer un commentaire

    public function createCommentaire(Request $request){

        if (Auth::check()) {
            
            $user = Auth::user();

            $idUser = Auth::id();

            $donnees = utilisateurs::where('utilisateurs.id', '=', $idUser)->addSelect('id')->first();

            $idU = $donnees['id'];

            if ( $idUser == $idU) {

                $datas = $request->all();

                $validator = Validator::make($datas, [

                    'commentaire' => 'required',
                    'annonces_id' => 'required'

                ]);
                
                if ($validator->fails()) {
        
                    $erreur = $validator->errors();
                    
                    return response([
                        'code' => '001',
                        'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                        'data' => $erreur
                    ], 201);
        
                }else {

                    $datas['utilisateurs_id'] = $idUser;

                    $datas['date_commentaire'] = date_create(now());

                    $commentaires = commentaires::create($datas);
                    
                    if ($commentaires) {
                        
                        return response([
                            'code' => '200',
                            'message' => 'success',
                            'data' => $commentaires
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


    
    // Afficher les commentaires

    public function Commentaires(){

        $commentaires = commentaires::join('utilisateurs', 'utilisateurs.id', '=', 'commentaires.utilisateurs_id')
        ->join('annonces', function($join)
            {
                $join->on('annonces.id', '=', 'commentaires.annonces_id');
            })
        ->join('sous_categories', 'annonces.sous_categories_id', '=', 'sous_categories.id')
        ->join('categories', function($join)
            {
                $join->on('categories.id', '=', 'sous_categories.categories_id');
            })
        ->select('commentaires.id',
            'commentaires.commentaire',
            'commentaires.date_commentaire',
            'annonces.titre',
            'utilisateurs.login',
        )->get();

        if ($commentaires) {

            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $commentaires
            ], 200);

        }else {

            return response([
                'code' => '005',
                'message' => 'La table est vide',
                'data' => $commentaires
            ], 201);

        }

    }


    // Consulter ou afficher un commentaire

    public function getCommentaire($id){

        // $commentaires = commentaires::find($id);

        $commentaires = commentaires::where('commentaires.id', '=', $id)
        ->join('utilisateurs', 'utilisateurs.id', '=', 'commentaires.utilisateurs_id')
        ->join('annonces', function($join)
            {
                $join->on('annonces.id', '=', 'commentaires.annonces_id');
                // ->where('annonces.actif', '=', true)
                // ->where('annonces.etat', '=', true);
            })
        ->join('sous_categories', 'annonces.sous_categories_id', '=', 'sous_categories.id')
        ->join('categories', function($join)
            {
                $join->on('categories.id', '=', 'sous_categories.categories_id');
            })
        ->select('commentaires.id',
            'commentaires.commentaire',
            'commentaires.date_commentaire',
            'annonces.titre',
            'utilisateurs.login',
        )->get();

        if ($commentaires) {

            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $commentaires
            ], 200);

        }else {

            return response([
                'code' => '005',
                'message' => 'La table est vide',
                'data' => $commentaires
            ], 201);

        }
    }


    // Affichage d'utilisateur à partir des commentaires

    public function Utilisateur($id){

        // $utilisateur = commentaires::find($id)->Utilisateurs;

        $utilisateur = commentaires::where('commentaires.id', '=', $id)
        ->join('utilisateurs', function($join)
            {
                $join->on('utilisateurs.id', '=', 'commentaires.utilisateurs_id')
                ->where('utilisateurs.actif', '=', true);
            })
        ->select('utilisateurs.id',
            'utilisateurs.login',
            'utilisateurs.email',
            'utilisateurs.photo',
            'utilisateurs.role',
            'utilisateurs.date_creation',
            'utilisateurs.nomAdministrateur',
            'utilisateurs.prenomAdministrateur',
            'utilisateurs.telephoneAdministrateur',
        )->get();

        if ($utilisateur) {
            
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $utilisateur
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => null
            ], 201);

        }
        
    }


        // Afficher les annonces a partir des commentaires

        public function CommentaireAnnonces(){

            // $commentaires = commentaires::all();
            $commentaires = commentaires::join('utilisateurs', 'utilisateurs.id', '=', 'commentaires.utilisateurs_id')
                ->join('annonces', 'annonces.id', '=', 'commentaires.annonces_id')
                ->join('sous_categories', 'annonces.sous_categories_id', '=', 'sous_categories.id')
                ->join('categories', function($join)
                    {
                        $join->on('categories.id', '=', 'sous_categories.categories_id');
                    })
                ->select('annonces.id',
                    'annonces.titre',
                    'annonces.description',
                    'annonces.date',
                    'annonces.type',
                    'annonces.image_couverture',
                    'annonces.lieu',
                    'annonces.latitude',
                    'annonces.longitude',
                    'annonces.sous_categories_id',
                    'sous_categories.nom_sous_categorie',
                    'categories.nomCategorie',
                )->get();
    
            if ($commentaires) {
    
                return response([
                    'code' => '200',
                    'message' => 'success',
                    'data' => $commentaires
                ], 200);
    
            }else {
    
                return response([
                    'code' => '005',
                    'message' => 'La table est vide',
                    'data' => $commentaires
                ], 201);
    
            }
    
        }


    // Modifier un commentaire

    public function putCommentaire(Request $request, $id){

        if (Auth::check()) {
            
            $user = Auth::user();

            $idUser = Auth::id();

            $role = $user['role'];

            $donnees = commentaires::where('utilisateurs_id', '=', $idAuth)->addSelect('id')->first();

            $idU = $donnees['id'];

            if ( ($idUser == $idU) || $role == "administrateur") {

                $identif = commentaires::findOrFail($id);

                $data = $request->all();

                $validator = Validator::make($request->all(), [
                    
                    'commentaire' => 'required',
                    'annonces_id' => 'required'
                ]);

                if ($validator->fails()) {

                    $erreur = $validator->errors();
                    
                    return response([
                        'code' => '001',
                        'message' => 'L\'un des champs est vide ou ne respecte pas le format',
                        'data' => $erreur
                    ], 201);

                }else {

                    $commentaires = $identif->update($data);

                    if ($commentaires) {
                        
                        return response([
                            'code' => '200',
                            'message' => 'success',
                            'data' => $identif
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


    
    // Supprimer une commentaire
     
    public function deleteCommentaire($id){

        if (Auth::check()) {
            
            $user = Auth::user();

            $idUser = Auth::id();

            $role = $user['role'];

            $donnees = commentaires::where('utilisateurs_id', '=', $idAuth)->addSelect('id')->first();

            $idU = $donnees['id'];

            if ( ($idUser == $idU) || $role == "administrateur") {

                $delete = commentaires::findOrFail($id)->delete();

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
