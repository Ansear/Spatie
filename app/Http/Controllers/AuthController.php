<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request){
        try{ 
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|email',
                'password' => 'required|string',
                'code' => 'string'
            ]);

            $user = User::Create([
                'name' => $request->name,
                'email'  => $request->email,
                'password' => bcrypt($request->password)
            ]);
            if($request->code && $request->code=="12345" ){
                $user->assignRole('super-admin');
            }else{
                $user->assignRole('customer');
            }
            


            return response()->json([
                'message' => 'Usuario creado exitosamente',
                'user' => $user,
            ], 201);
        }catch (\Exception $e){
            return response()->json([
                'error' => 'Unauthorised'
            ], 401); 
        }
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $credentials = $request->only('email', 'password');
        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'error' => 'Unauthorize',
            ], 401);
           
        }
        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'ttl' => 3600,
        ]); 
        
    }

    public function me()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        } else {
            return response()->json([
                'user' => $user
            ]);
        }
    }

    public function permi(){
        return response()->json([
            "message" => "hola"
        ]);
    }
}
