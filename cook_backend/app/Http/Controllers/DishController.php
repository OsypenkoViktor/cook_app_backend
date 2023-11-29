<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Dish;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DishController extends Controller
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
        $validData=$request->validate([
            "name"=>"string|required|unique:products|",
            "user_id"=>"required|numeric",
            "description"=>"required|string|min:20"
        ]);
        $newDish = Dish::create($validData);
        return response()->json(['message'=>"dish $request->name created successfully"]);
    }

    /**
     * Display the specified resource.
     */
    public function showAll()
    {
        $allDishes = Dish::all();
        if(!$allDishes)
        {
        return response()->json(['message'=>'Dishes not found'],404);
        }
        return response()->json([$allDishes]);
    }
    public function show(Dish $dish)
    {
        return response()->json($dish);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dish $dish)
    {
        $validData = $request->validate([
            'name'=>'sometimes|required|string|min:3',
            'description'=>'sometimes|required|min:10'
        ]);
        $dish->update($validData);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dish $dish)
    {
        $dish = Dish::find($id);
        if (!$dish) {
            return response()->json(['message' => 'Блюдо не найдено'], 404);
        }

        // код для удаления блюда
        $dish->delete();
        return response()->json(['message' => 'Блюдо успешно удалено'], 200);
    }

    public function createComment(Request $request,Dish $dish){
        $validComment =$request->validate([
            'text'=>'string|required',
            'user_id'=>'required',
            'dish_id'=>'required'
        ]);
        $newComment = Comment::create($validComment);
        return response()->json(["message"=>"Comment created $newComment"]);
    }

    public function getComments(Request $request,Dish $dish){
        $commentedDish = Dish::find($dish);
        $dishComments = $commentedDish->comments;
        return response()->json($dishComments);
    }
    public function deleteComment(Request $request,Dish $dish, Comment $comment){
        if($comment->dish_id===$dish->id)
        {
            $comment->delete();
            return response()->json();
        }
        return response()->json(['message'=>"Access denied"],403);
    }
    public function updateComment(Request $request,Dish $dish, Comment $comment){
        if($comment->dish_id===$dish->id)
        {
            $comment->update($request->all());
            return response()->json(['message'=>'Comment updated']);
        }
        return response()->json(['message'=>"Access denied"],403);
    }

    public function voteDish(Request $request,Dish $dish){


            return response()->json(['message'=>'not implemented yet']);

    }

}
