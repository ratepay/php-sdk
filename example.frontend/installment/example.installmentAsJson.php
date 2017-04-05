<?php

require __DIR__ . '/../../vendor/autoload.php';

require_once "../../ratepay_credentials.php";

$orderAmount = 2000;

$ib = new RatePAY\Frontend\InstallmentBuilder(true); // true = sandbox mode
$ib->setProfileId(PROFILE_ID);
$ib->setSecuritycode(SECURITYCODE);
//$ib->setLanguage("DE");
//$ib->setBillingCountry("DE");
var_dump($ib->getInstallmentCalculatorAsJson($orderAmount));