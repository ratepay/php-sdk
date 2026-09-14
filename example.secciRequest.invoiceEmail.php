<?php

require __DIR__ . '/vendor/autoload.php';

require_once "ratepay_credentials.php";

/****************************************************
 * The SecciRequest requests the CCD2 SECCI information *
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

// Building content model
$mbContent = new RatePAY\ModelBuilder('Content');
$mbContent->setArray([
    'ShoppingBasket' => [
        'Items' => [
            [
                'Item' => [
                    'Description' => "Artikel A",
                    'ArticleNumber' => "123",
                    'Quantity' => 6,
                    'UnitPriceGross' => 10.00,
                    'TaxRate' => 19,
                ]
            ], [
                'Item' => [
                    'Description' => "Artikel B",
                    'ArticleNumber' => "234",
                    'Quantity' => 1,
                    'UnitPriceGross' => 100.00,
                    'TaxRate' => 19,
                ]
            ], [
                'Item' => [
                    'Description' => "Artikel C",
                    'ArticleNumber' => "345",
                    'Quantity' => 2,
                    'UnitPriceGross' => 50.00,
                    'TaxRate' => 19,
                    'Discount' => -10.00,
                ]
            ]
        ],
        'Shipping' => [
            'Description' => "Versandkosten",
            'UnitPriceGross' => 5.00,
            'TaxRate' => 19,
        ],
        'Discount' => [
            'Description' => "Rabatt",
            'UnitPriceGross' => -15.00,
            'TaxRate' => 19,
        ]
    ],
    'Secci' => [
        'PaymentMethod' => 'INVOICE',
        'DeliveryMethod' => 'EMAIL',
        'Email' => 'abc@gmail.com',
        'Language' => 'DE',
        'Country' => 'DE',
    ],
    'Payment' => [
        'Currency' => 'EUR',
        'Method' => 'INVOICE',
        'Amount' => 230.00,
    ],
]);

$rb = new RatePAY\RequestBuilder(true); // Sandbox mode = true

$profileRequest = $rb->callSecciRequest($mbHead, $mbContent);

if (!$profileRequest->isSuccessful()) die("SecciRequest not successful");

var_dump($profileRequest->getResult());

// The ProfileRequest response object provides no specific methods

/*********************************************************************************************************************************
 * The library throws decidedly exceptions. It's recommended to surround model building and request calls with try-catch-blocks. *
 *********************************************************************************************************************************/
