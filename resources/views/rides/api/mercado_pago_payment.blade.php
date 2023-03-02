<script src="https://sdk.mercadopago.com/js/v2"></script>
<script>
    initialize();

    async function initialize() {
        const mp = new MercadoPago('{{ setting('mercado_pago_key') }}');
        const {
            preference
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
        mp.checkout({
            preference: {
                id: preference
            },
            autoOpen: true,
        });

    }
</script>
