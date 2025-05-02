<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\ChangePasswordRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChangePasswordController extends Controller
{
    public function resetPassword(ChangePasswordRequest $request)
    {
        Log::info('Password reset request received', [
            'email' => $request->email,
            'token_length' => strlen($request->token)
        ]);

        if (!$this->validateEmailAndToken($request)) {
            Log::error('Token validation failed', [
                'email' => $request->email
            ]);
            return $this->tokenNotFoundResponse();
        }

        return $this->changePassword($request);
    }
   
    private function validateEmailAndToken($request)
    {
        $resetData = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->first();

        Log::info('Token validation result', [
            'email' => $request->email,
            'token_found' => !is_null($resetData)
        ]);

        if (!$resetData) {
            return false;
        }

        return true;
    }

    private function tokenNotFoundResponse()
    {
        return response()->json([
            'error' => 'Token or Email is incorrect'
        ], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    private function changePassword($request)
    {   
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            Log::error('User not found during password reset', [
                'email' => $request->email
            ]);
            return $this->tokenNotFoundResponse();
        }

        try {
            $user->password = Hash::make($request->password);
            $user->save();

            // Delete the used token
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            Log::info('Password successfully changed', [
                'email' => $request->email
            ]);

            return response()->json([
                'data' => 'Password Successfully Changed'
            ], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Error changing password', [
                'email' => $request->email,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'An error occurred while changing the password'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}