$rp(function() {
    $rp('.rp-btn-runtime').click(function() {
        callInstallmentPlan('time', $rp(this).data().bind);
        // marking and de-marking of buttons
        $rp('.rp-btn-runtime').removeClass('btn-info');
        $rp('.rp-btn-rate').removeClass('btn-info');
        $rp('#rp-rate-value').val("");
        $rp(this).addClass('btn-info');
    });

    $rp('.rp-btn-rate').click(function() {
        callInstallmentPlan('rate', $rp('#rp-rate-value').val());
        // de-marking of buttons
        $rp('.rp-btn-runtime').removeClass('btn-info');
    });

    $rp('#rp-rate-value').keyup(function() {
        if (!validateValue($rp('#rp-rate-value').val())) {
            $rp('.rp-btn-rate').prop('disabled', true);
        } else {
            $rp('.rp-btn-rate').prop('disabled', false);
        }
    });

    $rp("#rp-payment-firstday").val((rpDirectDebitAllowed == "1") ? rpDirectDebitFirstday : rpBankTransferFirstday);
});

function callInstallmentPlan(calcType, calcValue) {
    var params = "?"
        + "calculationAmount=" + $rp('#rp-calculation-amount').val()
        + "&calculationValue=" + calcValue
        + "&calculationType=" + calcType
        + "&paymentFirstday=" + $rp("#rp-payment-firstday").val();

    $rp.ajax(rpInstallmentController + params)
        .done(function(result) {
            // show filled calculation plan template
            $rp('#rpResultContainer').html(result);

            if (rpDirectDebitAllowed == "1" && $rp("#rp-payment-type").val() == "DIRECT-DEBIT") {
                $rp('.rp-sepa-form').show();
            }

            $rp('.rp-payment-type-switch').hide();
            // if payment type bank transfer is allowed show switch
            if (rpBankTransferAllowed == "1") {
                if ($rp("#rp-payment-type").val() == "DIRECT-DEBIT") {
                    $rp('#rp-switch-payment-type-bank-transfer').show();
                } else if (rpDirectDebitAllowed == "1") {
                    $rp('#rp-switch-payment-type-direct-debit').show();
                }
            }

            $rp('#rp-calculation-type').val(calcType);
            $rp('#rp-calculation-value').val(calcValue);
        })
        .fail(function() {
            alert( "error" );
        });
}

function validateValue(value) {
    if (value.length == 0) {
        return false;
    }
    if (!jQuery.isNumeric(value)) {
        return false;
    }

    return true;
}