<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\saveUserRequest;

class UserController extends Controller
{
   public function index(){
    $users=User::all();
    if(request()->wantsJson()){
        return response()->json($users);
    }

   }
   public function store(saveUserRequest $request){
    $user=  User::create($request->validated());
    $response=[
        'message'=>'User created successfully',
        'data'=>$user
    ];
    if($request->wantsJson()){
        return response()->json($response,201);
    }
 
   }

   public function update(saveUserRequest $request, User $user){
    $user->update($request->validated());

    if(request()->wantsJson()){
        return response()->json(['User updated successfully',
        'alert-type' => 'info',
        'user'=>$user
    ]);
    }

   }
   public function show(Request $request,  User $user)
   {
       
      if (!$user) {
         return response()->json(['error' => 'User not exist'], 404);
     }
 
     if ($request->wantsJson()) {
         return response()->json($user); 
     }
 
}
public function edit(User $user){

    if (request()->wantsJson()) {
       return response()->json(['user' => $user]);
    }}

    public function destroy(User $user){
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        $user->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}