<?php
namespace App\Http\Controllers;

use App\Http\Requests\signupRequest;
use Illuminate\Support\Facades\Auth;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'signup']]);
    }

    public function login(Request $request)
    {
        // Valider les informations de l'utilisateur
        $credentials = $request->only(['email', 'password']);
    
        // Vérifier que l'utilisateur existe et tenter de générer un token
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized, invalid credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }
    
        // Si l'authentification réussit, obtenir l'utilisateur et envoyer le token
        $user = auth()->user();
    
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => $user
        ]);
    }
    
    
    
    public function signup(signupRequest $request)
    {
        $data = $request->all();
        $data['role'] = 'client';

        $user = User::create($data);

        return $this->login($request);
    }

    public function me()
    {
        return response()->json(auth()->user());
    }

    protected function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,

               
                'user' => auth()->user() // Ceci devrait contenir l'utilisateur avec le rôle
            
              
        ]);
    }
}