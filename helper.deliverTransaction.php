<?php

require __DIR__ . '/vendor/autoload.php';

$confirmationDeliver = $rb->callConfirmationDeliver($mbHead, $mbContent);

if (!$paymentInit->isSuccessful()) die("ConfirmationDeliver not successful");