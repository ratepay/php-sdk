<?php

require __DIR__ . '/../../vendor/autoload.php';

// Initialize DFP builder by setting your snippet id and a unique identifier (like customer id, order id, etc)
$dfp = new RatePAY\Frontend\deviceFingerprintBuilder("ratepay", rand(1, 99));
var_dump($dfp->getToken()); // Get unique DFP token. You need to transmit this token through the PaymentRequest to RatePAY.
var_dump($dfp->getDfpSnippetCode()); // Get DFP snippet code (JavaScript) which has to be placed into the checkout. (Set true as parameter to make the return compatible with smarty template engine)