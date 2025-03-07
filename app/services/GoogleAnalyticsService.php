<?php

namespace App\Services;

use Google_Client;
use Google_Service_AnalyticsData;
use Google_Service_AnalyticsData_RunReportRequest;
use Google_Service_AnalyticsData_Metric;
use Google_Service_AnalyticsData_Dimension;
use Google_Service_AnalyticsData_DateRange;

class GoogleAnalyticsService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setAuthConfig(storage_path('app/google/local-bebop-450021-v2-640a0e13f44b.json'));
        $this->client->addScope(Google_Service_AnalyticsData::ANALYTICS_READONLY);
    }

    public function getAnalyticsData($propertyId, $startDate, $endDate)
    {
       // create an object to make the request to the API
        $analyticsData = new Google_Service_AnalyticsData($this->client);
        $request = new Google_Service_AnalyticsData_RunReportRequest();

        // Gives the data between start date and end date 
        $dateRange = new Google_Service_AnalyticsData_DateRange();
        $dateRange->setStartDate($startDate);  
        $dateRange->setEndDate($endDate);  

        // ADD la plage de dates à la request
        $request->setDateRanges([$dateRange]);

        // Define les dimensions (exp: pays)
        $request->setDimensions([
            new Google_Service_AnalyticsData_Dimension(['name' => 'country']),
        ]);

        // Define the metrics (exp : sessions, newUsers, screenPageViews)
        $request->setMetrics([
            new Google_Service_AnalyticsData_Metric(['name' => 'sessions']),
            new Google_Service_AnalyticsData_Metric(['name' => 'newUsers']),
            new Google_Service_AnalyticsData_Metric(['name' => 'screenPageViews']),  // use validated metrics
        ]);

        // Effect the request
        $results = $analyticsData->properties->runReport("properties/$propertyId", $request);

        return $results->getRows();
    }
}
