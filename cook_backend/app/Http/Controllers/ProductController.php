<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $validData=$request->validate([
                "name"=>"string|required|unique:products|",
                'parent_id'=>"nullable|integer|exists:products,id",
                "calories"=>"required|numeric|max:1100",
                "proteins"=>"required|numeric",
                "fats"=>"required|numeric",
                "carbohydrates"=>"required|numeric",
                "description"=>"required|string|min:20"
            ]);

                $newProductId=Product::create($validData);
                return response()->json([
                    "message"=>"Product ID $newProductId created",
                ]);

        }catch(ValidationException $e){
            return response()->json([
                "errors" => $e->validator->errors()->all()
            ], 422);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
