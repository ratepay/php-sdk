$rp(function() {
    // initialize visibility

    // starting with hidden sepa form (visible a after rate calculation)
    $rp('.rp-sepa-form').hide();
    $rp('.rp-payment-type-switch').hide();

    if (rpDirectDebitAllowed == "1") {
        $rp('#rp-payment-type').val("DIRECT-DEBIT");
    } else {
        // if direct debit is not allowed 'BANK_TRANSFER' is set by default
        $rp('#rp-payment-type').val("BANK-TRANSFER");
    }

    // show sepa agreement
    /*$rp('#rp-show-sepa-agreement').click(function() {
        $rp(this).hide();
        $rp('#rp-sepa-agreement').show();
    });*/

    // show bank code if account number is entered
    /*$rp('#rp-iban-account-number').keyup(function() {
        switchBankCodeInput($rp(this));
    });*/

    // hide sepa form
    $rp('#rp-switch-payment-type-bank-transfer').click(function() {
        switchPaymentType("bank-transfer");
    });

    // show sepa form
    $rp('#rp-switch-payment-type-direct-debit').click(function() {
        switchPaymentType("direct-debit");
    });
});

// show bank code if account number is entered
function switchBankCodeInput(element) {
    if (!jQuery.isNumeric(element.val()) || element.val() == "") {
        $rp('#rp-form-bank-code').hide();
        $rp('#rp-bank-code').prop('disabled', true);
    } else {
        $rp('#rp-form-bank-code').show();
        $rp('#rp-bank-code').prop('disabled', false);
    }
}

// switch between installment paymenttypes
function switchPaymentType(paymentType) {
    if (paymentType == "bank-transfer") {
        $rp('.rp-sepa-form').hide();
        $rp('#rp-switch-payment-type-bank-transfer').hide();
        $rp('#rp-switch-payment-type-direct-debit').show();
        $rp("#rp-payment-type").val("BANK-TRANSFER");
        $rp("#rp-payment-firstday").val(rpBankTransferFirstday);
    } else {
        $rp('.rp-sepa-form').show();
        //$rp('#rp-sepa-agreement').hide();
        $rp('#rp-switch-payment-type-direct-debit').hide();
        $rp('#rp-switch-payment-type-bank-transfer').show();
        $rp("#rp-payment-type").val("DIRECT-DEBIT");
        $rp("#rp-payment-firstday").val(rpDirectDebitFirstday);
    }
    // After changing payment type, re-call of installment plan because of changed firstday
    callInstallmentPlan($rp('#rp-calculation-type').val(), $rp('#rp-calculation-value').val());
}

