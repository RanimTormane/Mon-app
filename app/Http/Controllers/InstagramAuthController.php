<?php
    namespace App\Http\Controllers;

    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Http;
    use App\Models\Client;

    
    class InstagramAuthController extends Controller
    {
        // Redirection vers l'authentification Instagram
        public function redirectToInstagram()
        {
            $appId = env('INSTAGRAM_APP_ID');
            $redirectUri = urlencode(env('INSTAGRAM_REDIRECT_URI'));
            $scopes = 'instagram_business_content_publish,instagram_business_basic,instagram_business_manage_comments,instagram_business_manage_insights';

    
            $authUrl ="https://api.instagram.com/oauth/authorize?client_id=$appId&redirect_uri=$redirectUri&response_type=code&scope=$scopes";
    
    
            return redirect($authUrl);
        }
    
        // Callback pour gérer la réponse d'Instagram et récupérer le token d'accès
        public function instagramCallback(Request $request)
        {
            // Vérifie si le paramètre 'code' est présent dans la requête
            if (!$request->has('code')) {
                return response()->json(['error' => 'Code dauthentification manquant'], 400);
            }
        
            // Récupère le code d'autorisation
            $code = $request->input('code');
        
            // Faire l'appel POST à Instagram pour obtenir le token d'accès
          
                $response = Http::asForm()->withOptions(['verify' => false])->post('https://api.instagram.com/oauth/access_token', [
                    'client_id' => env('INSTAGRAM_APP_ID'),
                    'client_secret' => env('INSTAGRAM_APP_SECRET'),
                    
                    'redirect_uri' => env('INSTAGRAM_REDIRECT_URI'),
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                ]);
          
        
            // Vérifier si l'appel a échoué
            if ($response->failed()) {
                // Retourner une réponse avec l'erreur et les détails
                return response()->json(['error' => 'Échec de l’authentification Instagram', 'details' => $response->json()], 400);
            }
        
            // Retourner les données reçues d'Instagram (généralement, un token d'accès)
            return response()->json($response->json());
        }
        
        
        
     
          
    

    
        //save on database
       /* public function saveInstagramToken(Request $request)
{
    $request->validate([
        'instagram_id' => 'required|string',
        'username' => 'required|string',
        'profile_picture_url' => 'nullable|string',
        'access_token' => 'required|string',
    ]);

    $token = Client::updateOrCreate(
        ['instagram_id' => $request->instagram_id], // Si l'ID existe, il met à jour
        [
            'username' => $request->username,
            'profile_picture_url' => $request->profile_picture_url,
            'access_token' => $request->access_token
        ]
    );

    return response()->json(['message' => 'Token enregistré avec succès !', 'data' => $token], 200);
}*/
    }        