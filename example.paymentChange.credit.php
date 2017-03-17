<?php

require __DIR__ . '/vendor/autoload.php';

require_once "ratepay_credentials.php";

require_once "helper.createTransaction.php"; // Opens new transaction to handle
require_once "helper.deliverTransaction.php"; // Delivers transaction to handle

/******************************************************************
 * The PaymentChange alters order details (shopping basket)       *
 * The subtype Credit commits a additional discount item to order *
 ******************************************************************/

$mbHead = new RatePAY\ModelBuilder('head');
$mbHead->setArray([
    'SystemId' => "Example",
    'Credential' => [
        'ProfileId' => PROFILE_ID,
        'Securitycode' => SECURITYCODE
    ],
    'TransactionId' => $transactionId
]);

/*
 * The PaymentChange Credit (PCh) requires a discount item.
 * It has to be send after the ConfirmationDeliver
 */
$shoppingBasket = [
    'ShoppingBasket' => [
        'Discount' => [
            'Description' => "Goodwill refund",
            'UnitPriceGross' => 20,
            'TaxRate' => 19,
        ]
    ]
];

$mbContent = new RatePAY\ModelBuilder('Content');
$mbContent->setArray($shoppingBasket);

// PaymentChange has to be specified by subtype
$pChCredit = $rb->callPaymentChange($mbHead, $mbContent)->subtype('credit');

if (!$pChCredit->isSuccessful()) die("PaymentChange not successful");

var_dump("PaymentChange successful");

// PaymentChange response object provides no specific methods


/*********************************************************************************************************************************
 * The library throws decidedly exceptions. It's recommended to surround model building and request calls with try-catch-blocks. *
 *********************************************************************************************************************************/