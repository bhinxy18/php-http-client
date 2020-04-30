<?php

declare(strict_types=1);

use CoreDNA\PublicApi\ServiceGateway;
use CoreDNA\PublicApi\Service\Users;
require __DIR__ . '/vendor/autoload.php';

$gateway = new ServiceGateway('http://dummy.restapiexample.com/api/v1');
$users = new Users($gateway);

// Get User by ID
// $response = $users->fetch('719');


$options = [
    'http' => [
        'method' => 'POST',
        'header' => [
            'Accept: application/json', 
            'Content-Type: application/json'
        ] 
    ]
];
$context = stream_context_create($options);
$uri = 'https://www.coredna.com/assessment-endpoint.php';

$payload = [
    "name" => "John Doe",
    "email" => "spamwelcomedhere@gmail.com",
    "url" => "https://github.com/john-doe/http-client"
];

try {
    $response = file_get_contents($uri, false, $context);
} catch(Exception $ex) {
    throw new Exception("Exception caught while attempting GET to $uri. Message: " . $ex->getMessage());
}
var_dump($response);
