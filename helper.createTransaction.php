<?php

require __DIR__ . '/vendor/autoload.php';

require_once "ratepay_credentials.php";

// Helper opens a new transaction (PaymentInit & PaymentRequest) to use it in further requests.

$mbHead = new RatePAY\ModelBuilder();
$mbHead->setArray([
    'SystemId' => "Example",
    'Credential' => [
        'ProfileId' => PROFILE_ID,
        'Securitycode' => SECURITYCODE
    ],
]);

$rb = new RatePAY\RequestBuilder(true);
$paymentInit = $rb->callPaymentInit($mbHead);
if (!$paymentInit->isSuccessful()) die("PaymentInit not successful");

$transactionId = $paymentInit->getTransactionId();

$mbHead->setTransactionId($transactionId);
$mbHead->setCustomerDevice(
    $mbHead->CustomerDevice()->setDeviceToken("1234567890")
);

$mbContent = new RatePAY\ModelBuilder('Content');
$mbContent->setArray([
    'Customer' => [
        'Gender' => "f",
        'FirstName' => "Alice",
        'LastName' => "Nobodyknows",
        'DateOfBirth' => "1975-12-17",
        'IpAddress' => "127.0.0.1",
        'Addresses' => [
            [
                'Address' => [
                    'Type' => "billing",
                    'Street' => "Hive Street 12",
                    'ZipCode' => "12345",
                    'City' => "Raccoon City",
                    'CountryCode' => "de",
                ]
            ]
        ],
        'Contacts' => [
            'Email' => "alice@umbrella.tld",
            'Phone' => [
                'DirectDial' => "012 3456789"
            ],
        ],
    ],
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
        ],
        'Shipping' => [
            'Description' => "Shipping costs",
            'UnitPriceGross' => 4.95,
            'TaxRate' => 19,
        ],
        'Discount' => [
            'Description' => "Discount 20 EUR",
            'UnitPriceGross' => 20,
            'TaxRate' => 19,
        ]
    ],
    'Payment' => [
        'Method' => "invoice",
        'Amount' => 464.95
    ]
]);

$rb = new RatePAY\RequestBuilder(true);
$paymentRequest = $rb->callPaymentRequest($mbHead, $mbContent);
if (!$paymentRequest->isSuccessful()) die("PaymentRequest not successful");