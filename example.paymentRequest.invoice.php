<?php

require __DIR__ . '/vendor/autoload.php';

require_once "ratepay_credentials.php";

/******************************************************
 * The PaymentRequest requests authorization of order *
 ******************************************************/

$mbHead = new RatePAY\ModelBuilder();
$mbHead->setArray([
    'SystemId' => "Example",
    'Credential' => [
        'ProfileId' => PROFILE_ID,
        'Securitycode' => SECURITYCODE
    ]
]);

$rb = new RatePAY\RequestBuilder(true);
$paymentInit = $rb->callPaymentInit($mbHead);
if (!$paymentInit->isSuccessful()) die("PaymentInit not successful");

// Extending head model with required customer device information
$mbHead->setTransactionId($paymentInit->getTransactionId());
$mbHead->setCustomerDevice(
    $mbHead->CustomerDevice()->setDeviceToken("1234567890")
);
// External commits additional information about customer and order. Is optional.
$mbHead->setArray([
    'External' => [
        'MerchantConsumerId' => "1234567", // Customer Id
        'OrderId' => "xyzabc"
        //'MerchantConsumerClassification' => "X"
        //'ShopLanguage' => "DEU"
        //'ReferenceId' => "xyzabc"
    ]
]);

// The PaymentRequest requires a content model.

// Building content model
$mbContent = new RatePAY\ModelBuilder('Content');
$contentArr = [
    'Customer' => [
        'Gender' => "f",
        //'Salutation' => "Mrs.",
        //'Title' => "Dr.",
        'FirstName' => "Alice",
        //'MiddleName' => "J.",
        'LastName' => "Nobodyknows",
        //'NameSuffix' => "Sen.",
        'DateOfBirth' => "1975-12-17",
        //'Nationality' => "DE",
        //'Language' => "DE",
        'IpAddress' => "127.0.0.1",
        'Addresses' => [
            [
                'Address' => [
                    'Type' => "billing",
                    'Street' => "Hive Street 12",
                    //'StreetAdditional' => "SubLevel 27",
                    //'StreetNumber' => "12",
                    'ZipCode' => "12345",
                    'City' => "Raccoon City",
                    'CountryCode' => "de",
                ]
            ], [
                'Address' => [
                    'Type' => "delivery",
                    //'Salutation' => "Mrs.",
                    'FirstName' => "Alice",
                    'LastName' => "Nobodyknows",
                    //'Company' => "Umbrella Corp.",
                    'Street' => "Hive Street 12",
                    //'StreetAdditional' => "SubLevel 27",
                    //'StreetNumber' => "12",
                    'ZipCode' => "12345",
                    'City' => "Raccoon City",
                    'CountryCode' => "de",
                ]
            ]
        ],
        'Contacts' => [
            'Email' => "alice@umbrella.tld",
            //'Mobile' => "0123 4567890",
            'Phone' => [
                //'AreaCode' => "012",
                'DirectDial' => "012 3456789"
            ],
            //'Fax' => "012 3456777",
        ],
        // 'CompanyName' => "Umbrella Corp.",
        // 'CompanyType' => "AG",
        // 'VatId' => "DE123456789",
        // 'CompanyId' => "HRB 123456X",
        // 'RegistryLocation' => "Raccoon City",
        // 'Homepage' => "http://www.umbrellacorporation.tld",
    ],
    'ShoppingBasket' => [
        'Items' => [
            [
                'Item' => [
                    'Description' => "Test product 1",
                    'ArticleNumber' => "ArtNo1",
                    //'UniqueArticleNumber' => "ArtNo1-variation123",
                    'Quantity' => 1,
                    'UnitPriceGross' => 300,
                    'TaxRate' => 19,
                    //'Category' => "Additional information about the product"
                    //'DescriptionAddition' => "Additional information about the product"
                ]
            ], [
                'Item' => [
                    'Description' => "Test product 2",
                    'ArticleNumber' => "ArtNo",
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
            //'DescriptionAddition' => "Additional information about the shipping"
        ],
        'Discount' => [
            'Description' => "Discount 20 EUR",
            'UnitPriceGross' => 20,
            'TaxRate' => 19,
            //'DescriptionAddition' => "Additional information about the discount"
        ]
    ],
    'Payment' => [
        'Method' => "invoice", // "installment", "elv", "prepayment"
        'Amount' => 464.95
    ]
];
$mbContent->setArray($contentArr);

$rb = new RatePAY\RequestBuilder(true); // Sandbox mode = true
$paymentRequest = $rb->callPaymentRequest($mbHead, $mbContent);

if (!$paymentRequest->isSuccessful()) die("PaymentRequest not successful");

// The PaymentRequest response object provides following methods:
// getTransactionId();   // Returns transaction id (unique transaction identifier) {string}
// isRetryAdmitted();    // Returns whether retry is admitted {boolean}
// getCustomerMessage(); // Returns customer message {string}

var_dump($paymentRequest->getTransactionId());
// If the order gets rejected by RatePAY because of invalid inputs (like invalid zip code or iban), a retry of order is allowed.
var_dump($paymentRequest->isRetryAdmitted());
// If retry of order is allowed, the there will be a customer message with further information.
var_dump($paymentRequest->getCustomerMessage());


// Example of rejection with allowed retry and customer message:

$mbHead = new RatePAY\ModelBuilder();
$mbHead->setArray([
    'SystemId' => "Example",
    'Credential' => [
        'ProfileId' => PROFILE_ID,
        'Securitycode' => SECURITYCODE
    ]
]);

$paymentInit = $rb->callPaymentInit($mbHead);

$mbHead->setTransactionId($paymentInit->getTransactionId());
$mbHead->setCustomerDevice(
    $mbHead->CustomerDevice()->setDeviceToken("1234567890")
);

$contentArr['Customer']['Addresses'][0]['Address']['ZipCode'] = "1234";
$mbContent->setArray($contentArr);

$rb = new RatePAY\RequestBuilder(true);
$paymentRequest = $rb->callPaymentRequest($mbHead, $mbContent);

var_dump($paymentRequest->isRetryAdmitted());
var_dump($paymentRequest->getCustomerMessage());


/*********************************************************************************************************************************
 * The library throws decidedly exceptions. It's recommended to surround model building and request calls with try-catch-blocks. *
 *********************************************************************************************************************************/
