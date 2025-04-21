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
       //if we dont have the email return a failed response 
        if (!$this->validateEmail($request->email)) {
            return $this->failedResponse();
        }
       $this->send($request->email);
       return $this->successResponse();
    }
    
    public function send($email)
    {
        $token = $this->createToken($email);
        Mail::to($email)->send(new ResetPasswordMail($token));
    }

    public function createToken($email)
    {
        $oldToken = \DB::table('reset_passwords')->where('email', $email)->first();
        
        if ($oldToken) {
            return $oldToken->token;
        }

        $token = Str::random(60);

        $this->saveToken($token, $email);
        return $token;
    }

    public function saveToken($token, $email)
    {
        \DB::table('reset_passwords')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);
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
