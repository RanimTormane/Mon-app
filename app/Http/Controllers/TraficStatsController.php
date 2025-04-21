<?php

namespace App\Http\Controllers;

use App\Models\trafic_stats;
use Illuminate\Http\Request;
use Carbon\Carbon;
class TraficStatsController extends Controller
{
    public function extractTraficData()
    {
        $json=file_get_contents(storage_path('app/analytics/trafic_stats.json'));
        $data=json_decode($json,true);
        return $data;
       
    }
    public function transformTraficData($data){
        foreach ($data as $entry) {
            trafic_stats::create([
                'date' => Carbon::parse($entry['date'])->format('Y-m-d'),
                'visiteurs_uniques' => $entry['visiteurs_uniques'],
                'sessions' => $entry['sessions'],
                'temps_total_site' => $entry['temps_total_site'],
                'bounce_rate' => $entry['bounce_rate'],
                'pages_vues_totales' => $entry['pages_vues_totales'],
                'nouveaux_visiteurs' => $entry['nouveaux_visiteurs'],
                'visiteurs_recurrents' => $entry['visiteurs_recurrents'],]);
    }}
    public function loadTraficData($data){
        //load
        if (is_array($data) || is_object($data)) {
        foreach ($data as $entry) {
            trafic_stats::create([
                'date' => $entry['date'],
                'visiteurs_uniques' => $entry['visiteurs_uniques'],
                'sessions' => $entry['sessions'],
                'temps_total_site' => $entry['temps_total_site'],
                'bounce_rate' => $entry['bounce_rate'],
                'pages_vues_totales' => $entry['pages_vues_totales'],
                'nouveaux_visiteurs' => $entry['nouveaux_visiteurs'],
                'visiteurs_recurrents' => $entry['visiteurs_recurrents']
            ]);

        }
    }}
    public function etlProcessTrafic()
    {
        $data = $this->extractTraficData();
    
        $transformed = $this->transformTraficData($data);
        $this->loadTraficData($transformed);

        return response()->json(['message' => 'ETL terminé avec succès 🚀']);
    }
    public function getVisiterByMonth(){

        $data = \DB::table('trafic_stats')
        ->select(
            \DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
            \DB::raw('SUM(visiteurs_uniques) as total_visiteurs')
        )
        ->groupBy(\DB::raw('DATE_FORMAT(date, "%Y-%m")'))
        ->orderBy('month')
       
        ->get();
        
    
        return $data;
       
    }
    public function getSessionDurationByMonth() {
        $data = \DB::table('trafic_stats')
            ->select(
                \DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
                \DB::raw('ROUND(AVG(sessions), 2) as avg_session_duration') // On utilise session ici
            )
            ->groupBy(\DB::raw('DATE_FORMAT(date, "%Y-%m")'))
            ->orderBy('month')
            ->get();
    
        return response()->json($data);
    }
    public function bounceRateStats()

    {
        $data = \DB::table('trafic_stats')
            ->select(
                \DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
                \DB::raw('ROUND(AVG(bounce_rate), 2) as avg_bounce_rate') // Moyenne du taux de rebond
            )
            ->groupBy(\DB::raw('DATE_FORMAT(date, "%Y-%m")'))
            ->orderBy('month')
            ->get();
    
        return response()->json($data);
    }
    public function getVisitPagesBySession(){
        $data = \DB::table('trafic_stats')
        ->select('sessions', \DB::raw('SUM(pages_vues_totales) as total_pages_vues'))
        ->groupBy('sessions')
        ->get();
    return response()->json($data);
}

public function getNewVisitors() {
    // On récupère les données pour plusieurs jours de l'année 2024
    $targetDates = ['2024-01-26', '2024-03-01', '2024-04-10', '2024-05-15']; // Exemple de dates supplémentaires

    $visitorsData = trafic_stats::whereIn('date', $targetDates)
        ->select('date', 'nouveaux_visiteurs')
        ->orderBy('date', 'asc')
        ->get();

    $growthRates = [];

    for ($i = 1; $i < count($visitorsData); $i++) {
        $previous = $visitorsData[$i - 1];
        $current = $visitorsData[$i];

        // Éviter la division par zéro
        if ($previous->nouveaux_visiteurs == 0) {
            $growthRate = 0;
        } else {
            $growthRate = (($current->nouveaux_visiteurs - $previous->nouveaux_visiteurs) / $previous->nouveaux_visiteurs) * 100;
        }

        $growthRates[] = [
            'from_date' => $previous->date,
            'to_date' => $current->date,
            'growth_rate' => round($growthRate, 2),
        ];
    }

   

    return response()->json($growthRates);
}
}