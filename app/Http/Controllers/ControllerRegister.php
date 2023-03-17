<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class ControllerRegister extends Controller
{
    public  function register(Request $request){

        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'email'=>'required|email|unique:users,email',
            'password'=>'required|min:6|max:24|confirmed'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success'=>false,
                'message'=>'User exist deja'
            ],Response::HTTP_UNAUTHORIZED);
        }

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        return response()->json([
            'success'=>true,
            'message'=>'Register Success',
            'user'=>$user
        ],Response::HTTP_OK);
    }
    public function login(Request $request)
    {
        $creds = $request->only(['email','password']);
        if (!$token=auth()->attempt($creds)){
            return response()->json([
                'success'=>false,
                'message'=>'information incorrecte'
            ],Response::HTTP_UNAUTHORIZED);
        }
        return response()->json([
            'success'=>true,
            'token'=>$token,
            'user'=>Auth::user()
        ],Response::HTTP_OK);

    }

    public function logout(Request $request){
        try {
            JWTAuth::invalidate(JWTAuth::parseToken($request->token));
            return response()->json([
                "success"=>true,
                "message"=>"logout success"
            ]);
        } catch (TokenExpiredException $e) {
            return response()->json([
                "success"=>false,
                "message"=>"token_expired"
            ]);

    } catch (TokenInvalidException $e) {
        return response()->json([
            "success"=>false,
            "message"=>"token_invalid"
        ]);

    } catch (JWTException $e) {
        return response()->json([
            "success"=>false,
            "message"=>"token_absent"
        ]);

    }
    }

    // change profile

    public function edit_profile(Request $request,$user_id=null){

        // get auth user
        $user= auth()->user();

        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'email' => 'required|string|email|unique:users,email,'.$user->email,
            'password'=>'required|min:6|max:24|confirmed',
            'role'=>'integer',

        ]);

        if($validator->fails()){
                return response()->json($validator->errors(), 400);
        }

        // return $user;
     else if($user_id){
            if($user->role==1 || $user->id==$user_id)
            {
                if($user->role==1){
                    $role=$request->role;
                }else{
                    $role=$user->role;
                }

                User::find($user_id)->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'role'=>$role
                ]);

                return response()->json([
                    'message' => 'User profile updated successfully',
                    'user' =>User::find($user->id),
                ]);

            }
            else{

                return response()->json([
                    "Error"=>"Sorry it's Not Your A count",
                ], 403);

            }
        }
        else{
            // return  User::find($user->id);

            User::find($user->id)->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return response()->json([
                'message' => 'User profile updated successfully',
                'user' => User::find($user->id),
            ]);
        }





    }
}
