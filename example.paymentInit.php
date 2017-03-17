<?php

require __DIR__ . '/vendor/autoload.php';

require_once "ratepay_credentials.php";

/*************************************************
 * The PaymentInit initializes a new transaction *
 *************************************************/

// Building head model
$mbArr = new RatePAY\ModelBuilder('Head'); // If no parameter is set, 'head' model will be set by default
$mbArr->setArray([
    'SystemId' => "Example",
    'Credential' => [
        'ProfileId' => PROFILE_ID,
        'Securitycode' => SECURITYCODE
    ]
]);

$rb = new RatePAY\RequestBuilder(true); // true == Sandbox mode
$paymentInit = $rb->callPaymentInit($mbArr);

if (!$paymentInit->isSuccessful()) die("PaymentInit not successful");

// PaymentInit response object provides following methods:
// getTransactionId(); // Returns transaction id (unique transaction identifier) {string}

var_dump($paymentInit->getTransactionId());


/*********************************************************************************************************************************
 * The library throws decidedly exceptions. It's recommended to surround model building and request calls with try-catch-blocks. *
 *********************************************************************************************************************************/