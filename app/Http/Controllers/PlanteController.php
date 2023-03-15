<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Plante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StorePlanteRequest;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\UpdatePlanteRequest;

class PlanteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $plantes= Plante::all();
        return response()->json([
            'plantes'=>$plantes
        ],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePlanteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
            // return response()->json($request, 200);

        // verify image
        if($request->hasFile('image')){
            $image=time().'.'.$request->image->extension();
            $request->image->storeAs('public/images',$image);
        }
        else{
            $image='';
        }

        $validator = Validator::make($request->all(), [
            'name'=>'required|string|max:255',
            'description'=>'required|string|max:255',
            'price'=>'required|integer',
            'image'=>'mimes:png,jpg,jpeg',
            'category_id'=>'required|int|exists:categories,id',


        ]);
        if($validator->fails()){
            return response()->json($validator->errors(), 400);
        }
    // get auth user
    $user = Auth::guard('api')->user();

        $plante = Plante::create([
            "name"=>$request->name,
            "description"=>$request->description,
            "price"=>$request->price,
            "category_id"=>$request->category_id,
            "user_id"=>$user->id,
            "image"=>$image
        ]);


        return response()->json([
            'massage'=>'plant created With success',
            'plante'=>$plante,
            // show category associate to this plante
            'category'=>$plante->category,
            // show user associate to this plante
            'user'=>$plante->user
        ],201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Plante  $plante
     * @return \Illuminate\Http\Response
     */
    public function show(Plante $plante)
    {

            return response()->json([
                'Plante'=>$plante,
                'Ctegory'=>$plante->category,
                'User'=>$plante->user,
            ],200);


    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Plante  $plante
     * @return \Illuminate\Http\Response
     */
    public function edit(Plante $plante)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePlanteRequest  $request
     * @param  \App\Models\Plante  $plante
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Plante $plante)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required|string|max:255',
            'description'=>'required|string|max:255',
            'price'=>'required|integer',
            'category_id'=>'required|int|exists:categories,id',

        ]);
   // get auth user
   $user = Auth::guard('api')->user();

        if($validator->fails()){
                return response()->json($validator->errors(), 400);
        }

        else if($user->role==1 || $plante->user_id == $user->id)
{

        // return $validator->validate();

        $plante->update($validator->validate());

        return response()->json([
            'massage'=>'plant updated With success',
            'plante'=>$plante,
            'category'=>$plante->category,
            'user'=>$plante->user,
        ],201);

    }
    else if($plante->user_id != $user->id){
        return response()->json(['error'=>"Sorry this plante it's not your's"], 400);

    }
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Plante  $plante
     * @return \Illuminate\Http\Response
     */
    public function destroy(Plante $plante)
    {

                // get auth user
   $user = Auth::guard('api')->user();

   if($user->role==1)
   {

        $plante->delete();
        return response()->json([
            'massage'=>'plante Deleted With Success',
            'plante'=>$plante,
        ],200);
    }

else if($plante->user_id !=$user->role){
    return response()->json(['error'=>"Sorry this plante it's not your's"], 400);

}
}
}
