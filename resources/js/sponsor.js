document.addEventListener("DOMContentLoaded", function () {
    var form = document.querySelector('#payment-form');
    var client_token = document.querySelector('#client_token').value;

    braintree.dropin.create({
        authorization: client_token,
        selector: '#bt-dropin',
        paypal: {
            flow: 'vault'
        }
    }, function (createErr, instance) {

        if (createErr) {
            console.log('Create Error', createErr);
            return;
        }

        form.addEventListener('submit', function (event) {
            event.preventDefault();

            instance.requestPaymentMethod(function (err, payload) {
                if (err) {
                    console.log('Request Payment Method Error', err);
                    return;
                }

                // Add the nonce to the form and submit
                document.querySelector('#nonce').value = payload.nonce;
                form.submit();
            });
        });
    });
});

    