<?php

declare(strict_types=1);

use Firebase\JWT\JWT;

require_once('../vendor/autoload.php');

$secretKey  = 'bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=';
$issuedAt   = new DateTimeImmutable();
$expire     = $issuedAt->modify('+6 minutes')->getTimestamp();      // Add 60 seconds
$serverName = "your.domain.name";
$username   = "username";                                           // Retrieved from filtered POST data

$data = [
    'iat'  => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
    'iss'  => $serverName,                       // Issuer
    'nbf'  => $issuedAt->getTimestamp(),         // Not before
    'exp'  => $expire,                           // Expire
    'userName' => $username,                     // User name
];


// Encode the array to a JWT string.
echo JWT::encode(
    $data,
    $secretKey,
    'HS512'
);


?>