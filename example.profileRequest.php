<?php

require __DIR__ . '/vendor/autoload.php';

require_once "ratepay_credentials.php";

/****************************************************
 * The ProfileRequest requests the merchant profile *
 ****************************************************/

// ProfileRequest needs 'head' only
$mbHead = new RatePAY\ModelBuilder();
$mbHead->setArray([
    'SystemId' => "Example",
    'Credential' => [
        'ProfileId' => PROFILE_ID,
        'Securitycode' => SECURITYCODE
    ]
]);

$rb = new RatePAY\RequestBuilder(true); // Sandbox mode = true

$profileRequest = $rb->callProfileRequest($mbHead);

if (!$profileRequest->isSuccessful()) die("ConfigurationRequest not successful");

var_dump($profileRequest->getResult());

// The ProfileRequest response object provides no specific methods

/*********************************************************************************************************************************
 * The library throws decidedly exceptions. It's recommended to surround model building and request calls with try-catch-blocks. *
 *********************************************************************************************************************************/
