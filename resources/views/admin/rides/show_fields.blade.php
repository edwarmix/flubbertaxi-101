<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            {!! Form::label('date', __('Date') . ':') !!}
            <p>{{ $ride->created_at->format(getLocaleDateFormat(true)) }}</p>
        </div>
        <!-- Driver Id Field -->
        <div class="form-group">
            {!! Form::label('driver_id', __('Driver') . ':') !!}
            <p>
                {{ __('Name') . ': ' . $ride->driver->user->name }}<br>
                {{ __('Phone') . ': ' . $ride->driver->user->phone }}<br>
                {{ __('Email') . ': ' . $ride->driver->user->email }}<br>
            </p>
        </div>
        <!-- Customer Id Field -->
        <div class="form-group">
            {!! Form::label('user_id', __('Customer:')) !!}
            <p>
                {{ __('Name') . ': ' . $ride->user->name }}<br>
                {{ __('Phone') . ': ' . $ride->user->phone }}<br>
                {{ __('Email') . ': ' . $ride->user->email }}<br>
            </p>
        </div>

    </div>
    <div class="col-md-6">
        <div class="row">
            <div class="col-sm-3">
                <!-- Distance Field -->
                <div class="form-group">
                    {!! Form::label('distance', __('Distance') . ':') !!}
                    <p>{{ $ride->distance }} {{ setting('distance_unit', 'mi') }}</p>
                </div>
            </div>
            <div class="col-sm-3">
                <!-- Driver Value Field -->
                <div class="form-group">
                    {!! Form::label('driver_value', __('Driver value') . ':') !!}
                    <p>{!! getPrice($ride->driver_value) !!}</p>
                </div>

            </div>
            <div class="col-sm-3">
                <!-- App Value Field -->
                <div class="form-group">
                    {!! Form::label('app_value', __('App value') . ':') !!}
                    <p>{!! getPrice($ride->app_value) !!}</p>
                </div>
            </div>
            <div class="col-sm-3">
                <!-- Total Value Field -->
                <div class="form-group">
                    {!! Form::label('total_value', __('Total') . ':') !!}
                    <p>{!! getPrice($ride->total_value) !!}</p>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-6">
                <!-- Ride Status Field -->
                <div class="form-group">
                    {!! Form::label('ride_status', __('Ride status:')) !!}
                    <p>{{ trans('general.ride_status_list.' . $ride->ride_status) }}</p>
                </div>
            </div>
            <div class="col-sm-6">
                <!-- Ride Status Date Field -->
                <div class="form-group">
                    {!! Form::label('ride_status_date', __('Ride status date:')) !!}
                    <p>{{ $ride->ride_status_date->format(getLocaleDateFormat(true)) }}</p>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-4">
                <!-- Payment Status Field -->
                <div class="form-group">
                    {!! Form::label('payment_status', __('Payment status:')) !!}
                    <p>{{ trans('general.ride_status_list.' . $ride->payment_status) }}</p>
                </div>
            </div>
            <div class="col-sm-4">
                <!-- Payment Status Date Field -->
                <div class="form-group">
                    {!! Form::label('payment_status_date', __('Payment status date:')) !!}
                    <p>{{ $ride->payment_status_date->format(getLocaleDateFormat(true)) }}</p>
                </div>
            </div>
            <div class="col-sm-4">
                <!-- Payment Method Field -->
                <div class="form-group">
                    {!! Form::label('payment_method', __('Payment method:')) !!}
                    <p>
                        @if ($ride->offline_payment_method_id != 0)
                            {{ $ride->offlinePaymentMethod->name }}
                        @else
                            {{ $ride->payment_gateway }} ({{ $ride->gateway_id }})
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row" id="adressesContainer">

</div>
<div class="row">
    <div class="col-md-6">
        @if (!empty($ride->customer_observation))
            <!-- Customer Observation Field -->
            <div class="form-group">
                {!! Form::label('customer_observation', __('Customer Observation') . ':') !!}
                <p>{{ $ride->customer_observation }}</p>
            </div>
        @endif
    </div>
    <div class="col-md-6" id="mapsHere" style="min-height: 300px">

    </div>
</div>
