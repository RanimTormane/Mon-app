<?php

namespace App\Http\Controllers;

use App\Services\GoogleAnalyticsService;

class GoogleAnalyticsController extends Controller
{//property that store the instance of the class 
    protected $analytics;
//initialize this property 
    public function __construct(GoogleAnalyticsService $analytics)
    {
        $this->analytics = $analytics;
    }

    public function index()
    {
        $data = $this->analytics->getAnalyticsData(
            '476634976', //  VIEW ID
            '2024-02-01',
            '2024-03-01'
        );
//compact('data') :helper function create associative table 
        return view('google-analytics', compact('data'));
    }
}
