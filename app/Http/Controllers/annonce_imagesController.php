<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\annonce_images;
use Illuminate\Support\Facades\Validator;


class annonce_imagesController extends Controller
{
    
    // Creer une annonce_image

    public function createImageAnnonce(Request $request){

        $imageA = $request->all();

        $validator = Validator::make($request->all(), [
            
            'image' => 'required|image|mimes:jpeg,png,jpg,svg|max:2048',
            'annonces_id' => 'required'
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

                $path = $file->move(public_path("/annonceImages/images/"), $fileName);

                $photoURL = url('/annonceImages/images/'.$fileName);

                $imageA['image'] = $fileName;

                $annonceI = annonce_images::create($imageA);

                if ($annonceI) {

                    return response([
                        'code' => '200',
                        'message' => 'success',
                        'datas' => $annonceI,
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

    }


    // Modifier annonce_image

    public function putImageAnnonce(Request $request, $id){

        $identif = annonce_images::findOrFail($id);

        $imageA = $request->all();

        return $imageA;

        $validator = Validator::make($request->all(), [
            
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

                $path = $file->move(public_path("/annonceImages/images/"), $fileName);

                $photoURL = url('/annonceImages/images/'.$fileName);

                $imageA['image'] = $imageName;

                $annonceI = $identif->update($imageA);

                if ($annonceI) {

                    return response([
                        'code' => '200',
                        'message' => 'success',
                        'data' => $annonceI
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

    }

    

    //Afficher les images annonces

    public function AnnonceImage(){

        // $annonce_images = annonce_images::all();

        $annonce_images = annonce_images::join('annonces', function($join)
            {
                $join->on('annonces.id', '=', 'annonce_images.annonces_id')
                ->where('annonces.etat', '=', true )
                ->where('annonces.actif', '=', true);
            })
        ->select('annonce_images.id',
            'annonce_images.image',
            'annonces.titre',
            'annonces.description')
        ->get();

        if ($annonce_images) {

            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $annonce_images
            ], 200);

        }else {

            return response([
                'code' => '005',
                'message' => 'La table est vide',
                'data' => $annonce_images
            ], 201);

        }

    }


    // Consulter ou afficher une annonce_images

    public function getAnnonceImage($id){

        // $annonce_images = annonce_images::find($id);

        $annonce_images = annonce_images::where('annonce_images.id', '=', $id )
        ->join('annonces', function($join)
            {
                $join->on('annonces.id', '=', 'annonce_images.annonces_id')
                ->where('annonces.etat', '=', true )
                ->where('annonces.actif', '=', true);
            })
        ->select('annonce_images.id',
            'annonce_images.image',
            'annonces.titre',
            'annonces.description')
        ->get();

        if ($annonce_images) {
            
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $annonce_images
            ], 200);

        }else {
            
            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => null
            ], 201);

        }

    }


    
    // Affichage des annonces a partir de l'image annonce

    public function Annonce($id){

        $annonce = annonce_images::where('annonce_images.id', '=', $id )
        ->join('annonces', function($join)
            {
                $join->on('annonces.id', '=', 'annonce_images.annonces_id')
                ->where('annonces.etat', '=', true )
                ->where('annonces.actif', '=', true);
            })
        ->join('utilisateurs', 'annonces.utilisateurs_id', '=', 'utilisateurs.id')
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
            'annonces.nom_etablissement',
            'sous_categories.nom_sous_categorie',
            'utilisateurs.login',
            'categories.nomCategorie')
        ->get();

        if ($annonce) {
            
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $annonce
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
        
        return response()->download(public_path('/annonceImages/images/' . $fileName));

    }



    // Supprimer une annonce image
     
    public function deleteAnnonceImage($id){

        if (Auth::check()) {
            
            $user = Auth::user();

            $role = $user['role'];

            if ($role == "administrateur" || $role == "mobinaute") {

                $delete = annonce_images::findOrFail($id)->delete();

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
        
    }

}
