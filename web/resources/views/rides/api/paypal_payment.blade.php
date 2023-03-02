<body class="@if (setting('language_rtl', false)) text-right @endif"
    dir="{{ setting('language_rtl', false) ? 'rtl' : 'ltr' }}">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Template CSS -->
    <link href="{{ asset('assets/css/sweetalert.css') }}" rel="stylesheet" type="text/css" />
    <div class="row mx-1 vertical-center">
        <div class="col-12 text-center">
            <h5 style="color: #777777">
                {{ __('Payment Pending') }}<br>
                <small>{{ number_format($ride->distance, 3, '.', '') }} {{ setting('distance_unit', 'mi') }} -
                    {!! getPrice($ride->total_value) !!}</small>
            </h5>
            <div id="payment-container" style="margin: 0 auto;"></div>

            <div id="payment-message" class="hidden"></div>

        </div>
    </div>
    <script src="https://www.paypal.com/sdk/js?client-id={{ setting('paypal_key') }}&currency={{ setting('currency') }}">
    </script>
    <style>
        .vertical-center {
            min-height: 100%;
            min-height: 100vh;

            display: flex;
            align-items: center;
        }
    </style>
    <script>
        paypal.Buttons({
            createRide: (data, actions) => {
                return actions.ride.create({
                    purchase_units: [{
                        custom_id: '{{ $ride->id }}',
                        amount: {
                            currency_code: "{{ setting('currency') }}",
                            value: '{{ $ride->total_value }}'
                        }
                    }]
                });
            },
            onApprove: (data, actions) => {
                return actions.ride.capture().then(function(orderData) {
                    // Successful capture! For dev/demo purposes:
                    const transaction = orderData.purchase_units[0].payments.captures[0];
                    //alert(`Transaction ${transaction.status}: ${transaction.id}\n\nSee console for all available details`);
                    //console.log(orderData.id);

                });
            }
        }).render('#payment-container');
    </script>
</body>
