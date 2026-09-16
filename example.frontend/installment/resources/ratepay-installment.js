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

    $rp(document).on("click","#rp-show-installment-plan-details", function() {
        $rp('.rp-installment-plan-details').show();
        $rp('.rp-installment-plan-no-details').hide();
        $rp(this).hide();
        $rp('#rp-hide-installment-plan-details').show();
    });

    $rp(document).on("click","#rp-hide-installment-plan-details", function() {
        $rp('.rp-installment-plan-details').hide();
        $rp('.rp-installment-plan-no-details').show();
        $rp(this).hide();
        $rp('#rp-show-installment-plan-details').show();
    });

    $rp(document).on("click","#rp-ccd2-learn-more", function() {
        $rp('#rp-hide-secci-details').toggle();
    });

    $rp(document).on("click","#rp-ccd2-btn-email", function() {
        $rp('#rp-show-secci-choose-text').hide();
        $rp('#rp-hide-secci-choose-email').show();
        $rp(this).parent().hide();
    });


    $rp("#rp-payment-firstday").val((rpDirectDebitAllowed == "1") ? rpDirectDebitFirstday : rpBankTransferFirstday);
});

if (document.getElementById('rp-ccd2-btn-pdf')) {
    document.getElementById('rp-ccd2-btn-pdf').addEventListener('click', function() {
        const params = new URLSearchParams();
        params.append('calculationAmount', document.getElementById('rp-calculation-amount').value);
        params.append('calculationType', document.getElementById('rp-calculation-type').value);
        params.append('calculationValue', document.getElementById('rp-calculation-value').value);

        fetch(rpSecciController, {
            method: 'POST',
            body: params
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const disposition = response.headers.get('Content-Disposition');
                let filename = 'dokument.pdf';

                if (disposition && disposition.includes('filename=')) {
                    // Regex to extract the filename from the header string
                    const filenameMatch = disposition.match(/filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/);
                    if (filenameMatch && filenameMatch[1]) {
                        // Remove quotes
                        filename = filenameMatch[1].replace(/['"]/g, '');
                    }
                }

                return response.blob().then(blob => ({ blob, filename }));
            })
            .then(({ blob, filename }) => {
                const downloadUrl = window.URL.createObjectURL(blob);

                const a = document.createElement('a');
                a.style.display = 'none';
                a.href = downloadUrl;
                a.download = filename;

                document.body.appendChild(a);
                a.click();

                a.remove();
                window.URL.revokeObjectURL(downloadUrl);
            })
            .catch(error => {
                console.error('Fehler beim PDF-Download:', error);
            });
    });
}

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

            $rp('#rp-ccd2-container').show();

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