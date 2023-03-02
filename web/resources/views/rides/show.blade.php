@extends($ride->ride_status == 'pending' || $ride->ride_status == 'accepted' || $ride->ride_status == 'in_progress' ? 'layouts.public_map' : 'layouts.public')
@include('rides.chat_modal')
@section('title')
    {{ __('Ride #:id - :name', ['id' => $ride->id, 'name' => $ride->driver->user->name]) }}
@endsection
@section('content')
    @push('css')
        @if ($ride->ride_status == 'pending' || $ride->ride_status == 'accepted' || $ride->ride_status == 'in_progress')
            <style>
                #map {
                    height: calc(100vh - 76px);
                }
            </style>
        @endif

        <style>
            .orderDetailsRow {
                border-bottom: 1px solid #eee;
                padding-top: 10px;
                padding-bottom: 10px;
            }
        </style>
    @endpush

    @if ($ride->ride_status == 'pending' || $ride->ride_status == 'accepted' || $ride->ride_status == 'in_progress')
        <div id="map"></div>
        <div class="card bg-light"
            style="position: absolute; bottom: 80px;z-index:999; left: 50%; transform: translate(-50%, 0%);">
            <div class="card-body text-primary">
                @if ($ride->ride_status == 'pending')
                    <div class="d-flex justify-content-center">
                        <span class="spinner-border mr-4" role="status" aria-hidden="true"></span>
                        <h4>{{ __('Searching driver') }}</h4>
                    </div>
                    <div class="row justify-content-center">
                        <button class="btn btn-sm btn-outline-secondary mt-3"
                            id="btnCancelRide">{{ __('Cancel Ride') }}</button>
                    </div>
                @elseif($ride->ride_status == 'accepted')
                    <div class="justify-content-center">
                        <h5>{{ __(':name is heading to your location driving a:', ['name' => $ride->driver->user->name]) }}
                        </h5>
                        <span><b>{{ __('Brand') }}</b> : {{ $ride->driver->brand }} </span><br>
                        <span><b>{{ __('Model') }}</b> : {{ $ride->driver->model }} </span><br>
                        <span><b>{{ __('Plate') }}</b> : {{ $ride->driver->plate }} </span><br>
                    </div>
                @else
                    <div class="justify-content-center">
                        <h5>{{ __('The ride is in progress') }}</h5>
                    </div>
                @endif
                @if (in_array($ride->ride_status, ['accepted', 'in_progress']))
                    <a id="openChat" href="#" data-ride_id="{{ $ride->id }}" data-toggle="modal"
                        data-target="#chatModal" class='btn btn-sm btn-outline-success btn-block'><i
                            class="fa fa-comments"></i>
                        {{ __('Order Chat') }}
                    </a>
                @endif
            </div>
        </div>
    @else
        <div class="container">
            <div class="container newRideContainer">
                <div class="row">
                    <div class="col-sm-12 text-center">
                        <h5 class="card-title text-center" style="color: #00a65a">
                            {{ __('Ride #:id - :name', ['id' => $ride->id, 'name' => $ride->driver->user->name]) }}
                        </h5>
                    </div>
                </div>
                @include('flash::message')
                @if (!$ride->offline_payment_method_id)
                    @if ($ride->payment_status == 'pending')
                        @include('rides.include.' . $ride->payment_gateway . '_payment')
                    @endif
                @endif
                <div class="row mb-5 mx-1">
                    <div class="col-md-6 text-center">
                        <div id="map" style="min-height: 400px"></div>
                    </div>
                    <div id="adressesContainer" class="col-md-6">

                    </div>
                </div>
                <div class="row" style="padding-top: 25px">
                    <div class="col-12 text-center">
                        <h5>{{ __('Ride Details') }}</h5>
                    </div>
                </div>
                @if ($ride->offline_payment_method_id == 0)
                    <div class="row orderDetailsRow">
                        <div class="col-sm-4 text-sm-right">
                            <h6>{{ __('Payment') }}</h6>
                        </div>
                        <div class="col-sm-8 text-left">
                            @if ($ride->payment_status == 'paid')
                                <h6 class="text-success">
                                    <i class="fas fa-check" style="font-size: 24px"></i>
                                    {{ __('Payment Approved') }}
                                </h6>
                            @elseif($ride->ride_status == 'cancelled')
                                <h6 class="text-danger">
                                    <i class="fas fa-times-circle" style="font-size: 24px"></i>
                                    {{ __('Ride Cancelled') }}
                                </h6>
                            @else
                                <h6 class="text-warning">
                                    <i class="fas fa-exclamation-circle" style="font-size: 24px"></i>
                                    {{ __('Payment :status', ['status' => ucwords($ride->payment_status)]) }}
                                </h6>
                            @endif
                        </div>
                    </div>
                @else
                    <div class="row orderDetailsRow">
                        <div class="col-sm-4 text-sm-right">
                            <h6>{{ __('Payment Method') }}</h6>
                        </div>
                        <div class="col-sm-8 text-left">
                            {{ $ride->OfflinePaymentMethod->name }}
                        </div>
                    </div>
                @endif
                <div class="row orderDetailsRow">
                    <div class="col-sm-4 text-sm-right">
                        <h6>{{ __('Ride Date') }}</h6>
                    </div>
                    <div class="col-sm-8 text-left">
                        {{ $ride->created_at->format('d/m/Y H:i') }}
                    </div>
                </div>
                <div class="row orderDetailsRow">
                    <div class="col-sm-4 text-sm-right">
                        <h6>{{ __('Driver') }}</h6>
                    </div>
                    <div class="col-sm-8 text-left">
                        <img src="{{ $ride->driver->user->media->first()->url ?? '/img/avatardefault.png' }}"
                            style="width: 40px;height:40px;border-radius: 100%">
                        {{ $ride->driver->user->name }}
                    </div>
                </div>
                <div class="row orderDetailsRow">
                    <div class="col-sm-4 text-sm-right">
                        <h6>{{ __('Distance') }}</h6>
                    </div>
                    <div class="col-sm-8 text-left">
                        {{ number_format($ride->distance, 2, '.', '') }} {{ setting('distance_unit') }}
                    </div>
                </div>
                <div class="row orderDetailsRow">
                    <div class="col-sm-4 text-sm-right">
                        <h6>{{ __('Total') }}</h6>
                    </div>
                    <div class="col-sm-8 text-left">
                        {!! getPrice($ride->total_value) !!}
                    </div>
                </div>
                <div class="row orderDetailsRow">
                    <div class="col-sm-4 text-sm-right">
                        <h6>{{ __('Observation') }}</h6>
                    </div>
                    <div class="col-sm-8 text-left">
                        {{ $ride->customer_observation ?? '-' }}
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 text-right mt-4">
                        @if ($ride->ride_status == 'pending')
                            <button class="btn btn-sm btn-outline-danger"
                                id="btnCancelRide">{{ __('Cancel Ride') }}</button>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    @endif
    <!-- Modal -->
    <div class="modal fade" id="modalPedido" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Driver Contact') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        <b>{{ __('Name') }}:</b> {{ $ride->driver->user->name }}<br>
                        <b>{{ __('Email') }}:</b> <a
                            href="mailto:{{ $ride->driver->user->email }}">{{ $ride->driver->user->email }}</a><br>
                        <b>{{ __('Phone') }}:</b> {{ $ride->driver->user->phone ?? '-' }}<br>
                    </p>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script type="text/javascript">
        var map;
        var latlng = [];
        var latlngbounds;
        let directionsService;
        let directionsRenderer;

        function initMap() {
            const myLatLng = {
                lat: {{ json_decode($ride->boarding_location_data)->geometry->location->lat }},
                lng: {{ json_decode($ride->boarding_location_data)->geometry->location->lng }}
            };
            map = new google.maps.Map(document.getElementById("map"), {
                zoom: 16,
                center: myLatLng,

            });
            directionsService = new google.maps.DirectionsService();
            directionsRenderer = new google.maps.DirectionsRenderer({
                suppressMarkers: true,
            });
            directionsRenderer.setMap(map);

            latlng.push(new google.maps.LatLng({{ json_decode($ride->boarding_location_data)->geometry->location->lat }},
                {{ json_decode($ride->boarding_location_data)->geometry->location->lng }}));

            @if ($ride->ride_status == 'pending')
                var svg = ''
                new google.maps.Marker({
                    icon: {
                        url: "{{ url('img/searching_driver.svg') }}",
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(75, 75),
                        scaledSize: new google.maps.Size(150, 150)
                    },
                    position: myLatLng,
                    map,
                    title: "{{ __('Pickup Location') }}",
                });
            @else
                @if ($ride->ride_status == 'accepted' || $ride->ride_status == 'in_progress')
                    showDriverPosition();
                @endif
                new google.maps.Marker({
                    position: myLatLng,
                    map,
                    title: "{{ __('Pickup Location') }}",
                });


                new google.maps.Marker({
                    position: {
                        lat: {{ json_decode($ride->destination_location_data)->geometry->location->lat }},
                        lng: {{ json_decode($ride->destination_location_data)->geometry->location->lng }}
                    },
                    map,
                    title: "{{ __('Ride Place') }}",
                });

                directionsService
                    .route({
                        origin: {
                            lat: {{ json_decode($ride->boarding_location_data)->geometry->location->lat }},
                            lng: {{ json_decode($ride->boarding_location_data)->geometry->location->lng }}
                        },
                        destination: {
                            lat: {{ json_decode($ride->destination_location_data)->geometry->location->lat }},
                            lng: {{ json_decode($ride->destination_location_data)->geometry->location->lng }}
                        },
                        optimizeWaypoints: true,
                        travelMode: google.maps.TravelMode.DRIVING,
                    })
                    .then((response) => {
                        console.log(response);
                        directionsRenderer.setDirections(response);
                    });
            @endif
            latlng.push(new google.maps.LatLng(
                {{ json_decode($ride->destination_location_data)->geometry->location->lat }},
                {{ json_decode($ride->destination_location_data)->geometry->location->lng }}));

            latlngbounds = new google.maps.LatLngBounds();

            for (var i = 0; i < latlng.length; i++) {
                latlngbounds.extend(latlng[i]);
            }
            map.fitBounds(latlngbounds);

        }

        window.initMap = initMap;


        var driverMarker;
        $(document).ready(function() {
            loadAddresses();
            $('#btnCancelRide').click(function() {
                cancelRide();
            });
        });
        if (!Array.prototype.last) {
            Array.prototype.last = function() {
                return this[this.length - 1];
            };
        };

        function cancelRide() {
            if (confirm("{{ __('Are you sure you want to cancel this ride?') }}")) {
                $('#btnCancelRide').html('<i class="fas fa-spinner fa-spin"></i>');
                $.ajax({
                    url: "{{ url('api/rides/cancel') }}",
                    type: "POST",
                    dataType: "json",
                    data: {
                        api_token: "{{ Auth::user()->api_token }}",
                        ride_id: '{{ $ride->id }}',
                    },
                    success: function(data) {
                        if (data.success) {
                            Swal.fire({
                                title: '{{ __('Ride cancelled') }}',
                                text: '{{ __('Ride cancelled successfully') }}',
                                type: 'success',
                                icon: 'success',
                                confirmButtonText: '{{ __('Ok') }}'
                            }).then((result) => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                title: '{{ __('Oops') }}',
                                text: data.message,
                                type: 'error',
                                icon: 'error',
                                confirmButtonText: '{{ __('Ok') }}'
                            });
                            $('#btnCancelRide').html('{{ __('Cancel ride') }}');
                        }
                    },
                });
            }
        }

        function loadAddresses() {
            $.ajax({
                url: "{{ route('rides.ajaxGetAddressesHtml') }}",
                type: "GET",
                dataType: "html",
                data: {
                    ride_id: '{{ $ride->id }}',
                },
                success: function(data) {
                    $('#adressesContainer').html(data);
                }
            });
        }

        @if ($ride->ride_status == 'pending' || $ride->ride_status == 'accepted' || $ride->ride_status == 'in_progress')
            loadRideStatus();

            function loadRideStatus() {
                $.ajax({
                    url: "{{ url('api/rides/' . $ride->id . '/status') }}",
                    type: "GET",
                    data: {
                        api_token: "{{ Auth::user()->api_token }}",
                    },
                    success: function(data) {
                        if (data.data != '{{ $ride->ride_status }}') {
                            location.reload();
                        }
                    }
                });

                setTimeout(function() {
                    loadRideStatus();
                }, 10000);
            }
        @endif
        @if ($ride->ride_status == 'accepted' || $ride->ride_status == 'in_progress')
            function showDriverPosition() {
                $.ajax({
                    url: "{{ url('api/rides/getDriverPosition') }}",
                    type: "POST",
                    data: {
                        api_token: "{{ Auth::user()->api_token }}",
                        ride_id: '{{ $ride->id }}',
                    },
                    success: function(data) {
                        if (data.success) {
                            if (!driverMarker) {
                                driverMarker = new google.maps.Marker({
                                    position: {
                                        lat: data.lat,
                                        lng: data.lng
                                    },
                                    map,
                                    icon: {
                                        url: '{{ $ride->driver->vehicle_type->getFirstMediaUrl() }}',
                                        scaledSize: new google.maps.Size(80, 50),
                                        origin: new google.maps.Point(0, 0),
                                        anchor: new google.maps.Point(40, 25),
                                    },
                                });
                                latlngbounds.extend(driverMarker.getPosition());
                                map.fitBounds(latlngbounds);
                            } else {
                                if (driverMarker.getPosition().lat() != data.lat || driverMarker.getPosition()
                                    .lng() != data.lng) {
                                    driverMarker.setPosition({
                                        lat: data.lat,
                                        lng: data.lng
                                    });
                                    latlngbounds.extend(driverMarker.getPosition());
                                    map.fitBounds(latlngbounds);
                                }
                            }

                            setTimeout(function() {
                                showDriverPosition();
                            }, 5000);
                        } else {
                            //remove the driver position
                            removeDriverPosition();
                        }
                    }
                });
            }
        @endif


        function removeDriverPosition() {
            if (driverMarker) {
                driverMarker.setMap(null);
            }
        }
    </script>

    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ setting('google_maps_key') }}&libraries=places&callback=initMap"
        async></script>
@endpush
