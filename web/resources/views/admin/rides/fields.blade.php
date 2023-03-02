<!-- User Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('user_id', __('Customer') . ':') !!}
    {!! Form::select('user_id', isset($customer) ? [$customer] : [], null, [
        'id' => 'customer_id',
        'class' => 'form-control',
    ]) !!}
</div>

<!-- Driver Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('driver_id', __('Driver') . ':') !!}
    {!! Form::select('driver_id', isset($driver) ? [$driver] : [], null, [
        'id' => 'driver_id',
        'class' => 'form-control',
    ]) !!}
</div>

<!-- Pickup Location Field -->
<div class="form-group col-sm-12">
    <input type="hidden" name="boarding_address_data" value='{{ $ride->boarding_location_data }}'>
    {!! Form::label('boarding_location', __('Pickup Location') . ':') !!}
    {!! Form::text('boarding_location', null, [
        'class' => 'form-control',
        'id' => 'inputCollectPlace',
        'maxlength' => 255,
        'maxlength' => 255,
    ]) !!}
</div>

<!-- Save Pickup Location For Next Ride Field -->
<div class="form-group col-sm-12" style="">
    <div class="form-check">
        <input type="hidden" name="save_boarding_location_for_next_ride" value="0">
        {!! Form::checkbox('save_boarding_location_for_next_ride', '1', null, [
            'id' => 'save_boarding_location_for_next_ride',
        ]) !!}
        <label class="form-check-label" for="save_boarding_location_for_next_ride">
            {{ __('Save pickup location for next ride') }}
        </label>
    </div>
</div>

<div class="form-group col-sm-12">
    <input type="hidden" name="destination_address_data" value='{{ $ride->destination_location_data }}'>
    {!! Form::label('destination_location', __('Destination Location') . ':') !!}
    {!! Form::text('destination_location', ((json_decode($ride->destination_location_data) != null) ? json_decode($ride->destination_location_data)->formatted_address : ''), [
        'class' => 'form-control',
        'id' => 'inputRidePlace',
        'maxlength' => 255,
        'maxlength' => 255,
    ]) !!}
</div>

<!-- Distance Field -->
<div class="form-group col-sm-6">
    {!! Form::label('distance', __('Distance') . ':') !!}
    {!! Form::number('distance', null, ['class' => 'form-control', 'step' => 0.001, 'min' => 0]) !!}
</div>

<!-- Driver Value Field -->
<div class="form-group col-sm-6">
    {!! Form::label('driver_value', __('Driver Value') . ':') !!}
    {!! Form::number('driver_value', null, ['class' => 'form-control', 'step' => 0.01, 'min' => 0]) !!}
</div>

<!-- App Value Field -->
<div class="form-group col-sm-6">
    {!! Form::label('app_value', __('App Value') . ':') !!}
    {!! Form::number('app_value', null, ['class' => 'form-control', 'step' => 0.01, 'min' => 0]) !!}
</div>

<!-- Customer Observation Field -->
<div class="form-group col-sm-12">
    {!! Form::label('customer_observation', __('Customer Observation') . ':') !!}
    {!! Form::textarea('customer_observation', null, ['class' => 'form-control', 'style' => 'min-height:100px']) !!}
</div>
<!-- Offline Payment Method Id Field -->
<div class="form-group col-sm-6">
    {!! Form::label('offline_payment_method_id', __('Offline Payment Method') . ':') !!}
    {!! Form::select('offline_payment_method_id', [0 => 'None'] + $offlinePaymentMethods->toArray(), null, [
        'class' => 'form-control select2',
    ]) !!}
</div>

<!-- Payment Gateway Field -->
<div class="form-group col-sm-6">
    {!! Form::label('payment_gateway', __('Payment Gateway') . ':') !!}
    {!! Form::select('payment_gateway', ['' => 'None'] + getAvailablePaymentGatewaysArray(), null, [
        'class' => 'form-control',
        'maxlength' => 255,
        'maxlength' => 255,
    ]) !!}
</div>
<!-- Payment Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('payment_status', __('Payment Status') . ':') !!}
    {!! Form::select('payment_status', getAvailablePaymentStatusArray(), null, ['class' => 'form-control select2']) !!}
</div>

<!-- Ride Status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('ride_status', __('Ride Status') . ':') !!}
    {!! Form::select('ride_status', getAvailableRideStatusArray(), null, ['class' => 'form-control select']) !!}
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(__('Save'), ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('admin.rides.index') }}" class="btn btn-light">{{ __('crud.cancel') }}</a>
</div>
