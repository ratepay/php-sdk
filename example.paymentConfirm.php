<?php

require __DIR__ . '/vendor/autoload.php';

require_once "ratepay_credentials.php";

require_once "helper.createTransaction.php"; // Opens new transaction to handle

/********************************************************
 * The PaymentConfirm confirms a authorized transaction *
 ********************************************************/

// PaymentConfirm needs 'head' only
$mbHead = new RatePAY\ModelBuilder();
$mbHead->setArray([
    'SystemId' => "Example",
    'Credential' => [
        'ProfileId' => PROFILE_ID,
        'Securitycode' => SECURITYCODE
    ],
    'TransactionId' => $transactionId,
    'External' => [ // If order id or customer id isn't created at the time of Payment Request use Payment Confirm to hand missing information later
        'MerchantConsumerId' => "1234567", // Customer Id
        'OrderId' => "xyzabc"
    ]
]);

$rb = new RatePAY\RequestBuilder(true); // Sandbox mode = true

$paymentConfirm = $rb->callPaymentConfirm($mbHead);

if (!$paymentConfirm->isSuccessful()) die("PaymentConfirm not successful");

var_dump("PaymentConfirm successful");

// PaymentConfirm response object provides no specific methods


/*********************************************************************************************************************************
 * The library throws decidedly exceptions. It's recommended to surround model building and request calls with try-catch-blocks. *
 *********************************************************************************************************************************/
