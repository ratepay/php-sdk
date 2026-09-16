<?php

require __DIR__ . '/../../vendor/autoload.php';

require_once "../../ratepay_credentials.php";

function sendPdfResponse($secciRequest) {
    header('Access-Control-Expose-Headers: Content-Disposition');
    header('Content-Type: ' . $secciRequest->getContentType());
    header('Content-Disposition: attachment; filename="SECCI-'.$secciRequest->getDocumentId().'.pdf"');
    header('Content-Length: ' . strlen($secciRequest->getDocument()));
    header('Cache-Control: private, max-age=0, must-revalidate');
    header('Pragma: public');

    echo $secciRequest->getDocument();
    exit;
}

function sendJsonResponse($response) {
    header('Content-Type: application/json');

    echo json_encode($response);
    exit;
}

$calculationAmount = $_REQUEST['calculationAmount'];
$calculationType = $_REQUEST['calculationType'];
$calculationValue = $_REQUEST['calculationValue'];

$method = isset($_REQUEST['method']) ? $_REQUEST['method'] : 'installment';
$deliveryMethod = isset($_REQUEST['deliverymethod']) ? strtolower($_REQUEST['deliverymethod']) : 'pdf';
$emailAddress = isset($_REQUEST['email']) ? $_REQUEST['email'] : null;

$secci = [
    'DeliveryMethod' => strtoupper($deliveryMethod),
    'Language' => 'DE',
    'CountryCode' => 'DE',
];

if ($deliveryMethod == 'email') {
    $secci['Email'] = $emailAddress;
} elseif ($deliveryMethod == 'pdf') {
    $secci['Action'] = 'PRINT';
}

$installmentPlan = null;
if ($method == 'installment') {
    $ib = new RatePAY\Frontend\InstallmentBuilder(true); // true = sandbox mode
    $ib->setProfileId(PROFILE_ID);
    $ib->setSecuritycode(SECURITYCODE);
    $installmentPlan = $ib->getInstallmentPlanAsJson($calculationAmount, $calculationType, $calculationValue);

    if (empty($installmentPlan)) {
        sendJsonResponse([
            'success' => false,
            'message' => 'SecciRequest not successful',
        ]);
    }

    $installmentPlan = json_decode($installmentPlan, true);
}


// ProfileRequest needs 'head' only
$mbHead = new RatePAY\ModelBuilder();
$mbHead->setArray([
    'SystemId' => "Example",
    'Credential' => [
        'ProfileId' => PROFILE_ID,
        'Securitycode' => SECURITYCODE
    ]
]);

$requestData = [
    'ShoppingBasket' => [
        'Items' => [
            [
                'Item' => [
                    'Description' => "Testartikel",
                    'ArticleNumber' => "123",
                    'Quantity' => 1,
                    'UnitPriceGross' => 1995.00,
                    'TaxRate' => 19,
                ]
            ],
        ],
        'Shipping' => [
            'Description' => "Versandkosten",
            'UnitPriceGross' => 5.00,
            'TaxRate' => 19,
        ],
    ],
    'Secci' => $secci,
    'Payment' => [
        'Currency' => 'EUR',
        'Method' => strtoupper($method),
        'Amount' => 2000.00,
    ],
];

if (!empty($installmentPlan)) {
    $requestData['Payment']['InstallmentDetails'] = [
        'InstallmentNumber' => $installmentPlan['numberOfRatesFull'],
        'InstallmentAmount' => $installmentPlan['rate'],
        'LastInstallmentAmount' => $installmentPlan['lastRate'],
        'InterestRate' => $installmentPlan['interestRate'],
        'PaymentFirstday' => $installmentPlan['paymentFirstday'],
    ];
    $requestData['Payment']['DebitPayType'] = "DIRECT-DEBIT";
}

// Building content model
$mbContent = new RatePAY\ModelBuilder('Content');
$mbContent->setArray($requestData);

$rb = new RatePAY\RequestBuilder(true); // Sandbox mode = true

$secciRequest = $rb->callSecciRequest($mbHead, $mbContent);

error_log(date("Y-m-d H:i:s - ").print_r($secciRequest, true).PHP_EOL, 3, __DIR__."/result.log");

if (!$secciRequest->isSuccessful()) {
    sendJsonResponse([
        'success' => false,
        'message' => 'SecciRequest not successful',
    ]);
}

if ($deliveryMethod == 'pdf') {
    sendPdfResponse($secciRequest);
} elseif ($deliveryMethod == 'email') {
    sendJsonResponse([
        'success' => true,
        'message' => 'SECCI-'.$secciRequest->getDocumentId().'.pdf has been sent to '.$emailAddress,
    ]);
}