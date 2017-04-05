<?php

require __DIR__ . '/../../vendor/autoload.php';

require_once "../../ratepay_credentials.php";

$orderAmount = 2000;
$htmlStringOrTemplate = file_get_contents("template.installmentCalculator.html");

$ib = new RatePAY\Frontend\InstallmentBuilder(true); // true = sandbox mode
$ib->setProfileId(PROFILE_ID);
$ib->setSecuritycode(SECURITYCODE);
echo $ib->getInstallmentCalculatorByTemplate($orderAmount, $htmlStringOrTemplate);