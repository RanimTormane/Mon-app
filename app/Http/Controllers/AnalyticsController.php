<?php

// app/Http/Controllers/AnalyticsController.php
namespace App\Http\Controllers;

use App\Services\GoogleAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AnalyticsController extends Controller
{
    protected $analyticsService;

    public function __construct(GoogleAnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
        $this->middleware('auth:api');
    }

    public function getReport(Request $request)
    {
        try {
            $startDate = $request->input('start_date', '7daysAgo');
            $endDate = $request->input('end_date', 'today');
            Log::info('Fetching analytics report', ['start_date' => $startDate, 'end_date' => $endDate]);
            $data = $this->analyticsService->getReport($startDate, $endDate);
            return response()->json([
                'success' => true,
                'data' => $data,
            ], 200);
        } catch (\Exception $e) {
            Log::error('AnalyticsController error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch analytics data: ' . $e->getMessage(),
            ], 500);
        }
    }
}