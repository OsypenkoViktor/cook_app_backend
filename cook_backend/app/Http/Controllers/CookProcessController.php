<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CookProcess;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CookProcessController extends Controller
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
    public function store(Request $request, CookProcess $cookProcess)
    {
        //$this->authorize('create',CookProcess::class);
        $validData = $request->validate([
            "name"=>"string|required|unique:products|",
            'product_id'=>"integer|exists:products,id",
            "duration"=>"required|numeric",
            "cookPresenceInterval"=>"required|numeric",
            "description"=>"required|string|min:20"
        ]);
        $newCookProcess = CookProcess::create($validData);
        return response()->json(['message'=>"Cook process created successfully id - $newCookProcess"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product,CookProcess $cookProcess)
    {
        return response()->json(["cookProcess"=>$cookProcess]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,Product $product, CookProcess $cookProcess)
    {
        $cookProcess->update($request->all());
        return response()->json(['message'=>'Cook process updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CookProcess $cookProcess)
    {
        $cookProcess->delete();
        return response()->json(['message'=>'Cook process has been deleted']);
    }
}
