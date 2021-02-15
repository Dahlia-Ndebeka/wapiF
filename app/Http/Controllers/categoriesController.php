<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\categories;
use Illuminate\Support\Facades\Validator;

class categoriesController extends Controller
{
    
    //Afficher les categories

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
                'data' => 'null'
            ], 201);

        }

    }


    // Consulter ou afficher une categorie

    public function Categorie($id){

        $categorie = categories::find($id);

        if ($categorie) {
            
            return response([
                'code' => '200',
                'message' => 'success',
                'data' => $categorie
            ], 200);
            
        }else {
            
            return response([
                'code' => '004',
                'message' => 'L\'identifiant incorrect',
                'data' => 'null'
            ], 201);

        }

    }


    // Creer une categorie

    public function createCategorie(Request $request){

        $categorie = $request->all();

        $validator = Validator::make($request->all(), [
            
            'nomCategorie' => 'required|unique:categories|max:100|regex:/[^0-9.-]/',
            'image' => 'required',
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

            $img = $request->file('image');

            if($request->hasFile('image')){

                $imageName = rand() . '.' . $request->file('image')->getClientOriginalExtension();

                $img->move(public_path('/categories/images', $imageName));

                // return response()->json($imageName);

                $categorie['image'] = $imageName;

                $cat = categories::create($categorie);

                if ($cat) {

                    return response([
                        'code' => '200',
                        'message' => 'success',
                        'data' => $cat
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


    // Modifier une categorie

    public function putCategorie(Request $request, $id)
    {

        $identifiant = categories::findOrFail($id);

        $categorie = $request->all();

        if ($identifiant) {
            
            $validator = Validator::make($request->all(), [
            
                'nomCategorie' => 'required|unique:categories|max:100|regex:/[^0-9.-]/',
                'image' => 'required',
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

                $img = $request->file('image');

                if($request->hasFile('image')){

                    $imageName = rand() . '.' . $request->file('image')->getClientOriginalExtension();

                    $img->move(public_path('/categories/images', $imageName));

                    // return response()->json($imageName);

                    $categorie['image'] = $imageName;

                    $cat = $identifiant->update($categorie);

                    if ($cat) {

                        return response([
                            'code' => '200',
                            'message' => 'success',
                            'data' => $cat
                        ], 200);

                    }else {

                        return response([
                            'message' => '005',
                            'message' => 'Erreur 005 : Echec lors de l\'operation',
                            'data' => 'null'
                        ], 201);
                        
                    }

                }else {

                    return response([
                        'message' => '001',
                        'message' => 'image nulle',
                        'data' => 'null'
                    ], 201);

                }
                
            }

        }else {
            
            return response([
                'message' => '004',
                'message' => 'L\'identifiant incorrect',
                'data' => 'null'
            ], 201);

        }
        
    }


}
