<?php
// Include the Composer autoloader
require 'vendor/autoload.php';

// Move Messages From Inbox to Processed
$config = parse_ini_file('env.ini');

$accessToken = file_get_contents('access_token.key');
$objectId = $config['OBJECT_ID'];

// Create a Guzzle HTTP client
$client = new \GuzzleHttp\Client();

// Make a GET request to get list of email messages
$response = $client->get("https://graph.microsoft.com/v1.0/users/$objectId/messages", [
    'headers' => [
        'Authorization' => 'Bearer ' . $accessToken,
    ],
]);

// Parse the response
$data = json_decode($response->getBody(), true)["value"];

// Get custom folder ID where you want to move emails
$customFolderId = "Processed"; // Replace with your folder ID

// Move each email to the custom folder
foreach ($data as $message) {
    $messageId = urlencode($message['id']);

    // Move the message to the custom folder
    $moveUrl = "https://graph.microsoft.com/v1.0/users/$objectId/messages/$messageId/move";
    $moveData = [
        'destinationId' => $customFolderId,
    ];

    $client->post($moveUrl, [
        'headers' => [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ],
        'json' => $moveData,
    ]);

    // Handle the response or errors as needed
}

?>