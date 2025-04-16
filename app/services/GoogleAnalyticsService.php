<?php
// app/Services/GoogleAnalyticsService.php
namespace App\Services;

use Google\Analytics\Data\V1beta\Client\BetaAnalyticsDataClient;
use Google\Analytics\Data\V1beta\DateRange;
use Google\Analytics\Data\V1beta\Dimension;
use Google\Analytics\Data\V1beta\Metric;
use Illuminate\Support\Facades\Log;

class GoogleAnalyticsService
{
    protected $client;
    protected $propertyId;

    public function __construct()
    {
        try {
            $credentialsPath = env('GOOGLE_ANALYTICS_CREDENTIALS', 'storage/app/analytics/service-account-credentials.json');
            $absolutePath = base_path($credentialsPath);
            Log::info('Attempting to load keyfile: ' . $absolutePath);
            if (!file_exists($absolutePath)) {
                throw new \Exception("Could not find keyfile: $absolutePath (resolved from $credentialsPath)");
            }
            if (!is_readable($absolutePath)) {
                throw new \Exception("Keyfile not readable: $absolutePath");
            }
            $this->propertyId = env('GOOGLE_ANALYTICS_PROPERTY_ID');
            if (!$this->propertyId) {
                throw new \Exception("Google Analytics Property ID not set");
            }
            $this->client = new BetaAnalyticsDataClient([
                'credentials' => $absolutePath,
            ]);
            Log::info('Google Analytics client initialized successfully');
        } catch (\Exception $e) {
            Log::error('GoogleAnalyticsService error: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getReport($startDate = '7daysAgo', $endDate = 'today')
    {
        try {
            $response = $this->client->runReport([
                'property' => "properties/{$this->propertyId}",
                'dateRanges' => [
                    new DateRange([
                        'start_date' => $startDate,
                        'end_date' => $endDate,
                    ]),
                ],
                'dimensions' => [
                    new Dimension(['name' => 'date']),
                    new Dimension(['name' => 'pagePath']),
                ],
                'metrics' => [
                    new Metric(['name' => 'screenPageViews']),
                    new Metric(['name' => 'activeUsers']),
                ],
            ]);

            $result = [];
            foreach ($response->getRows() as $row) {
                $result[] = [
                    'date' => $row->getDimensionValues()[0]->getValue(),
                    'pagePath' => $row->getDimensionValues()[1]->getValue(),
                    'pageViews' => $row->getMetricValues()[0]->getValue(),
                    'activeUsers' => $row->getMetricValues()[1]->getValue(),
                ];
            }
            return $result;
        } catch (\Exception $e) {
            Log::error('Google Analytics API error: ' . $e->getMessage());
            throw new \Exception('Failed to fetch analytics data: ' . $e->getMessage());
        }
    }
}