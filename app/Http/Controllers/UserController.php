<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\saveUserRequest;
use App\Http\Requests\updateProfileRequest;


class UserController extends Controller
{
   public function index(){
    $users=User::all();
    if(request()->wantsJson()){
        return response()->json($users);
    }

   }
   public function store(saveUserRequest $request){
    $data = $request->validated();

    if ($request->hasFile('avatar')) {
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        $data['avatar'] = $avatarPath;
    }

    $user = User::create($data);

    $response = [
        'message' => 'User created successfully',
        'data' => $user
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
    public function updateProfile(updateProfileRequest $request)
{
    $user = auth()->user();

    if (!$user) {
        return response()->json([
            'error' => 'User not authenticated.',
        ], 401);
    }

    // Récupérer les données validées
    $data = $request->validated();

    // Gestion de l'avatar s'il y a un fichier
    if ($request->hasFile('avatar')) {
        // Supprimer l'ancien avatar s'il existe
        if ($user->avatar && \Storage::disk('public')->exists($user->avatar)) {
            \Storage::disk('public')->delete($user->avatar);
        }

        // Stocker le nouveau fichier dans "storage/app/public/avatars"
        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        $data['avatar'] = $avatarPath;
    }

    // Mettre à jour l'utilisateur
    $user->update($data);

    return response()->json([
        'message' => 'Profile updated successfully',
        'user' => $user,
        'avatar_url' => $user->avatar ? url('storage/' . $user->avatar) : null, // URL publique de l'avatar
    ], 200);
}

    
}