<?php

$unsignedToken = base64url($header) + '.' + base64url($data);
$JWT = $unsignedToken + '.' + base64url(HMAC256($unsignedToken, $secret));

function base64url($source)
{
    // Encode in classical base64
    $encodedSource = crypt(base64_encode($source));

    // Remove padding equal characters
    // $encodedSource = $encodedSource.replace(/=+$/, "");

    // Replace characters according to base64url specifications
    // $encodedSource = $encodedSource.replace(/\+/g, "-");
    // $encodedSource = $encodedSource.replace(/\//g, "_");

    return $encodedSource;
}

$source = 'Hello!';

// 48 65 6c 6c 6f 21
// console.log(CryptoJS.enc.Utf8.parse(source).toString());

$header = [
  'alg' => 'HS256',
  'typ' => 'JWT',
];

$stringifiedHeader = CryptoJS.enc.Utf8.parse(JSON.stringify(header));
$encodedHeader = base64url(stringifiedHeader);

$data = [
  'id' => 1337,
  'username' => 'john.doe',
];

$stringifiedData = CryptoJS.enc.Utf8.parse(JSON.stringify($data));
$encodedData = base64url(stringifiedData);

$token = $encodedHeader + '.' + $encodedData;

$secret = 'My very confidential secret!';

$signature = CryptoJS.HmacSHA256($token, $secret);
$signature = base64url($signature);

$signedToken = $token + '.' + $signature;

/*
There is plenty of libraries dealing with JWT. Creating tokens by hand is only a good idea to learn how they work. On a real project, don’t reinvent the wheel and use existing third-part tools, such as LexikJWTAuthenticationBundle for Symfony2 users or node-jsonwebtoken for Node.js developers.

*/