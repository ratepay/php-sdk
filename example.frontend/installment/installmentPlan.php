<?php

require __DIR__ . '/../../vendor/autoload.php';

require_once "../../ratepay_credentials.php";

$calculationAmount = $_GET['calculationAmount'];
$calculationType = $_GET['calculationType'];
$calculationValue = $_GET['calculationValue'];
$paymentFirstday = $_GET['paymentFirstday'];

$htmlStringOrTemplate = file_get_contents("template.installmentPlan.html");

$ib = new RatePAY\Frontend\InstallmentBuilder(true);
$ib->setProfileId(PROFILE_ID);
$ib->setSecuritycode(SECURITYCODE);
echo $ib->getInstallmentPlanByTemplate(
    $htmlStringOrTemplate,
    $calculationAmount,
    $calculationType,
    $calculationValue,
    $paymentFirstday
);