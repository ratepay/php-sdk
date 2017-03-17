<?php

require __DIR__ . '/vendor/autoload.php';

require_once "ratepay_credentials.php";

require_once "helper.createTransaction.php"; // Opens new transaction to handle
require_once "helper.deliverTransaction.php"; // Delivers transaction to handle

/************************************************************
 * The PaymentChange alters order details (shopping basket) *
 * The subtype Return commits all canceled articles         *
 ************************************************************/

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
 * The PaymentChange Return (PCh) requires returned/refunded articles of an order.
 * It has to be send after the ConfirmationDeliver
 * In case of partial return, multiple PCh requests are possible.
 */
$shoppingBasket = [
    'ShoppingBasket' => [
        'Items' => [
            [
                'Item' => [
                    'Description' => "Test product 1",
                    'ArticleNumber' => "ArtNo1",
                    'Quantity' => 1,
                    'UnitPriceGross' => 300,
                    'TaxRate' => 19,
                ]
            ], [
                'Item' => [
                    'Description' => "Test product 2",
                    'ArticleNumber' => "ArtNo2",
                    'Quantity' => 2,
                    'UnitPriceGross' => 100,
                    'TaxRate' => 19,
                    'Discount' => 10
                ]
            ]
        ]
    ]
];

$mbContent = new RatePAY\ModelBuilder('Content');
$mbContent->setArray($shoppingBasket);

// PaymentChange has to be specified by subtype
$pChReturn = $rb->callPaymentChange($mbHead, $mbContent)->subtype('return');

if (!$pChReturn->isSuccessful()) die("PaymentChange not successful");

var_dump("PaymentChange successful");

// PaymentChange response object provides no specific methods


/*********************************************************************************************************************************
 * The library throws decidedly exceptions. It's recommended to surround model building and request calls with try-catch-blocks. *
 *********************************************************************************************************************************/