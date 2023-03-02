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
            <button id="start-payment-button" class="btn btn-sm btn-outline-primary btn-block mb-3"
                target="_blank">{{ __('Pay Now') }}</button>
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
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        initialize();


        async function initialize() {
            const {
                payment_order_id
            } = await fetch("{{ url('api/rides/initializePayment') }}", {
                method: "POST",
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    ride_id: "{{ $ride->id }}",
                    api_token: "{{ auth()->user()->api_token }}"
                }),
            }).then((r) => r.json());

            var options = {
                "key": "{{ setting('razorpay_key') }}",
                "amount": "{{ intval($ride->total_value * 100) }}",
                "currency": "INR",
                "name": "{{ setting('APP_NAME') }}",
                "description": "{{ __('Payment of ride') . ' ' . $ride->id }}",
                "image": "{{ $appLogo }}",
                "callback_url": "{{ url('api/rides/' . $ride->id . '/success?api_token=' . auth()->user()->api_token) }}",
                "prefill": {
                    "name": "{{ $ride->user->name }}",
                    "email": "{{ $ride->user->email }}",
                },
                "order_id": payment_order_id,
                "notes": {
                    "ride_id": '{{ $ride->id }}',
                },
                "theme": {
                    "color": "#6777ef"
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.open();
            document.getElementById('start-payment-button').onclick = function(e) {
                rzp1.open();
                e.preventDefault();
            }
        }
    </script>
</body>
