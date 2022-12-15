<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Validator;

class AuthenticationController extends Controller
{
    
    function login(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        if($validate->fails()){
            return response()->json([
                "status" => "error",
                "results" => "Some fields are empty"
            ]);
        }
        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'results' => 'Invalid Credentials',
            ]);
        }
        $user = Auth::user();
        return response()->json([
                'status' => 'success',
                'results' => 'Welcome',
                'user' => $user,
                'authorisation' => [
                    'token' => $token,
                    'type' => 'bearer',
                ]
            ], 200);
    }

    function register(Request $request){
        $validate_username = Validator::make($request->all(), [
            'username' => 'required|string|alpha_dash|max:255',
        ]);
        if($validate_username->fails()){
            return response()->json([
                "status" => "error",
                "results" => "Username must contain letters, numbers, dashes and NOT space"
            ]);
        }
        $validate_username_exist = Validator::make($request->all(), [
            'username' => 'required|string|alpha_dash|max:255|unique:users',
        ]);
        if($validate_username_exist->fails()){
            return response()->json([
                "status" => "error",
                "results" => "This username is already registred"
            ]);
        }
        $validate_email_exist = Validator::make($request->all(), [
            'email' => 'required|string|regex:/(.+)@(.+)\.(.+)/i|max:255|unique:users',
        ]);
        if($validate_email_exist->fails()){
            return response()->json([
                "status" => "error",
                "results" => "This email is already registred"
            ]);
        }
        $validate_email = Validator::make($request->all(), [
            'email' => 'required|string|regex:/(.+)@(.+)\.(.+)/i|max:255',
        ]);
        if($validate_email->fails()){
            return response()->json([
                "status" => "error",
                "results" => "Invalid email format"
            ]);
        }
        $validate_password = Validator::make($request->all(), [
            'password' => 'required|string|min:8', 
        ]);
        if($validate_password->fails()){
            return response()->json([
                "status" => "error",
                "results" => "Password must contain at least 8 characters"
            ]);
        }
        $user = new User;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        if($user->save()){
            return response()->json([
            'status' => 'success',
            'results' => 'Account created successfully. You can now login.',
            'user' => $user
        ],200);
        }else{
            return response()->json([
                "status" => "error",
                "results" => []
            ]);
        }
    }
}
