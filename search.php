<?php
// Replace 'YOUR_AIRTABLE_API_KEY' and 'YOUR_AIRTABLE_BASE_ID' with your actual Airtable API key and base ID
$apiKey = 'YOUR_AIRTABLE_API_KEY';
$baseId = 'YOUR_AIRTABLE_BASE_ID';
$tableName = 'YOUR_ARTABLE__TABLE_ID'; // Replace with your table name

// Retrieve selectedColumn and searchText from the AJAX request
$requestBody = file_get_contents('php://input');
$data = json_decode($requestBody);
$selectedColumn = $data->selectedColumn;
$searchText = $data->searchText;

// Perform the search using Airtable API
$searchResults = performSearch($apiKey, $baseId, $tableName, $selectedColumn, $searchText);

// Return the search results in a simple JSON format
echo json_encode(array_values($searchResults));

/**
 * Perform the search using Airtable API.
 *
 * @param string $apiKey        Airtable API key
 * @param string $baseId        Airtable base ID
 * @param string $tableName     Airtable table name
 * @param string $selectedColumn    Selected column for the search
 * @param string $searchText    Text to search for
 * @return array    Array of search results
 */
function performSearch($apiKey, $baseId, $tableName, $selectedColumn, $searchText)
{
    // Build the Airtable API endpoint URL
    $url = "https://api.airtable.com/v0/{$baseId}/{$tableName}?filterByFormula=SEARCH(%22{$searchText}%22%2C+{{$selectedColumn}})";

    // Set up the cURL request
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $apiKey,
    ]);

    // Execute the cURL request
    $response = curl_exec($ch);

    // Check for errors
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
        exit;
    }

    // Close the cURL request
    curl_close($ch);

    // Parse the response JSON
    $data = json_decode($response, true);

    // Extract the search results from the response data
    $searchResults = [];

    if (isset($data['records'])) {
        foreach ($data['records'] as $record) {
            // Customize this based on your Airtable schema
            $result = $record['fields'][$selectedColumn];
            $searchResults[] = $result;
        }
    }

    return $searchResults;
}
