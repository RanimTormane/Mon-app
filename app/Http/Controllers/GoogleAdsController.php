<?php

namespace App\Http\Controllers;

use App\Models\google_Ads;
use Illuminate\Http\Request;
use Carbon\Carbon;


class GoogleAdsController extends Controller
{
        public function extractAdsData(){
            //extract
            $json=file_get_contents(storage_path('app/analytics/google_ads_data.json'));
            $data=json_decode($json,true);//result=une fonction pour decoder le fichier json et le mettre dans un tableau associatif 
            return $data;//retrun result

        }
        public function transformAdsData($data){
            //transform
    
            foreach ($data as $key => $item)//parcourt chaque ligne du tableau $data 
            {
                // Nettoyer les chaînes
                $data[$key]['campaign_name'] = trim($item['campaign_name']);//on supprime les espace qu debut et a la fin de chaque nom de comagne 
                $data[$key]['product_name'] = trim($item['product_name']);//le meme 
                $data[$key]['cost'] = (float) $item['cost'];//on assure que le champs cost esgt  bien etre un nmbre dicimale 
                $data[$key]['conversions'] = (int) $item['conversions'];// On convertit le nombre de conversions en entier (par exemple, 13.0 devient 13)
                $data[$key]['conversion_value'] = (float) $item['conversion_value'];// On s’assure que la valeur de conversion est bien un nombre à virgule
                $data[$key]['lead_type'] = ucfirst(strtolower($item['lead_type'])); //strtolower: on transforme tout en minuscule ("froid") / ucfirst : on met la première lettre en majuscule
                $data[$key]['date'] = Carbon::parse($item['date'])->format('Y-m-d');//On convertit la date du format brut en un format standard YYYY-MM-DD avec Carbon
            }

         return $data;
        }

        public function loadAdsData($data){
            //load
            foreach ($data as $item) {
                google_Ads::create([
                    'campaign_name' => $item['campaign_name'],
                    'product_name' => $item['product_name'],
                    'cost' => $item['cost'],
                    'conversions' => $item['conversions'],
                    'conversion_value' => $item['conversion_value'],
                    'lead_type' => $item['lead_type'],
                    'date' => $item['date'],
                ]);

            }
        }
        public function etlProcessAds()
        {
            $data = $this->extractAdsData();
            $transformed = $this->transformAdsData($data);
            $this->loadAdsData($transformed);

            return response()->json(['message' => 'ETL terminé avec succès 🚀']);
        }

        //KPI taux de CAC
        public function getGlobalCAC()
        {
            
            $totalCost = \DB::table('google_ads')->sum('cost');
            $totalConversions = \DB::table('google_ads')->sum('conversions');

            $cacGlobal = $totalConversions > 0 ? $totalCost / $totalConversions : 0;

            return response()->json([
                'cac_global' => round($cacGlobal, 2)
            ]);
        }
        public function getCACByCampaign(){
            $data = \DB::table('google_ads') ->select
                ('campaign_name',
                \DB::raw('SUM(cost) as total_cost'), 
                \DB::raw('SUM(conversions) as total_conversions'))
                ->groupBy('campaign_name')
                ->get();
            $result = $data->map(function ($item) {
                $cac = $item->total_conversions > 0 ? $item->total_cost / $item->total_conversions : 0;
                return [
                    'campaign_name' => $item->campaign_name,
                    'cac' => round($cac, 2)
                ];
            });
        
            return response()->json($result);

        }
        public function getCACByMonth()
        {
            // Récupérer les données agrégées par mois
            $data = \DB::table('google_ads')
                ->selectRaw('DATE_FORMAT(date, "%Y-%m") as month, SUM(cost) as total_cost, SUM(conversions) as total_conversions')
                ->groupBy('month')
                ->orderBy('month')
                ->get();

            // Calcul du CAC par mois
            $result = $data->map(function ($item) {
                return [
                    'month' => $item->month,
                    'cac' => $item->total_conversions > 0 ? round($item->total_cost / $item->total_conversions, 2) : 0
                ];
            });

            // Retourner les résultats sous forme de JSON
            return response()->json($result);
        }

        public function getCostByLeads(){
            $data = \DB::table('google_ads')
            ->select('lead_type', \DB::raw('SUM(cost) as total_cost'))
            ->groupBy('lead_type')
            ->get();
    
        return response()->json($data);
        }

        public function getGlobalROAS()
        {
        $data = \DB::table('google_ads')
            ->selectRaw('SUM(conversion_value) as total_revenue, SUM(cost) as total_cost')
            ->first();

        // Calcul du ROAS global
        $roasGlobal = $data->total_cost > 0 ? $data->total_revenue / $data->total_cost : 0;

        return response()->json([
            'roas_global' => round($roasGlobal, 2)
        ]);
        }
        public function getROASByProduct()
        {
        $data = \DB::table('google_ads')
            ->select('product_name', \DB::raw('SUM(conversion_value) as total_conversion_value'), \DB::raw('SUM(cost) as total_cost'))
            ->groupBy('product_name')
            ->get();
            $result = $data->map(function ($item) {
                $roas = $item->total_cost > 0 ? round($item->total_conversion_value / $item->total_cost, 2) : 0;
                return [
                    'name' => $item->product_name,
                    'value' => $roas
                ];
            });
        
            return response()->json($result);
        }
        public function getROASByCampaign()
        {
            $data = \DB::table('google_ads')
                ->select('campaign_name', \DB::raw('SUM(conversion_value) as total_conversion_value'), \DB::raw('SUM(cost) as total_cost'))
                ->groupBy('campaign_name')
                ->get();
        
            $result = $data->map(function ($item) {
                $roas = $item->total_cost > 0 ? round($item->total_conversion_value / $item->total_cost, 2) : 0;
                return [
                    'name' => $item->campaign_name,
                    'value' => $roas
                ];
            });
        
            return response()->json($result);
        }
        public function getProfitabilityByCampaign()
        {
            $data = \DB::table('google_ads')
                ->select('campaign_name', \DB::raw('SUM(conversion_value) as total_value'), \DB::raw('SUM(cost) as total_cost'))
                ->groupBy('campaign_name')
                ->get();

            $result = $data->map(function ($item) {
                $profitability = $item->total_cost > 0
                    ? round((($item->total_value - $item->total_cost) / $item->total_cost) * 100, 2)
                    : 0;
                return [
                    'name' => $item->campaign_name,
                    'value' => $profitability
                ];
            });

            return response()->json($result);
        }

    }