<?php
// Include the Composer autoloader
require 'vendor/autoload.php';

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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Fetch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</head>
<body>
<div class="container mt-5">
  <div class="row">
    <div class="col-md-3">
      List of Emails:
      <div class="card">
        <div class="card-header">
        Inbox
        </div>
        <ul class="list-group list-group-flush">
            <?php

            foreach($data as $d) {
                echo '<li class="list-group-item">'.$d["subject"].'</li>';
            }
            ?>
            
        </ul>
        </div>
      </div>
    <div class="col-md-9">
      Email Content
    </div>
  </div>
</div>

</body>
</html>
