@push('page_css')
    <link rel="stylesheet" href="{{ asset('plugins/iCheck/all.css') }}">
@endpush
<!-- User Id Field -->
<div class="form-group col-sm-4">
    {!! Form::label('user_id', 'User:') !!}
    {{ $driver->user->name }} <a href="{{ route('admin.users.edit', $driver->user->id) }}"
        class="btn btn-sm btn-primary">{{ __('Edit User') }}</a>
</div>

<!-- Active Field -->
<div class="form-group col-sm-6" style="padding-top: 15px">
    <div class="checkbox icheck">
        <label class="w-100 ml-2 form-check-inline">
            {!! Form::hidden('active', 0) !!}
            {!! Form::checkbox('active', 1, $driver->active) !!}
            <span class="ml-2">{{ __('Driver Active') }}</span>
        </label>
    </div>
</div>

<!-- status Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status', __('Status').':') !!}
    {!! Form::select('status', getAvailableDriverStatus(), null, ['class' => 'form-control select2']) !!}
</div>

<!-- Vehicle status_observation Field -->
<div class="form-group col-sm-6">
    {!! Form::label('status_observation', __('Status Observation') . ':') !!}
    {!! Form::text('status_observation', null, ['class' => 'form-control']) !!}
    <span class="help-block text-muted">{{ __('Status Observation') }}</span>
</div>

<!-- vehicle type Field -->
<div class="form-group col-sm-6">
    {!! Form::label('vehicle_type_id', __('Vehicle Type').':') !!}
    {!! Form::select('vehicle_type_id', $vehicleTypes, null, ['class' => 'form-control select2']) !!}
    <span class="help-block text-muted">{{ __('Vehicle Type') }}</span>
</div>

<!-- Vehicle brand Field -->
<div class="form-group col-sm-6">
    {!! Form::label('brand', __('Brand') . ':') !!}
    {!! Form::text('brand', null, ['class' => 'form-control']) !!}
    <span class="help-block text-muted">{{ __('Vehicle Brand') }}</span>
</div>

<!-- Vehicle model Field -->
<div class="form-group col-sm-6">
    {!! Form::label('model', __('Model') . ':') !!}
    {!! Form::text('model', null, ['class' => 'form-control']) !!}
    <span class="help-block text-muted">{{ __('Vehicle Model') }}</span>
</div>


<!-- Vehicle Plate Field -->
<div class="form-group col-sm-6">
    {!! Form::label('plate', __('Plate') . ':') !!}
    {!! Form::text('plate', null, ['class' => 'form-control']) !!}
    <span class="help-block text-muted">{{ __('Vehicle Plate') }}</span>
</div>


<!-- Base Price Field -->
<div class="form-group col-sm-6">
    {!! Form::label('base_price', __('Base Price') . ':') !!}
    {!! Form::number('base_price', null, ['class' => 'form-control', 'step' => 0.01, 'min' => 0]) !!}
    <span class="help-block text-muted">{{ __('Only used if is not using app pricing') }}</span>
</div>

<!-- Base Distance Field -->
<div class="form-group col-sm-6">
    {!! Form::label('base_distance', __('Base Distance') . ':') !!}
    {!! Form::number('base_distance', null, ['class' => 'form-control', 'step' => 0.01, 'min' => 0]) !!}
    <span class="help-block text-muted">{{ __('Only used if is not using app pricing') }}</span>
</div>

<!-- Additional Distance Pricing Field -->
<div class="form-group col-sm-6">
    {!! Form::label('additional_distance_pricing', __('Additional Distance Pricing') . ':') !!}
    {!! Form::number('additional_distance_pricing', null, ['class' => 'form-control', 'step' => 0.01, 'min' => 0]) !!}
    <span class="help-block text-muted">{{ __('Only used if is not using app pricing') }}</span>
</div>

<!-- Submit Field -->
<div class="form-group col-sm-12">
    {!! Form::submit(__('Save'), ['class' => 'btn btn-primary']) !!}
    <a href="{{ route('admin.drivers.index') }}" class="btn btn-light">{{ __('crud.cancel') }}</a>
</div>
@push('scripts')
    <!-- iCheck -->
    <script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
@endpush
