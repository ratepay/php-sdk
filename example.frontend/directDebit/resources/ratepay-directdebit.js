$rp(function() {
    // initialize visibility
    //switchBankCodeInput($rp('#rp-iban-account-number'));
    $rp('#rp-sepa-agreement').hide();

    // show sepa agreement
    $rp('#rp-show-sepa-agreement').click(function() {
        $rp(this).hide();
        $rp('#rp-sepa-agreement').show();
    });

    // show bank code if account number is entered
    /*$rp('#rp-iban-account-number').keyup(function() {
        switchBankCodeInput($rp(this));
    });*/

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

