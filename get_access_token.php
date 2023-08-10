<?php
// Include the Composer autoloader
require 'vendor/autoload.php';

$config = parse_ini_file('env.ini');

// Replace with your actual values
$clientId = $config['CLIENT_ID'];
$clientSecret = $config['CLIENT_SECRET'];
$tenantId = $config['TENANT_ID']; // Azure AD Tenant ID
$scopes = 'https://graph.microsoft.com/.default'; // The scope for Microsoft Graph API

// Microsoft Identity Platform endpoint
$tokenUrl = "https://login.microsoftonline.com/$tenantId/oauth2/v2.0/token";

// Create a Guzzle HTTP client
$client = new \GuzzleHttp\Client();

// Make a POST request to get the access token
$response = $client->post($tokenUrl, [
    'form_params' => [
        'client_id' => $clientId,
        'client_secret' => $clientSecret,
        'scope' => $scopes,
        'grant_type' => 'client_credentials',
    ],
]);

// Parse the response
$data = json_decode($response->getBody(), true);

if (isset($data['access_token'])) {
    $accessToken = $data['access_token'];
    // Store the token to a file.
    file_put_contents('access_token.key', $accessToken);
    // Or use the access token as needed
    echo "Access Token: $accessToken";
} else {
    echo "Error retrieving access token.";
}
