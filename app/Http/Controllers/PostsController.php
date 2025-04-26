<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use App\Models\posts;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Clients;
use App\Models\API;
use App\Models\KPIs;

class PostsController extends Controller
{

    
    public function getEngagementData(Request $request)
    {
        // Récupère l'access_token et client_id depuis les paramètres de la requête
        $accessToken = $request->query('access_token');
        $clientId = $request->query('client_id');
        $apiId =$request->query('api_id');
        // Vérifier si les paramètres sont présents
        if (!$accessToken || !$clientId  || !$apiId) {
            return response()->json(['error' => 'Access Token and Client ID are required'], 400);
        }
    
        // Récupérer le client dans la base de données
        $client = Clients::where('instagram_id', $clientId)->first();
        $api = API::find($apiId);
        if (!$api) {
            return response()->json(['error' => 'API not found'], 404);
        }
        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }
    
        // Créer l'URL pour l'API Instagram
        $url = "https://graph.instagram.com/$clientId/media?fields=caption,comments_count,like_count,shares_count,insights.metric(impressions)&access_token=$accessToken";
    
        // Effectuer la requête à l'API
        $response = Http::withOptions(['verify' => false])->get($url);
    
        // Vérifier si la requête a réussi
        if ($response->successful()) {
            $rawData = $response->json()['data'] ?? [];
    
            // Appeler la fonction pour transformer les données
            $transformedData = $this->transformEngagementData($rawData);
    
            // Stocker les posts transformés dans la base de données
            $this->storePostsInDatabase($clientId, $transformedData,$api->id);
    
            // Retourner les données transformées
            return response()->json($transformedData);
        }
    
        return response()->json(['error' => 'Unable to retrieve data from Instagram'], 500);
    }
    
        
        
          
           
    
        public function transformEngagementData($rawData)
        {
            $transformedData = [];
        
            foreach ($rawData as $post) {
                if (isset($post['id'])) {
                    $impressions = 0;
                    // Récupérer les impressions si elles existent dans les insights
                    if (isset($post['insights']['data'][0]['values'][0]['value'])) {
                        $impressions = $post['insights']['data'][0]['values'][0]['value'];
                    }

                    $transformedData[] = [
                        
                        'post_id' => $post['id'],
                        'caption' => isset($post['caption']) ? $post['caption'] : '',
                        'like_count' => isset($post['like_count']) ? $post['like_count'] : 0,
                        'comments_count' => isset($post['comments_count']) ? $post['comments_count'] : 0,
                        'shares_count' => isset($post['shares_count']) ? $post['shares_count'] : 0,
                        'impressions' => $impressions,
                        
                        'engagement' => (isset($post['like_count']) ? $post['like_count'] : 0) + (isset($post['comments_count']) ? $post['comments_count'] : 0),
                        'timestamp' => isset($post['timestamp']) ? Carbon::parse($post['timestamp'])->toDateTimeString() : now(), // Définit une valeur par défaut si la date est manquante

                    ];
                }
            }
        
            return $transformedData;
        }
        
    
  
        public function storePostsInDatabase($clientId, $postsData,$apiId)
        {
            $client = Clients::where('instagram_id', $clientId)->first();

if (!$client) {
    return response()->json(['error' => 'Client not found'], 404);
}


            foreach ($postsData as $post) {
                // Vérifier si le post existe déjà dans la base de données
                $existingPost = posts::where('post_id', $post['post_id'])->first();
        
                // Si le post n'existe pas, on l'insère
                if (!$existingPost) {
                
                    posts::create([
                      
                        'client_id' => $client->id,
                        'api_id' => $apiId,  
                        'post_id' => $post['post_id'],
                        'caption' => $post['caption'],
                        'like_count' => $post['like_count'],
                        'comments_count' => $post['comments_count'],
                        'shares_count' => $post['shares_count'],
                        'impressions' => $post['impressions'], 
                        'engagement' => $post['engagement'],
                       
                        'timestamp' => $post['timestamp'], // La date transformée
                    ]);
                }
            }
}

        public function calculateEngagementKPI($clientId)
        {
    // Récupérer le client en fonction de son ID (ou instagram_id)
    $client = Clients::find($clientId);

    // Vérifier si le client existe
    if (!$client) {
        return response()->json(['error' => 'Client not found'], 404);
    }

    // Calculer les total likes, comments, shares et impressions pour le client
    $totalLikes = Posts::where('client_id', $client->id)->sum('like_count');
    $totalComments = Posts::where('client_id', $client->id)->sum('comments_count');
    $totalShares = Posts::where('client_id', $client->id)->sum('shares_count'); // Vérifier la présence de cette colonne
    $totalImpressions = Posts::where('client_id', $client->id)->sum('impressions'); // Vérifier la présence de cette colonne

    if ($totalImpressions <= 0) {
        return response()->json(['error' => 'Impressions data is invalid or missing'], 400);
    }

    // Calculer le taux d'engagement global
    $engagementGlobal = (($totalLikes + $totalComments + $totalShares) / $totalImpressions) * 100;
    //save the kpi on real time 
     $this->saveKpi($client->id,'Engagement Rate',$engagementGlobal, '↑', 'high');
    // Retourner la réponse JSON avec le taux d'engagement global
    return response()->json(['engagement_global' => $engagementGlobal]);
}

public function getEngagementPerPost($clientId)
{
    // Vérifier que le client existe
    $client = Clients::find($clientId);
    if (!$client) {
        return response()->json(['error' => 'Client not found'], 404);
    }

    // Récupérer les posts du client
    $posts = Posts::where('client_id', $client->id)->get();

    // Préparer les données avec KPI
    $engagementPerPost = [];

    foreach ($posts as $post) {
        if ($post->impressions > 0) {
            $kpi = (($post->like_count + $post->comments_count + $post->shares_count) / $post->impressions) * 100;
        } else {
            $kpi = 0;
        }

        $engagementPerPost[] = [
            'post_id' => $post->post_id,
            'caption' => \Str::limit($post->caption, 30), // raccourcir la légende
            'like_count' => $post->like_count,
            'comments_count' => $post->comments_count,
            'shares_count' => $post->shares_count,
            'impressions' => $post->impressions,
            'engagement_kpi' => round($kpi, 2),
            'timestamp' => $post->timestamp,
        ];
    }
   
    return response()->json($engagementPerPost);
}

        
        
public function saveKpi($clientId, $name, $value, $trend, $status)
{
    // Créer un nouvel enregistrement pour chaque KPI
    $kpi = new KPIs();
    $kpi->client_id = $clientId; // Associer ce KPI au client
    $kpi->name = $name;
    $kpi->value = $value;
    $kpi->trend = $trend;
    $kpi->status = $status;
  
    $kpi->save();

    return $kpi;
}   
        
public function showAllKpis()
{
    return KPIs::all();
}
    public function getInteractions($clientId){
      $client = Clients::find($clientId);
      if(!$client){
        return response()->json(['error'=>'Client not found'],404);//404 error Not Found
      }


      // get client posts
      $posts=posts::where('client_id',$client->id)->get();


    // Initialiser les compteurs
    $totalLikes = 0;
    $totalComments = 0;
    $totalShares = 0;
    $totalImpressions = 0;
    foreach ($posts as $post) {
        $totalLikes += $post->like_count;
        $totalComments += $post->comments_count;
        $totalShares += $post->shares_count;
        $totalImpressions += $post->impressions;
    }
    return response()->json([
        'total_likes' => $totalLikes,
        'total_comments' => $totalComments,
        'total_shares' => $totalShares,
        'total_impressions' => $totalImpressions,
    ]);
    }

    public function getEngagementEvolution($clientId){
        $client= Clients::find($clientId);
        if(!$client){
            return response()->json(['error'=>'Client not found'],404);
        }
        $posts=posts::where('client_id',$client->id)->get();
        $monthlyData = [
            'January' => ['likes' => 0, 'comments' => 0, 'shares' => 0, 'impressions' => 0],
            'February' => ['likes' => 0, 'comments' => 0, 'shares' => 0, 'impressions' => 0],
            'March' => ['likes' => 0, 'comments' => 0, 'shares' => 0, 'impressions' => 0],
            'April' => ['likes' => 0, 'comments' => 0, 'shares' => 0, 'impressions' => 0],
            'May' => ['likes' => 0, 'comments' => 0, 'shares' => 0, 'impressions' => 0],
            'June' => ['likes' => 0, 'comments' => 0, 'shares' => 0, 'impressions' => 0],
            'July' => ['likes' => 0, 'comments' => 0, 'shares' => 0, 'impressions' => 0],
            'August' => ['likes' => 0, 'comments' => 0, 'shares' => 0, 'impressions' => 0],
            'September' => ['likes' => 0, 'comments' => 0, 'shares' => 0, 'impressions' => 0],
            'October' => ['likes' => 0, 'comments' => 0, 'shares' => 0, 'impressions' => 0],
            'November' => ['likes' => 0, 'comments' => 0, 'shares' => 0, 'impressions' => 0],
            'December' => ['likes' => 0, 'comments' => 0, 'shares' => 0, 'impressions' => 0],
        ];
    
        // Parcourir les posts pour ajouter les engagements au bon mois
        foreach ($posts as $post) {
            $month = $post->created_at->format('F'); // Extrait le mois sous forme de nom : 'January', 'February', etc.
            
            // Vérifier si le mois est valide (au cas où il y aurait une erreur de données)
            if (isset($monthlyData[$month])) {
                $monthlyData[$month]['likes'] += $post->like_count;
                $monthlyData[$month]['comments'] += $post->comments_count;
                $monthlyData[$month]['shares'] += $post->shares_count;
                $monthlyData[$month]['impressions'] += $post->impressions;
            }
        }
    
        return response()->json($monthlyData);
    }

    public function getEngagementByUser($clientId){
        $client= Clients::find($clientId);
        if(!$client){
            return response()->json(['error'=>'Client not found'],404);
        }
        $posts=posts::where('client_id',$client->id)->get();
 $userEngagementData = [];

    foreach ($posts as $post) {
        // Calculer l'engagement de chaque post
        $engagement = $post->like_count + $post->comments_count + $post->shares_count;

        // Si l'utilisateur n'existe pas encore dans le tableau, l'ajouter
        if (!isset($userEngagementData[$post->client_id])) {
            $userEngagementData[$post->client_id] = [
                'total_likes' => 0,
                'total_comments' => 0,
                'total_shares' => 0,
                'total_impressions' => 0,
                'total_engagement' => 0,
            ];
        }

        // Ajouter les données d'engagement pour chaque utilisateur
        $userEngagementData[$post->client_id]['total_likes'] += $post->like_count;
        $userEngagementData[$post->client_id]['total_comments'] += $post->comments_count;
        $userEngagementData[$post->client_id]['total_shares'] += $post->shares_count;
        $userEngagementData[$post->client_id]['total_impressions'] += $post->impressions;
        $userEngagementData[$post->client_id]['total_engagement'] += $engagement;
    }

    // Calculer les KPIs par utilisateur
    $engagementKpis = [];
    foreach ($userEngagementData as $userId => $data) {
        // Vérifier qu'il y a des impressions pour éviter la division par zéro
        if ($data['total_impressions'] > 0) {
            $engagementRate = (($data['total_engagement'] / $data['total_impressions']) * 100);
        } else {
            $engagementRate = 0;
        }

        // Ajouter les KPI à la réponse
        $engagementKpis[] = [
            'user_id' => $userId,
            'total_likes' => $data['total_likes'],
            'total_comments' => $data['total_comments'],
            'total_shares' => $data['total_shares'],
            'total_impressions' => $data['total_impressions'],
            'total_engagement' => $data['total_engagement'],
            'engagement_rate' => round($engagementRate, 2),
        ];
    }

    return response()->json($engagementKpis);
}

public function filterPosts(Request $request)
{
    $request->validate([
        'start_date' => 'nullable|date',
        'end_date' => 'nullable|date|after_or_equal:start_date',
        'min_likes' => 'nullable|integer|min:0',
        'caption' => 'nullable|string|max:255'
    ]);

    $query = posts::query();

    if ($request->filled('start_date')) {
        $query->whereDate('timestamp', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $query->whereDate('timestamp', '<=', $request->end_date);//ignore l'heure, et ne prend en compte que la date
    }

    if ($request->filled('min_likes')) {
        $query->where('like_count', '>=', $request->min_likes);
    }

    if ($request->filled('caption')) {
        $query->where('caption', 'like', '%' . $request->caption . '%');
    }

    return $query->orderBy('timestamp', 'desc')->get();
}
        }






        



    
    

        
