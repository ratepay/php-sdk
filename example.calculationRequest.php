<?php

require __DIR__ . '/vendor/autoload.php';

require_once "ratepay_credentials.php";

/**********************************************************
 * The CalculationRequest the installment configuration *
 **********************************************************/

// CalculationRequest needs 'head' and 'content'
$mbHead = new RatePAY\ModelBuilder();
$mbHead->setArray([
    'SystemId' => "Example",
    'Credential' => [
        'ProfileId' => PROFILE_ID,
        'Securitycode' => SECURITYCODE
    ]
]);

// CalculationRequest provides two types of operation: calculation by time and calculation by rate
// Example: calculation by time
$mbContentTime = new RatePAY\ModelBuilder('Content');
$mbContentTime->setArray([
    'InstallmentCalculation' => [
        'Amount' => 464.95,
        'CalculationTime' => [
            'Month' => 6
        ]
    ]
]);

$rb = new RatePAY\RequestBuilder(true); // Sandbox mode = true

// CalculationRequest has to be specified by subtype
$calculationRequest = $rb->callCalculationRequest($mbHead, $mbContentTime)->subtype('calculation-by-time');

if (!$calculationRequest->isSuccessful()) die("CalculationRequest not successful");

var_dump("Example: calculation by time");

var_dump($calculationRequest->getReasonMessage()); // Returns RatePAY (technical) reason message {string}
var_dump($calculationRequest->getResult()); // Returns whole result {array}

// The CalculationRequest response object provides following methods:
// getPaymentAmount(); // Returns total amount (basket + installment fee) (for PaymentRequest payment section) {float}
// getInstallmentNumber(); // Returns number of rates for installment details (for PaymentRequest payment section) {int}
// getInstallmentAmount(); // Returns rate (for PaymentRequest payment section) {float}
// getLastInstallmentAmount(); // Returns last rate (for PaymentRequest payment section) {float}
// getInterestRate(); // Returns interest rate {float}
// getPaymentFirstday(); // Returns payment firstday {int}

var_dump($calculationRequest->getPaymentAmount());
var_dump($calculationRequest->getInstallmentNumber());
var_dump($calculationRequest->getInstallmentAmount());
var_dump($calculationRequest->getLastInstallmentAmount());
var_dump($calculationRequest->getInterestRate());
var_dump($calculationRequest->getPaymentFirstday());



// Example: calculation by rate
var_dump("Example: calculation by rate");

$mbContentRate = new RatePAY\ModelBuilder('Content');
$mbContentRate->setArray([
    'InstallmentCalculation' => [
        'Amount' => 500,
        'CalculationRate' => [
            'Rate' => 50
        ]
    ]
]);

$calculationRequest = $rb->callCalculationRequest($mbHead, $mbContentRate)->subtype('calculation-by-rate');

var_dump($calculationRequest->getReasonMessage());
var_dump($calculationRequest->getResult());

var_dump($calculationRequest->getPaymentAmount());
var_dump($calculationRequest->getInstallmentNumber());
var_dump($calculationRequest->getInstallmentAmount());
var_dump($calculationRequest->getLastInstallmentAmount());
var_dump($calculationRequest->getInterestRate());
var_dump($calculationRequest->getPaymentFirstday());


/*********************************************************************************************************************************
 * The library throws decidedly exceptions. It's recommended to surround model building and request calls with try-catch-blocks. *
 *********************************************************************************************************************************/