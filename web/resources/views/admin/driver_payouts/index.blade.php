@extends('layouts.app')
@section('title')
    {{ __('Driver Payouts') }}
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Driver Payouts') }}</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('admin.driverPayouts.create') }}" class="btn btn-primary form-btn">{{ __('Driver Payout') }}
                    <i class="fas fa-plus"></i></a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Drivers Payout List') }}</h4>
                </div>

                <div class="card-body">
                    @include('flash::message')
                    @include('stisla-templates::common.errors')
                    @include('admin.driver_payouts.table')
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <h4>{{ __('Drivers Payout Summary') }}</h4>
                </div>

                <div class="card-body">
                    <!-- payment_methods Field -->
                    <div class="form-group col-sm-6">
                        {!! Form::label('payment_methods[]', __('Filter by Payment Methods') . ':') !!}
                        {!! Form::select(
                            'payment_methods[]',
                            $paymentsArray,
                            ['all'],
                            [
                                'id' => 'payment_methods',
                                'class' => 'form-control select2',
                                'multiple' => 'multiple',
                            ],
                        ) !!}
                    </div>
                    @include('admin.driver_payouts.summary')
                </div>
            </div>
        </div>

    </section>
@endsection

@push('scripts')
    <script type="text/javascript">
        var onlinePayments = {!! json_encode($onlinePayments) !!};
        var offlinePayments = {!! json_encode($offlinePayments) !!};
        $('#payment_methods').on('select2:select', function(e) {
            var selectedValues = $(this).val();
            var data = e.params.data;
            if (data.id == 'all') {
                $('#payment_methods').val(data.id).trigger('change');
            } else if (data.id == 'all_online_payments') {
                selectedValues = selectedValues.filter(function(el) {
                    return Object.keys(onlinePayments).indexOf(el) < 0;
                });
                $('#payment_methods').val(selectedValues).trigger('change');
            } else if (data.id == 'all_offline_payments') {
                selectedValues = selectedValues.filter(function(el) {
                    return Object.keys(offlinePayments).indexOf(el) < 0;
                });
                $('#payment_methods').val(selectedValues).trigger('change');
            }
            $('#driver-payouts-summary-table').DataTable().ajax.reload();
        });
        $('#payment_methods').on('select2:unselect', function(e) {
            $('#driver-payouts-summary-table').DataTable().ajax.reload();
        });
        $('#payment_methods').on('select2:selecting', function(e) {
            var selectedValues = $(this).val();
            var data = e.params.args.data;
            if (selectedValues.includes("all_online_payments") && Object.keys(onlinePayments).includes(data.id)) {
                selectedValues = selectedValues.filter(function(el) {
                    return el != "all_online_payments";
                });
                $('#payment_methods').val(selectedValues).trigger('change');
            } else if (selectedValues.includes("all_offline_payments") && Object.keys(offlinePayments).includes(data
                    .id)) {
                selectedValues = selectedValues.filter(function(el) {
                    return el != "all_offline_payments";
                });
                $('#payment_methods').val(selectedValues).trigger('change');
            } else if (selectedValues.includes("all")) {
                $('#payment_methods').val('').trigger('change');
            }
        });
    </script>
@endpush
