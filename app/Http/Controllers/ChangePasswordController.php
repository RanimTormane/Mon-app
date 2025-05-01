<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function resetPassword(ChangePasswordRequest $request){
             //if we have the token and the email i'll change the password else the token not found
             return $this->validateEmailAndToken($request) ? $this->changePassword($request) : $this->tokenNotFoundResponse();
    }
   
    private function validateEmailAndToken($request)
    {
        // On ne valide pas le token dans une table séparée, on vérifie seulement si l'email existe dans la base
        $user = User::where('email', $request->email)->where('password_reset_token', $request->token)->first();

        // Vérifier si l'utilisateur existe et si le token correspond dans la base de données
        return $user;
    }
    private function tokenNotFoundResponse()
    {
        return response()->json(['error' => 'Token or Email is incorrect'],Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    private function changePassword($request)
    {   //get user 
        $user = User::where('email', $request->email)->first();

        // Si l'utilisateur existe et que le token correspond
        if ($user) {
            $user->password_reset_token = Str::random(60);
            // Mise à jour du mot de passe
            $user->password = bcrypt($request->password);  // Assure-toi de hacher le mot de passe
        
            $user->save();
        return response()->json(['data'=>'Password Successfully Changed'],Response::HTTP_CREATED);
    }
    return $this->tokenNotFoundResponse();
}
}