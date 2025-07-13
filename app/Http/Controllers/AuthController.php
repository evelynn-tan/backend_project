<?php

namespace App\Http\Controllers; 
 
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth; 

class AuthController extends Controller 
{   
    // Login method to authenticate users 
    public function login(Request $request) 
    { 
        $credentials = $request->validate([ 
            'email' => 'required|email', 
            'password' => 'required', 
        ]); 
 
        if (!Auth::attempt($credentials)) { 
            return response()->json(['message' => 'Login gagal'], 401); 
        } 
        
        //bagian ini buat fetch datanya
        /** @var \App\Models\User $user */ 
        $user = Auth::user(); 
        $token = $user->createToken('api-token')->plainTextToken; 
 
        //bagian ini buat mengirim data ke frontend
        return response()->json([ 
            'user' => $user, 
            'token' => $token, 
        ]); 
    } 
    //logout
    public function logout(Request $request){
        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $token=$request -> user()->currentAccessToken();

        if ($token){
            $token -> delete();
            return response()->json(['message' => 'Logout berhasil']);
        } else{
            return response()->json(['message' => 'token unknown'],401);
        }
        // \Log::error('Tes log Laravel berhasil.');
        // return response()->json(['message' => 'Cek log berhasil']);
    }
} 
