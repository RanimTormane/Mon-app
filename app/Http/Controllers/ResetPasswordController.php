<?php

namespace App\Http\Controllers;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;
use App\Mail\ResetPasswordMail;

use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ], [
            'email.email' => 'L\'adresse email n\'est pas valide.',
            
        ]);
    
        // Vérifier si l'email existe dans la base de données
        if (!$this->validateEmail($request->email)) {
            return $this->failedResponse();
        }
    
        $this->send($request->email);
        return $this->successResponse();
    }
    
    public function send($email)
    {
        // Create token
        $token = Str::random(60);
        
        // Store token in database
        \DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            [
                'token' => $token,
                'created_at' => now()
            ]
        );

        // Send email with token
        Mail::to($email)->send(new ResetPasswordMail($token));
    }

 
    //check if the email exist in the database  if its true return the email from the database ans the reverse boolean function by !!
    public function validateEmail($email)
    {
        return !!User::where('email', $email)->first();
    }


    public function failedResponse()
    {
        return response()->json([
            'error' => 'We can\'t find a user with that email address '
        ], Response::HTTP_NOT_FOUND);
    }
    public function successResponse(){
        return response()->json([
            'data' => 'Reset Email is send successfully, please check your inbox.'
        ], Response::HTTP_OK);
    }
}
