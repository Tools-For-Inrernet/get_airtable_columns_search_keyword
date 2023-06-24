<?php
// get_column_names.php

// Replace 'YOUR_AIRTABLE_API_KEY' and 'YOUR_AIRTABLE_BASE_ID' with your actual Airtable API key and base ID
$apiKey = 'YOUR_AIRTABLE_API_KEY';
$baseId = 'YOUR_AIRTABLE_BASE_ID';
$tableName = 'YOUR_AIRTABLE_TABLE_ID'; // Replace with your table name

// Airtable API endpoint URL
$url = "https://api.airtable.com/v0/{$baseId}/{$tableName}?maxRecords=1";

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization: Bearer {$apiKey}"));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute cURL request
$response = curl_exec($ch);

// Check for errors
if (curl_errno($ch)) {
    $error = curl_error($ch);
    echo json_encode(array('success' => false, 'message' => 'Error: ' . $error));
    curl_close($ch);
    exit();
}

// Close cURL session
curl_close($ch);

// Parse the response JSON data
$data = json_decode($response, true);

// Extract column names from the response
$columnNames = array_keys($data['records'][0]['fields']);

// Prepare the response data
$responseData = array('success' => true, 'columnNames' => $columnNames);

// Send the response as JSON
header('Content-Type: application/json');
echo json_encode($responseData);
?>

