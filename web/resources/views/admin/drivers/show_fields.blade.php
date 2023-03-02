<!-- User Id Field -->
<div class="form-group col-sm-5">
    {!! Form::label('user_id', __('User') . ':') !!} <a href="{{ route('admin.drivers.edit', $driver->id) }}"
        class="btn btn-sm btn-primary">{{ __('Edit') }}</a>
    <p>{{ __('Name') . ': ' . $driver->user->name }}<br>
        {{ __('Phone') . ': ' . $driver->user->phone }}<br>
        {{ __('Email') . ': ' . $driver->user->email }}<br>
    </p>
</div>

<!-- Active Field -->
<div class="form-group col-sm-2">
    {!! Form::label('active', __('Active') . ':') !!}
    <p>{!! getBoolColumn($driver->active) !!}</p>
</div>

<!-- Status Field -->
<div class="form-group col-sm-2">
    {!! Form::label('status', __('Status') . ':') !!}
    <p>{!! getDriverStatusColumn($driver->status) !!}</p>
</div>


<!-- Last Location At Field -->
<div class="form-group col-sm-3">
    {!! Form::label('last_location_at', __('Last Location At') . ':') !!}
    <p>{!! is_null($driver->last_location_at) ? '-' : getDateHumanFormat($driver->last_location_at) !!}</p>
</div>


<!-- vehicle_type_id Field -->
<div class="form-group col-sm-3">
    {!! Form::label('vehicle_type_id', __('Vehicle Type') . ':') !!}
    <p>{{ $driver->vehicle_type->name }}</p>
</div>

<!-- brand Field -->
<div class="form-group col-sm-3">
    {!! Form::label('brand', __('Brand') . ':') !!}
    <p>{{ $driver->brand }}</p>
</div>

<!-- model Field -->
<div class="form-group col-sm-3">
    {!! Form::label('model', __('Model') . ':') !!}
    <p>{{ $driver->model }}</p>
</div>


<!-- plate Field -->
<div class="form-group col-sm-3">
    {!! Form::label('plate', __('Plate') . ':') !!}
    <p>{{ $driver->plate }}</p>
</div>


@if(!is_null($driver->last_location_at))
    <div class="col-sm-6">
        <div class="row">
            <!-- Lat Field -->
            <div class="form-group col-sm-12">
                {!! Form::label('actual_location', __('Last Location') . ':') !!}
                <div id="mapsHere" style="height: 300px">

                </div>
            </div>
        </div>
        <div class="row">
            <!-- Lat Field -->
            <div class="form-group col-sm-6">
                {!! Form::label('lat', __('Latitude') . ':') !!}
                <p>{{ $driver->lat }}</p>
            </div>

            <!-- lng Field -->
            <div class="form-group col-sm-6">
                {!! Form::label('lng', __('Longitude') . ':') !!}
                <p>{{ $driver->lng }}</p>
            </div>
        </div>
    </div>
@endif
<div class="col-sm-6">
    <div class="row">
        @if (isset($driver->driver_license_url))
            <div class="form-group col-sm-12">
                {!! Form::label('actual_location', __('Driver License') . ':') !!}
                <p><img src="{{ asset($driver->driver_license_url) }}" alt="Image Preview" style="height: 300px" /></p>
            </div>
        @endif
    </div>
</div>
@push('scripts')
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ setting('google_maps_key') }}&libraries=places&callback=initMap"
        async></script>
    <script type="text/javascript">
        function initMap() {
            var latlng = [];
            const myLatLng = {
                lat: {{ $driver->lat }},
                lng: {{ $driver->lng }}
            };
            const map = new google.maps.Map(document.getElementById("mapsHere"), {
                zoom: 16,
                center: myLatLng,
            });

            latlng.push(new google.maps.LatLng({{ $driver->lat }}, {{ $driver->lng }}));
            new google.maps.Marker({
                position: myLatLng,
                map,
                title: "{{ __('Driver Location') }}",
            });



        }

        window.initMap = initMap;
    </script>
@endpush
