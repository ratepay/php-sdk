<?php

require __DIR__ . '/vendor/autoload.php';

require_once "ratepay_credentials.php";

require_once "helper.createTransaction.php"; // Opens new transaction to handle

/**********************************************
 * The ConfirmationDeliver captures the order *
 **********************************************/

$mbHead = new RatePAY\ModelBuilder('head');
$mbHead->setArray([
    'SystemId' => "Example",
    'Credential' => [
        'ProfileId' => PROFILE_ID,
        'Securitycode' => SECURITYCODE
    ],
    'TransactionId' => $transactionId
]);

// Tracking information is optional
$tracking = [
    'External' => [
        'Tracking' => [
            'Id' => "123456",
            'Provider' => 'DHL'
        ]
    ]
];
$mbHead->setArray($tracking);

/*
 * The ConfirmationDeliver (CD) requires shipped articles of an order.
 * In case of split/partial shipping, multiple CD requests are possible.
 * If payment method 'installment' is used, it's recommended to send just one CD after order is completely shipped.
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
    ]
];

// Invoicing information is optional
$invoicing = [
    'Invoicing' => [
        'InvoiceId' => "123456",
        //'InvoiceDate' => date('Y-m-d\Th:m:s'),
        //'DeliveryDate' => date('Y-m-d\Th:m:s'),
        'DueDate' => date('Y-m-d\Th:m:s'),
    ]
];

$mbContent = new RatePAY\ModelBuilder('Content');
$mbContent->setArray($shoppingBasket);
$mbContent->setArray($invoicing);

$confirmationDeliver = $rb->callConfirmationDeliver($mbHead, $mbContent);

if (!$confirmationDeliver->isSuccessful()) die("ConfirmationDeliver not successful");

var_dump("ConfirmationDeliver successful");

// ConfirmationDeliver response object provides no specific methods


/*********************************************************************************************************************************
 * The library throws decidedly exceptions. It's recommended to surround model building and request calls with try-catch-blocks. *
 *********************************************************************************************************************************/