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
            <button type="button" id="start-payment-button" onclick="makePayment()">Pay Now</button>
            <div id="payment-container" style="margin: 0 auto;"></div>
            <div id="payment-message" class="hidden"></div>
        </div>
    </div>
    <style>
        .vertical-center {
            min-height: 100%;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
    </style>
    <script src="https://checkout.flutterwave.com/v3.js"></script>
    <script>
        makePayment();

        function makePayment() {
            FlutterwaveCheckout({
                public_key: "{{ setting('flutterwave_key') }}",
                tx_ref: "{{ $ride->id }}-{{ Str::random(10) }}",
                amount: {{ number_format($ride->total_value, 2, '.', '') }},
                currency: "{{ setting('currency', 'USD') }}",
                meta: {
                    ride_id: '{{ $ride->id }}',
                },
                customer: {
                    email: "{{ $ride->user->email }}",
                    phone_number: "{{ $ride->user->phone }}",
                    name: "{{ $ride->user->name }}",
                },
                customizations: {
                    title: "{{ setting('APP_NAME') }}",
                    description: "{{ __('Payment of ride') . ' ' . $ride->id }}",
                    logo: " {{ $appLogo }}",
                },
            });
        }
    </script>
</body>
