<?php

require __DIR__ . '/../../vendor/autoload.php';

require_once "../../ratepay_credentials.php";

$calculationAmount = 2000; // Amount of order
$calculationType = "time"; // Calculation type: rate or time
$calculationValue = 6; // Calculation value: if type time = requested runtime (months) {integer} | if type rate = requested installment rate (amount) {integer}

$ib = new RatePAY\Frontend\InstallmentBuilder(true); // true = sandbox mode
$ib->setProfileId(PROFILE_ID);
$ib->setSecuritycode(SECURITYCODE);
echo $ib->getInstallmentPlanAsJson($calculationAmount, $calculationType, $calculationValue);