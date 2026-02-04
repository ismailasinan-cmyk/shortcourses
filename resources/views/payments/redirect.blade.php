<!DOCTYPE html>
<html>
<head>
    <title>Redirecting to Remita...</title>
</head>
<body onload="makePayment()">
    <p>Loading Remita Payment Gateway...</p>
    
    <script type="text/javascript" src="https://demo.remita.net/payment/v1/remita-pay-inline.bundle.js"></script>
    <script type="text/javascript">
        function makePayment() {
            var paymentEngine = RmPaymentEngine.init({
                key: "{{ $publicKey }}",
                rrr: "{{ $rrr }}",
                processRrr: true,
                transactionId: "{{ $time ?? time() }}",
                onSuccess: function (response) {
                    console.log('callback Successful Response', response);
                    window.location.href = "{{ $responseUrl }}?RRR={{ $rrr }}";
                },
                onError: function (response) {
                    console.log('callback Error Response', response);
                    alert("Payment Failed: " + (response.message || "Unknown error"));
                },
                onClose: function () {
                    console.log("Payment widget closed");
                    window.location.href = "{{ route('home') }}";
                }
            });
            paymentEngine.showPaymentWidget();
        }
    </script>
</body>
</html>
