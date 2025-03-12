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
        try {
            // Create an object for the request of api 
            $analyticsData = new Google_Service_AnalyticsData($this->client);
            $request = new Google_Service_AnalyticsData_RunReportRequest();

            // define the plage of date
            $dateRange = new Google_Service_AnalyticsData_DateRange();
            $dateRange->setStartDate($startDate);  
            $dateRange->setEndDate($endDate);  

            // add the plage of date on the request 
            $request->setDateRanges([$dateRange]);

            // Define the dimensions (exp : pays)
            $request->setDimensions([new Google_Service_AnalyticsData_Dimension(['name' => 'country'])]);

            // Define the metrics  (exp : sessions, newUsers, screenPageViews)
            $request->setMetrics([
                new Google_Service_AnalyticsData_Metric(['name' => 'sessions']),
                new Google_Service_AnalyticsData_Metric(['name' => 'newUsers']),
                new Google_Service_AnalyticsData_Metric(['name' => 'screenPageViews']), 
            ]);

            // Effect the request 

            $results = $analyticsData->properties->runReport("properties/$propertyId", $request);

            if (count($results->getRows()) === 0) {
                return [];  // return empty table 
            }
    
            return $results->getRows();
        } catch (\Exception $e) {
        
            return [];
        }
    }
}

