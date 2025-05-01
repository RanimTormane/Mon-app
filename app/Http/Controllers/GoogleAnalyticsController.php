<?php

namespace App\Http\Controllers;

use App\Models\GoogleAnalytics;
use Carbon\Carbon;
use Illuminate\Http\Request;


class GoogleAnalyticsController extends Controller
{
    /**
     * Extraction des données depuis un fichier JSON (peut être adapté pour une API).
     */
    public function extractData()
    {
        // Lire le JSON depuis un fichier
        $json = file_get_contents(storage_path('app/analytics/fake_kpi_google_analytics.json'));
        $data = json_decode($json, true); // Décoder le JSON en tableau associatif
        
        return $data;
    }

    /**
     * Transformation des données
     * Cette fonction va transformer les données extraites selon la logique du processus ETL.
     */
    public function transformData($data)
    {
        foreach ($data as $key => $item) {
            // Transformation : Convertir la date en format Carbon
            $data[$key]['visit_date'] = Carbon::parse($item['visit_date']);
            // Assurer que la conversion est un booléen
            $data[$key]['is_converted'] = (bool) $item['is_converted'];
        }

        return $data;
    }

    /**
     * Charger les données dans la base de données
     * Cette fonction va insérer ou mettre à jour les données dans la table `google_analytics`
     */
    public function loadData($data,$apiId)
    {
        foreach ($data as $item) {
            GoogleAnalytics::create([
                'api_id' => $apiId, 
                'visitor_id' => $item['visitor_id'],
                'session' => $item['session'],
                'visit_date' => $item['visit_date'],
                'campaign_name' => $item['campaign_name'],
                'traffic_source' => $item['traffic_source'],
                'lead_type' => $item['lead_type'],
                'is_converted' => $item['is_converted'],
                'lead_id' => $item['lead_id'],
            ]);
        }
    }

    /**
     * Le processus ETL complet : Extraction, Transformation et Chargement
     */
    public function etlProcess(Request $request)
    {
        // Récupération dynamique de api_id (via query param ou body)
        $apiId = $request->input('api_id');
    
        if (!$apiId) {
            return response()->json([
                'message' => 'api_id est requis.'
            ], 400);
        }
    
        // Extraction des données
        $data = $this->extractData();
    
        // Transformation des données
        $transformedData = $this->transformData($data);
    
        // Chargement avec api_id
        $this->loadData($transformedData, $apiId);
    
        return response()->json([
            'message' => 'ETL avec api_id effectué avec succès.',
        ]);
    }
    
    public function getConversionGlobalRate()
{
    // Nombre total de visiteurs (sessions)
    $totalVisiteurs = GoogleAnalytics::count();

    // Nombre de sessions converties
    $totalConversions = GoogleAnalytics::where('is_converted', true)->count();

    // Calcul du taux
    $tauxConversion = $totalVisiteurs > 0 
        ? round(($totalConversions / $totalVisiteurs) * 100, 2)
        : 0;

    return response()->json([
        'taux_conversion_global' => $tauxConversion . '%',
        'total_visiteurs' => $totalVisiteurs,
        'total_conversions' => $totalConversions
    ]);
}
public function conversionParCampagne()
{

$data = GoogleAnalytics::select('campaign_name')
->selectRaw('COUNT(*) as total')
->selectRaw('SUM(is_converted) as converted')
->groupBy('campaign_name')
->get()
->map(function ($item) {
    $taux = $item->total > 0 ? round(($item->converted / $item->total) * 100, 2) : 0;
    return [
        'campaign_name' => $item->campaign_name,
        'taux_conversion' => $taux
    ];
});

return response()->json($data);
}



public function getConversionsByTrafficSource()
{
    $results = GoogleAnalytics::select(
            'traffic_source',
            \DB::raw('SUM(CASE WHEN is_converted = true THEN 1 ELSE 0 END) as converted'),
            \DB::raw('SUM(CASE WHEN is_converted = false THEN 1 ELSE 0 END) as not_converted')
        )
        ->groupBy('traffic_source')
        ->get();

    return response()->json($results);
}
public function getConversionsByLeadType()
{
    $results = GoogleAnalytics::select(
            'lead_type',
            \DB::raw('SUM(CASE WHEN is_converted = true THEN 1 ELSE 0 END) as converted'),
            \DB::raw('SUM(CASE WHEN is_converted = false THEN 1 ELSE 0 END) as not_converted')
        )
        ->groupBy('lead_type')
        ->get();

    return response()->json($results);
}


public function getLeadScoreEvolution()
{
    $scoreMap = ['Froid' => 1, 'Tiède' => 2, 'Chaud' => 3];

    $data = GoogleAnalytics::select('session', 'traffic_source', 'lead_type')
        ->orderBy('visit_date')//On trie les données selon la date de visite (visit_date) pour avoir une chronologie.


        ->get()
        ->map(function ($row) use ($scoreMap) {
            return [
                'session' => $row->session,
                'source' => $row->traffic_source,
                'score' => $scoreMap[$row->lead_type],//convertit le type de lead (lead_type) en score numérique 1/2/3
            ];
        });

    // Group by traffic source for line chart format
    $grouped = [];
    foreach ($data as $row) {
        $grouped[$row['source']][] = [
            'name' => $row['session'],//name = session axe x
            'value' => $row['score']//value = score du lead axe y
        ];
    }

    // Format pour ngx-charts
    $chartData = [];
    foreach ($grouped as $source => $series) {
        $chartData[] = [
            'name' => $source,
            'series' => $series
        ];
    }

    return response()->json($chartData);
}
public function getFilteredData(Request $request)
{
    $query = GoogleAnalytics::query();

    if ($request->filled('campaign_name')) {
        $query->where('campaign_name', $request->campaign_name);
    }

    if ($request->filled('traffic_source')) {
        $query->where('traffic_source', $request->traffic_source);
    }

    if ($request->filled('lead_type')) {
        $query->where('lead_type', $request->lead_type);
    }
   
    $results = $query->get()->toArray();

    return response()->json($results);
}


}
