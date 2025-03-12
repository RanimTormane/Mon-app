<?php

namespace App\Http\Controllers;

use App\Services\GoogleAnalyticsService;
use Illuminate\Http\Request;

class GoogleAnalyticsController extends Controller
{
    protected $googleAnalyticsService;

    public function __construct(GoogleAnalyticsService $googleAnalyticsService)
    {
        $this->googleAnalyticsService = $googleAnalyticsService;
    }

    public function index()
    {
        
        $propertyId = 'G-C23DK1RE7E'; 
        $startDate = '2023-01-01'; 
        $endDate = '2023-12-31';  

        $data = $this->googleAnalyticsService->getAnalyticsData($propertyId, $startDate, $endDate);

        // Passer les données à la vue
        return view('google-analytics', compact('data'));
    }
}

