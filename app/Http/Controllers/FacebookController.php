<?php

namespace App\Http\Controllers;

use App\Models\Facebook;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class FacebookController extends Controller
{
    protected $accessToken = 'EAAJMIxVEiYIBOwVyoF246fPeUSDSXYmcwdgIThGEHA61sCB2eGXloVQZCU9ZBKT1xZAozI1Y09iTz31r2NOSZAMZBgeY4xpWAZCLP1zQy91lV56An151HBHxx1EoKjjVinUf0AvHDgcol9jLCXtjh5TEqIOfirq2W1HMlPwNZC5ykK8dInr6WFkgoXB1I4VDa32KWpBCpQTckHbNyysExHpaJlxXUaZCysqf7xggfZBuF1nZCw5iGXVq6YkwZDZD';

    public function getFacebookData()
    {
        try {
            $client = new Client();
            $response = $client->get('https://graph.facebook.com/v12.0/me', [
                'query' => [
                    'fields' => 'id,name,likes,birthday,friends',
                    'access_token' => $this->accessToken
                ],
                'verify' => false,  // Deactivate the verification SSL
            ]);
            // Convert response to a PHP array
            $data = json_decode($response->getBody(), true);

            // JSON response
            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error retrieving Facebook data', 'message' => $e->getMessage()], 500);
        }
    }
}
