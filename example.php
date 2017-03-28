<?php

require __DIR__ . '/vendor/autoload.php';

require_once "ratepay_credentials.php";

// Every RatePAY gateway request requires a 'head' set of information.
// Some requests additionally requires a 'content' set.


// There are various methods of building a request model:
// It's possible to create and fill a request model by manual instantiation of every submodel. Chaining of setters is possible.
$m = (new RatePAY\Model\Request\SubModel\Head)
    ->setSystemId("Example")
    ->setCredential(
        (new RatePAY\Model\Request\SubModel\Head\Credential)
            ->setProfileId(PROFILE_ID)
            ->setSecuritycode(SECURITYCODE)
    );

// Using the ModelBuilder makes the instantiation a lot easier.
$mb = new RatePAY\ModelBuilder('Head');
$mb->setSystemId("Example")
   ->setCredential(
       $mb->Credential()
           ->setProfileId(PROFILE_ID)
           ->setSecuritycode(SECURITYCODE)
   );

// A alternative way to set the request model is to commit a multidimensional array.
// The instantiation of lower submodels is done automatically.
// This way ensures a higher performance and is RECOMMENDED to use.
$mbArr = new RatePAY\ModelBuilder('Head');
$mbArr->setArray([
    'SystemId' => "Example",
    'Credential' => [
        'ProfileId' => PROFILE_ID,
        'Securitycode' => SECURITYCODE
    ],
    'Meta' => [
        'Systems' => [
            'System' => [
                'Name' => 'Magento 2'
            ]
        ]
    ]
]);

// It's also possible to commit the information as JSON
$mbJson = new RatePAY\ModelBuilder();
$mbJson->setJson('{
    "SystemId": "Example",
    "Credential" : {
        "ProfileId" : "' . PROFILE_ID . '",
        "Securitycode" : "' . SECURITYCODE . '"
    }
}');

// Initiation of generic RequestBuilder object.
$rb = new RatePAY\RequestBuilder(true); // true == Sandbox mode

// $rb->getAvailableRequests();     // Returns a list of all available RatePAY gateway requests (covered by library) {array}

// $rb->setConnectionTimeout(10000); // Sets the number of milliseconds to wait while trying to connect {integer} | default:0 (no timeout)
// $rb->setExecutionTimeout(10000);  // Sets the maximum number of milliseconds to allow cURL functions to execute {integer} | default:0 (no timeout)
// $rb->setConnectionRetries(3);     // Sets the number of retries {integer} | default:0
// $rb->setRetryDelay(500);          // Sets the delay between retries in milliseconds {integer} | default:0
// $rb->setConnectionTimeout(10000)->setExecutionTimeout(10000); // Chaining is possible

// Call a request by the example of PaymentInit
$paymentInit = $rb->callPaymentInit($mbArr); // Initializes transaction

// After the request is called the return object provides several common response methods
// isSuccessful();            // Returns whether request was successful {boolean}
// getRequestRaw();           // Returns request as xml (e.g. for logging) {string}
// getResponseRaw();          // Returns response as xml (e.g. for logging) {string}
// getRequestXmlElement();    // Returns request as object {SimpleXMLElement}
// getResponseXmlElement();   // Returns response as object {SimpleXMLElement}
// getResponseTime();         // Returns response time in seconds {float}
// getStatusCode();           // Returns RatePAY status code {string}
// getStatusMessage();        // Returns RatePAY status message {string}
// getReasonCode();           // Returns RatePAY reason code {integer}
// getReasonMessage();        // Returns RatePAY reason message {string}
// getResultCode();           // Returns RatePAY result code {integer}
// getResultMessage();        // Returns RatePAY result message {string}
// getResult();               // Returns whole result {array}

var_dump($paymentInit->isSuccessful());
var_dump($paymentInit->getRequestRaw());
var_dump($paymentInit->getResponseRaw());
var_dump($paymentInit->getResponseTime());
var_dump($paymentInit->getStatusCode());
var_dump($paymentInit->getStatusMessage());
var_dump($paymentInit->getReasonCode());
var_dump($paymentInit->getReasonMessage());
var_dump($paymentInit->getResultCode());
var_dump($paymentInit->getResultMessage());
var_dump($paymentInit->getResult());

// Depending on the different request types, there are special response methods
// In case of PaymentInit there are following methods:
// getTransactionId(); // Returns transaction id (unique transaction identifier) {string}

var_dump($paymentInit->getTransactionId()); // Returns transaction id (unique transaction identifier) {string}


/*********************************************************************************************************************************
 * The library throws decidedly exceptions. It's recommended to surround model building and request calls with try-catch-blocks. *
 *********************************************************************************************************************************/