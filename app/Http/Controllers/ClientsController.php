<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use App\Models\Clients;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
          $clients = Clients::all();
            return response()->json($clients);
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    \Log::info('🔹 Requête reçue dans store()', ['request' => $request->all()]);

    if ($request->method() !== 'POST') {
        return response()->json(['error' => 'Méthode non autorisée'], 405);
    }

    // Récupérer le token depuis la requête
    $accessToken = $request->input('access_token');

    if (!$accessToken) {
        return response()->json(['error' => 'Access token is required'], 400);
    }

    // Appel API pour récupérer les données d'Instagram
    $response = Http::withOptions(['verify' => false])->get("https://graph.instagram.com/me?fields=id,username,profile_picture_url&access_token=$accessToken");

    if ($response->successful()) {
        $data = $response->json();

        // Récupérer les permissions (ici, vous pouvez les envoyer comme un tableau JSON ou une structure clé-valeur)
       
        $permissions = [
            'view' => true, // ✅ forcé, même si on envoie "false" dans la requête
            'edit' => $request->input('edit', false),
            'delete' => $request->input('delete', false),
        ];
        
        // Utilisation de updateOrCreate pour insérer ou mettre à jour un client
        $client = Clients::updateOrCreate(
            ['instagram_id' => $data['id']],
            [
                'username' => $data['username'],
                'profile_picture_url' => $data['profile_picture_url'] ?? null,
                'permissions' => $permissions ? json_encode($permissions) : null // Assurez-vous que permissions est bien encodé en JSON
            ]
        );
        
        \Log::info('✅ Nouveau client créé', ['client' => $client]);

        return response()->json([
            'message' => 'Client Instagram enregistré avec succès',
            'client' => $client
        ], 201);
    } else {
        return response()->json([
            'error' => 'Impossible de récupérer les données',
            'details' => $response->json()
        ], 400);
    }
}


    

    /**
     * Display the specified resource.
     */
   /*public function show($id)
{
    // Recherche du client par ID
    $client = Clients::find($id);//make permissions to clients

    // Vérifie si le client existe
    if (!$client) {
        return response()->json(['error' => 'Client non trouvé'], 404);
    }

    return response()->json($client);
}*/


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Clients $clients)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Clients $clients)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clients $clients)
    {
        //
    }
  
    }