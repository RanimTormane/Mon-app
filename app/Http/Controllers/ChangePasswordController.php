<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Hash;

class ChangePasswordController extends Controller
{
    public function resetPassword(ChangePasswordRequest $request){
             //if we have the token and the email i'll change the password else the token not found
        return $this->getPasswordResetTableRow($request)->count()> 0 ? $this->changePassword($request) : $this->tokenNotFoundResponse();
    }
   
    private function getPasswordResetTableRow($request)
    {

        return \DB::table('reset_passwords')->where(['email' => $request->email,'token' =>$request->resetToken]);

    }
    private function tokenNotFoundResponse()
    {
        return response()->json(['error' => 'Token or Email is incorrect'],Response::HTTP_UNPROCESSABLE_ENTITY);
    }
    private function changePassword($request)
    {   //get user 
        $user = User::whereEmail($request->email)->first();
        $user->update(['password'=>$request->password]);
        //delete the row of the user after he reset his password 
        $this->getPasswordResetTableRow($request)->delete();
        return response()->json(['data'=>'Password Successfully Changed'],Response::HTTP_CREATED);
    }
}
