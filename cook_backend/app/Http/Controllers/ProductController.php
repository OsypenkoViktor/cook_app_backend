<?php

namespace App\Http\Controllers;

use http\Env\Response;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use function Webmozart\Assert\Tests\StaticAnalysis\length;

class ProductController extends Controller
{


    /**
     * Display a listing of the resource.
     * return only moderated products to user
     * return all products to admin
     */
    public function index()
    {
        // handle response to admin
        $user = Auth::user();
        if($user->can("moderate products")){
            $allProducts=Product::with("processes")->get();
            if(!$allProducts->isEmpty()){
                return response()->json($allProducts,200);
            } else{
                return response()->json(['message'=>'no products found'],404);
            }
        }
        //handle response to user
        $moderatedProducts = Product::where("isModerated",true)->get();
        if(!$moderatedProducts->isEmpty()){
            $this->authorize("viewAny",Product::class);
            return response()->json($moderatedProducts);
        }else{
            return response()->json(['message'=>'no products found'],404);
        }
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
                $this->authorize("create",Product::class);
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
    public function show(Product $product)
    {
            $this->authorize("view",$product);
            return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $this->authorize("update",$product);
        $isUpdated = $product->update($request->all());
        if($isUpdated){
            return response()->json(["message"=>"Product id $product->id has been updated"],200);
        }else{
            return response()->json(["message"=>"Error has occured during the product id $product->id update"],400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
            $this->authorize("delete",$product);
            $product->delete();
            return response()->json([
                'message'=>"Product ID $id has been deleted"
            ],200);
    }
}
