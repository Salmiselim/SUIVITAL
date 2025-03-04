<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class MedicationService
{
    private $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    // Get information from the FDA Drug Label API
    public function getMedicationInfo($medicationName)
    {
        // The base FDA Drug Label API URL
        $url = 'https://api.fda.gov/drug/label.json';

        // Send a GET request to the API
        $response = $this->httpClient->request('GET', $url, [
            'query' => [
                'search' => 'drug_interactions:' . urlencode($medicationName),
                'limit' => 5
            ]
        ]);

        // Parse the response data as an array
        $data = $response->toArray();

        // Return the data (or null if no data is found)
        return $data['results'] ?? null;
    }
}
