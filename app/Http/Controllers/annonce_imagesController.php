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

            $img = $request->file('image');

            if($request->hasFile('image')){

                $fileName = $request->file('image')->getClientOriginalName();

                $path = $img->move(public_path("/annonceImages/images/"), $fileName);

                $photoURL = url('/annonceImages/images/'.$fileName);

                $imageA['image'] = $fileName;

                $annonceI = annonce_images::create($imageA);

                if ($annonceI) {

                    return response([
                        'code' => '200',
                        'message' => 'success',
                        'data' => $annonceI,
                        'data' => $photoURL
                    ], 200);

                }else {

                    return response([
                        'code' => '005',
                        'message' => 'Echec lors de l\'operation',
                        'data' => 'null'
                    ], 201);

                }

            }else {

                return response([
                    'code' => '001',
                    'message' => 'image nulle',
                    'data' => 'null'
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

            $img = $request->file('image');

            if($request->hasFile('image')){

                // $imageName = rand() . '.' . $request->file('image')->getClientOriginalExtension();

                $fileName = $request->file('image')->getClientOriginalName();

                $path = $img->move(public_path("/annonceImages/images/"), $fileName);

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
                        'data' => 'null'
                    ], 201);

                }

            }else {

                return response([
                    'code' => '001',
                    'message' => 'image nulle',
                    'data' => 'null'
                ], 201);
                
            }
        }

    }


    //Afficher les images annonces

    public function AnnonceImage(){

        $annonce_images = annonce_images::all();

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

        $annonce_images = annonce_images::find($id);

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
                'data' => 'null'
            ], 201);

        }

    }


    // Affichage des annonces a partir de l'image annonce

    public function Annonce($id){

        $annonce = annonce_images::find($id)->Annonces;

        if ($annonce) {
            
            return response([
                'message' => 'success',
                'data' => $annonce
            ], 200);

        } else {

            return response([
                'code' => '004',
                'message' => 'Identifiant incorrect',
                'data' => 'null'
            ], 201);

        }
        
    }


    // Acceder aux images
     
    public function image($fileName){
        
        return response()->download(public_path('/annonceImages/images/' . $fileName));

    }


}
