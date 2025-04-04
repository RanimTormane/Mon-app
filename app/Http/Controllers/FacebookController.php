<?php

namespace App\Http\Controllers;

use App\Models\Facebook;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class FacebookController extends Controller
{
    protected $accessToken;
         public function __construct()
        {
        $this->accessToken = env('FACEBOOK_ACCESS_TOKEN');
        }

    public function getFacebookData()
    {
        try {
            $client = new Client();//create an object HTTP client 
            $response = $client->get('https://graph.facebook.com/v12.0/me', [
                'query' => [
                    'fields' => 'id,name,likes.summary(true),birthday,friends.summary(true),picture{url},albums',
                    'access_token' => $this->accessToken
                ],
                'verify' => false,  // Deactivate the verification SSL
            ]);
            // Convert response to a PHP array
            $data = json_decode($response->getBody(), true);
            if (isset($data['albums']) && isset($data['albums']['data'])) {
                \Log::info('Albums Facebook récupérés:', $data['albums']['data']);
            } else {
                \Log::warning('Aucun album Facebook trouvé.');
            }
            // JSON response
            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('Facebook API error: ' . $e->getMessage()); // Log de l'erreur

            return response()->json([
                'error' => 'Error retrieving Facebook data',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
