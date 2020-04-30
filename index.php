<?php

declare(strict_types=1);

require __DIR__ . '/vendor/autoload.php';
use CoreDNA\PublicApi\ServiceGateway;
use CoreDNA\PublicApi\Service\Users;
use CoreDNA\PublicApi\Model\User;

$gateway = new ServiceGateway('https://www.coredna.com/assessment-endpoint.php');
$users = new Users($gateway);

// Send JSON payload to endpoint
$user = new User();
$user->setName('Ruby Lamadora');
$user->setEmail('ruby.lamadora@gmail.com');
$user->setUrl('https://github.com/bhinxy18/php-http-client');

try {
    $response = $users->create($user);
} catch (\Exception $e) {
    echo "Error while posting User data with Exception message: " . $e->getMessage();
}

if ($response === true) {
    echo 'Woohoo, data posted successfully!';
}

// GET request
try {
    $response = $users->fetch('ID123');
} catch (\Exception $e) {
    echo "Error while retrieving User data with Exception message: " . $e->getMessage();
}
