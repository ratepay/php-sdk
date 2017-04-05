<?php

require __DIR__ . '/../../vendor/autoload.php';

$htmlStringOrTemplate = file_get_contents("template.directDebit.html");

$ib = new RatePAY\Frontend\DirectDebitBuilder();
echo $ib->getSepaFormByTemplate($htmlStringOrTemplate);