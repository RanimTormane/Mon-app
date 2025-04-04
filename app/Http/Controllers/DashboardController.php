<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class DashboardController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        
        // Retourne soit la vue, soit une réponse JSON selon le type de la requête
        if (request()->wantsJson()) {
            return response()->json($clients);  // Retourne la liste des clients en JSON
        }

        return view('dashboard.index', compact('clients'));  // Retourne la vue pour afficher la liste des clients
    }

    public function addClient(Request $request)
    {
        // Vérification manuelle de l'existence de l'URL
        $existingClient = Client::where('instagram_url', $request->instagram_url)->first();
        if ($existingClient) {
            return redirect()->route('dashboard.index')->with('error', 'Cette URL Instagram existe déjà.');
        }

        // Validation
        $request->validate([
            'name' => 'required|string|max:255',
            'instagram_url' => 'required|url',
        ]);

        // Création du client dans la base de données
        Client::create([
            'name' => $request->name,
            'instagram_url' => $request->instagram_url,
        ]);

        // Rediriger avec un message de succès
        return redirect()->route('dashboard.index')->with('success', 'Client ajouté avec succès.');
    }

    public function showClientDashboard($id)
    {
        // Récupérer le client par son ID
        $client = Client::findOrFail($id);

        // Vérifier si la requête attend une réponse JSON
        if (request()->wantsJson()) {
            return response()->json($client);  // Retourner les informations du client en JSON
        }

        // Retourner la vue avec les données du client
        return view('dashboard.client', compact('client'));
    }
}
