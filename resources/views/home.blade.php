@extends('layouts.public_map')
@section('title')
    {{ __('Home') }}
@endsection
@section('content')
    @push('css')
        <style>
            #map {
                height: calc(100vh - 76px);
            }

            .text {
                overflow: hidden;
                text-overflow: ellipsis;
                display: -webkit-box;
                -webkit-line-clamp: 2;
                line-clamp: 2;
                line-height: 1em;
                -webkit-box-orient: vertical;
                text-align: center;
                font-size: 17px;
            }

            .text:after {
                content: "";
                display: inline-block;
                width: 100%;
            }

            hr {
                margin-top: 0rem;
                margin-bottom: 0rem;
                border: 0;
                border-radius: 10px 5% / 20px 30px;
                border-top: 1px solid rgba(0, 0, 0, 0.1);
            }

            #inputBoardingPlace {
                border: 0;
                outline: 0;
            }

            #inputDestinationPlace {
                border: 0;
                outline: 0;
            }
        </style>
    @endpush
    <div id="select_address_card" class="card bg-primary"
        style="position: absolute; bottom: 180px;z-index:999; left: 50%; transform: translate(-50%, 0%); display:none;">
        <div class="card-body text-white">
            {{ __('Select the address on the map') }}
        </div>
    </div>

    <form method="POST" id="formRideRide">
        @if (auth()->check())
            <div class="card"
                style="position: absolute; top: 80px;z-index:999;left: 0; left: 50%;transform: translate(-50%, 0); max-width: 85%;border-radius:15px;">
                <div class="card-body p-0">
                    <div class="d-flex flex-row flex-nowrap">
                        <input type="hidden" id="vehicle_type" name="vehicle_type"
                            value="{{ $vehicle_types->where('default', true)->first()->id ?? null }}" />
                        @foreach ($vehicle_types as $key => $vehicle_type)
                            <div id="{{ $vehicle_type->id }}-card" data-id="{{ $vehicle_type->id }}"
                                class="vehicle_type_cards @if ($vehicle_type->default) bg-primary text-white @endif"
                                style="width: 100px;height: 90px; @if (sizeof($vehicle_types) > $key + 1) border-right: 0.1px solid #7777; @else border-top-right-radius:15px;border-bottom-right-radius:15px; @endif @if ($key == 0) border-top-left-radius:15px; border-bottom-left-radius:15px; @endif">
                                <div class="text" style="padding-top:10px">{{ $vehicle_type->name }}</div>
                                @if ($vehicle_type->has_media)
                                    <img class="card-img-top" style="margin-top:-10px;max-height: 50px;object-fit: contain"
                                        src="{{ $vehicle_type->getFirstMediaUrl() }}" alt="Card image cap">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="card"
            style="position: absolute; @if (auth()->check()) top: 180px; @else top: 100px; @endif z-index:999;left: 0; right: 0; margin-left: auto; margin-right: auto; width: 75%;">
            <div class="card-body p-0">
                <input type="hidden" name="api_token" value="{{ Auth::user()->api_token ?? '' }}">
                <div class="row pb-0">
                    <div class="col-lg-11 col-10 mr-0 pr-0">
                        <input type="hidden" id="destination_address_data" name="destination_address_data"
                            value="{{ $lastCollectAddressData ?? '' }}">
                        <input type="text" class="form-control" id="inputDestinationPlace" tabindex="-1"
                            name="destination_address" placeholder="{{ __('Destination location') }}" tabindex="1"
                            value="{{ Cookie::get('destination') !== null ? Cookie::get('destination') : old('destination') }}"
                            style="border: 0;">
                    </div>
                    <div class="col-lg-1 col-2 ml-0 pl-0">
                        <button id="btnAddDestinationLocation" type="button" class="btn btn-primary btn-block h-100"
                            style="border: 0; border-bottom-left-radius: 0px;border-top-left-radius: 0px;border-bottom-right-radius: 0px;">
                            <i class="fas fa-map-marker-alt"></i>
                        </button>
                    </div>
                </div>
                <hr id="boarding_location_divider" class="rounded-pill" />

                @if (auth()->check())
                    <div id="recent_rides_card" class="list-group"
                        style="border-top-left-radius: 0px;border-top-right-radius: 0px;">
                        @foreach (auth()->user()->addresses as $address)
                            <a href="#" onclick="setSelectedAddress({{ json_encode($address) }});"
                                class="list-group-item px-2 py-1 m-0 list-group-item-secondary list-group-item-action">
                                {{ $address->formatted_address }}
                            </a>
                        @endforeach
                    </div>
                @endif
                <div id="boarding_location" class="row" style="display:none;">

                    <div class="col-lg-11 col-10 mr-0 pr-0">
                        <input type="hidden" id="boarding_address_data" name="boarding_address_data"
                            value="{{ $lastCollectAddressData ?? '' }}">
                        <input type="text" class="form-control" id="inputBoardingPlace" tabindex="-1"
                            name="boarding_location" placeholder="{{ __('Boarding location') }}" tabindex="1"
                            value="{{ Cookie::get('boarding_place') !== null ? Cookie::get('boarding_place') : old('boarding_place') }}">
                    </div>
                    <div class="col-lg-1 col-2 ml-0 pl-0">
                        <button id="btnAddBoardingLocation" type="button" class="btn btn-primary btn-block h-100"
                            style="border-bottom-left-radius: 0px;border-top-left-radius: 0px;border-top-right-radius: 0px;">
                            <i class="fas fa-map-marker-alt"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
    @if (auth()->check())
        <div id="divResume" class="card border-primary mt-2"
            style="display: none; position: absolute; bottom: 10px; width: 300px; z-index:999;left: 0; right: 0; margin-left: auto; margin-right: auto;">
            <div class="card-body text-center p-1">
                <h4>{{ __('Summary') }}</h4>
                <table class="table table-striped table-sm p-0 m-0">
                    <tr>
                        <th>{{ __('Distance') }}</th>
                        <td id="totalDistance">-</td>
                    </tr>
                    <tr>
                        <th>{{ __('Total') }}</th>
                        <td id="totalPrice">-</td>
                    </tr>
                </table>
            </div>
            <button type="button" class="btn btn-primary mb-1 ml-3 mr-3" data-toggle="modal" data-target="#rideModal">
                {{ __('Request Ride') }}
            </button>
        </div>
    @endif
    <div id="map"></div>

    <!-- Modal -->
    <div class="modal fade" id="rideModal" tabindex="-1" role="dialog" style="z-index:9999;"
        aria-labelledby="rideModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">{{ __('Request Ride') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label>{{ __('Observation for the Driver') }}</label>
                                <textarea class="form-control" id="inputObservation" tabindex="6" name="observation"
                                    placeholder="{{ __('If needed, input an observation for the driver') }}" value="" style="height: 100px"></textarea>
                                <div class="invalid-feedback" data-name="observation">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center py-3">
                            <input type="hidden" name="payment_method" value="">
                            <input type="hidden" name="payment_method_type" value="">
                            <h4 style="text-align: center;font-size: 1.2rem">{{ __('Select the payment method') }}
                            </h4>
                        </div>
                    </div>
                    @include('rides.include.payment_methods')
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button id="btnSendRide" type="button" class="btn btn-primary">{{ __('Submit') }}</button>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
        <script
            src="https://maps.googleapis.com/maps/api/js?key={{ setting('google_maps_key') }}&libraries=places&callback=initMap"
            async></script>

        <script type="text/javascript">
            let directionsService;
            let directionsRenderer;
            let map;
            var markers = [];
            var driverMarkers = [];
            var rideLocationListener = null,
                rideDestinationListener = null;

            function placeDriverMarker(id, location, url) {
                if (driverMarkers[id] != null) {
                    driverMarkers[id].setPosition(location);
                } else {
                    driverMarkers[id] = new google.maps.Marker({
                        icon: {
                            url: url,
                            scaledSize: new google.maps.Size(80, 50),
                            origin: new google.maps.Point(0, 0),
                            anchor: new google.maps.Point(40, 25),
                        },
                        clickable: false,
                        position: location,
                        map: map
                    });
                }
            }

            function setSelectedAddress(address) {
                $('#destination_address_data').val(JSON.stringify(address)).trigger(
                    'change');
                $('#inputDestinationPlace').val(address.formatted_address);
                map.panTo(address.geometry.location);
                placeMarker(2, address.geometry.location);
            }


            function simulate() {
                if (markers[1] != null && markers[2] != null && $('#vehicle_type').val() != null) {
                    $.ajax({
                        url: '{{ url('/api/rides/simulate') }}',
                        type: 'POST',
                        dataType: 'json',
                        data: $('#formRideRide').serialize(),
                        success: function(data) {
                            $('#totalDistance').html(data.distance);
                            $('#totalPrice').html(data.price);
                            if (data.success) {
                                if (data.enabled) {
                                    alreadyContinued = true;
                                    $('#btnSendRide').removeAttr('disabled');
                                    $('#divResume').show();
                                } else {
                                    alreadyContinued = false;
                                    $('#divResume').hide();
                                }
                            } else {
                                alreadyContinued = false;
                                $('#divResume').hide();
                                return Swal.fire({
                                    type: 'error',
                                    icon: 'error',
                                    title: data.message ??
                                        '{{ __('There was an error calculating ride') }}',
                                });
                            }
                        },
                        error: function(data) {
                            alreadyContinued = false;
                            $('#divResume').hide();
                            $('#totalDistance').html('0,00');
                            $('#totalPrice').html('0,00');
                            return Swal.fire({
                                type: 'error',
                                icon: 'error',
                                title: '{{ __('There was an error calculating ride') }}',
                            });
                        },
                    });
                }
            }

            @if (auth()->check())

                $('body').on('mousedown touchstart,click', '.selectedPaymentMethod', function() {
                    let selectedPaymentMethod = $(this).attr('data-id');
                    let paymentMethodType = $(this).attr('data-type');
                    let paymentMethodText = $(this).text();
                    $('input[name="payment_method"]').val(selectedPaymentMethod);
                    $('input[name="payment_method_type"]').val(paymentMethodType);

                    $('.selectedPaymentMethod').removeClass("bg-primary text-white");
                    $('#' + selectedPaymentMethod + '-payment-card').addClass('bg-primary text-white');

                });

                $(".vehicle_type_cards").click(function() {
                    $('.vehicle_type_cards').removeClass("bg-primary text-white");
                    var vehicle_id = $(this).data("id");
                    $('#vehicle_type').val(vehicle_id)
                    $('#' + vehicle_id + '-card').addClass('bg-primary text-white');
                    simulate();
                });

                $("#btnAddBoardingLocation").click(function() {
                    if (google.maps.event.hasListeners(map, 'click')) {
                        $('#select_address_card').hide();
                        google.maps.event.clearListeners(map, 'click');
                        map.setOptions({
                            draggableCursor: 'default'
                        });
                    } else {
                        $('#select_address_card').show();
                        map.setOptions({
                            draggableCursor: 'crosshair'
                        });
                        google.maps.event.addListener(map, 'click', function(event) {
                            $('#select_address_card').hide();
                            google.maps.event.clearListeners(map, 'click');
                            map.setOptions({
                                draggableCursor: 'default'
                            });

                            var geocoder = new google.maps.Geocoder();
                            var addresses = geocoder
                                .geocode({
                                    location: event.latLng
                                })
                                .then((response) => {
                                    var address = response.results[0];
                                    var types = address['address_components'].find(element =>
                                        element['types']
                                        .find(
                                            element => element == 'street_number'));
                                    if (types != null) {
                                        address['number'] = types['long_name'];
                                    }
                                    $('#boarding_address_data').val(JSON.stringify(address)).trigger(
                                        'change');
                                    $('#inputBoardingPlace').val(address.formatted_address);
                                    placeMarker(1, event.latLng);
                                })
                                .catch((e) => window.alert("Geocoder failed due to: " + e));

                        });
                    }
                });

                $('#btnSendRide').click(function() {
                    $('#btnSendRide').attr('disabled', true);
                    let beforeValue = $('#btnSendRide').html();
                    $('#btnSendRide').html('<i class="fas fa-spinner fa-spin"></i>');
                    var paymentMethod = $('input[name="payment_method"]').val();
                    var paymentType = $('input[name="payment_method_type"]').val();
                    var observation = $('#inputObservation').val();
                    var data = $('#formRideRide').serialize() + ('&payment_method=' + paymentMethod) + (
                        '&payment_method_type=' + paymentType) + ('&observation=' + observation);
                    $.ajax({
                        url: '{{ url('/api/rides') }}',
                        type: 'POST',
                        dataType: 'json',
                        data: data,
                        success: function(data) {
                            if (data.success) {
                                window.location.href = '{{ url('/rides') }}/' + data.id;
                            } else {
                                $('#rideModal').modal('hide');
                                $('#btnSendRide').removeAttr('disabled');
                                $('#btnSendRide').html(beforeValue);
                                return Swal.fire({
                                    type: 'error',
                                    icon: 'error',
                                    title: data.message ??
                                        '{{ __('There was an error calculating ride') }}',
                                });
                            }
                        },
                        error: function(data) {
                            console.log(data);
                            $('#rideModal').modal('hide');
                            data = data.responseJSON;
                            $('#btnSendRide').html(beforeValue);
                            $('#btnSendRide').removeAttr('disabled');
                            return Swal.fire({
                                type: 'error',
                                icon: 'error',
                                title: data.message ??
                                    '{{ __('There was an error calculating ride') }}',
                            });
                        },
                    });

                });

                $('input[name="destination_address_data"]').change(function() {
                    if ($(this).val().length > 0) {
                        $('#recent_rides_card').hide();
                        $('#boarding_location').show();
                        $('#boarding_location_divider').show();
                    } else {
                        $('#boarding_location').hide();
                        $('#boarding_location_divider').hide();
                    }
                });
                $("#btnAddDestinationLocation").click(function() {
                    if (google.maps.event.hasListeners(map, 'click')) {
                        $('#select_address_card').hide();
                        google.maps.event.clearListeners(map, 'click');
                        map.setOptions({
                            draggableCursor: 'default'
                        });
                    } else {
                        $('#select_address_card').show();
                        map.setOptions({
                            draggableCursor: 'crosshair'
                        });
                        rideDestinationListener = google.maps.event.addListener(map, 'click', function(event) {
                            $('#select_address_card').hide();
                            google.maps.event.clearListeners(map, 'click');
                            map.setOptions({
                                draggableCursor: 'default'
                            });
                            var geocoder = new google.maps.Geocoder();
                            var addresses = geocoder
                                .geocode({
                                    location: event.latLng
                                })
                                .then((response) => {
                                    var address = response.results[0];
                                    var types = address['address_components'].find(element =>
                                        element['types']
                                        .find(
                                            element => element == 'street_number'));
                                    if (types != null) {
                                        address['number'] = types['long_name'];
                                    }
                                    $('#destination_address_data').val(JSON.stringify(address)).trigger(
                                        'change');
                                    $('#inputDestinationPlace').val(address.formatted_address);
                                    placeMarker(2, event.latLng);
                                })
                                .catch((e) => window.alert("Geocoder failed due to: " + e));
                        });
                    }
                });

                function placeMarker(id, location) {
                    if (markers[id] != null) {
                        markers[id].setPosition(location);
                    } else {
                        markers[id] = new google.maps.Marker({
                            icon: new google.maps.MarkerImage('https://maps.google.com/mapfiles/ms/icons/' + (id == 1 ?
                                    'green' : 'red') + '-dot.png',
                                new google.maps.Size(30, 30),
                                new google.maps.Point(0, 0),
                                new google.maps.Point(11, 11),
                            ),
                            clickable: false,
                            position: location,
                            map: map
                        });
                    }
                    setRoute();
                    simulate();
                }

                async function getTheNumber(place, theInput) {
                    let extractedNumber = '';
                    if (place.name.split(', ').length > 1) {
                        extractedNumber = place.name.split(', ').pop();
                    }
                    const {
                        value: number
                    } = await Swal.fire({
                        title: "{{ __('Enter the number and complement') }}",
                        text: theInput.val(),
                        input: 'text',
                        allowOutsideClick: false,
                        inputValue: extractedNumber,
                        inputPlaceholder: 'Ex. 123',
                        inputAttributes: {
                            'aria-label': "{{ __('Enter the location number and complement') }}"
                        },
                        showCancelButton: true,
                        cancelButtonText: "{{ __('Cancel') }}",
                        inputValidator: (value) => {
                            if (!value) {
                                return "{{ __('You need to enter a value!') }}"
                            }
                        }
                    });

                    return (number !== null && number != "") ? number : getTheNumber(place, theInput);

                }
            @else
                $('#btnAddDestinationLocation,#btnAddBoardingLocation').click(function() {
                    $('#modalLogin').modal('show');
                    $('#createAccountContent').show();
                    $('#loginContent').hide();
                    $('#modalTitleLogin').html('{{ __('Start creating your account!') }}');
                });
                $('#inputDestinationPlace,#inputBoardingPlace').focus(function() {
                    $('#modalLogin').modal('show');
                    $('#createAccountContent').show();
                    $('#loginContent').hide();
                    $('#modalTitleLogin').html('{{ __('Start creating your account!') }}');
                });
            @endif


            function getDrivers(lat, lng) {
                $.ajax({
                    url: "{{ url('/api/drivers') }}",
                    type: 'GET',
                    dataType: 'json',
                    "data": {
                        "lat": lat,
                        "lng": lng
                    },
                    success: function(data) {
                        if (data.success) {
                            data.data.forEach(function(item) {
                                var loc = {
                                    lat: parseFloat(item.lat),
                                    lng: parseFloat(item.lng),
                                };

                                placeDriverMarker(item.id, loc, item.vehicle_type['media'][0][
                                    'original_url'
                                ]);
                            });
                        }
                    }
                });
            }

            function initMap() {
                map = new google.maps.Map(document.getElementById("map"), {
                    center: {
                        lat: -14.6825207,
                        lng: -49.7332467
                    },
                    streetViewControl: false,
                    mapTypeControl: false,
                    zoom: 4.82,
                    styles: [{
                            "featureType": "poi.business",
                            "stylers": [{
                                "visibility": "off"
                            }]
                        },
                        {
                            "featureType": "poi.park",
                            "elementType": "labels.text",
                            "stylers": [{
                                "visibility": "off"
                            }]
                        }
                    ],
                });
                directionsService = new google.maps.DirectionsService();
                directionsRenderer = new google.maps.DirectionsRenderer({
                    suppressMarkers: true,
                });
                directionsRenderer.setMap(map);

                markers[0] = new google.maps.Marker({
                    clickable: false,
                    icon: new google.maps.MarkerImage("{{ url('img/my_marker.png') }}",
                        new google.maps.Size(30, 30),
                        new google.maps.Point(0, 0),
                        new google.maps.Point(11, 11),
                    ),
                    shadow: null,
                    zIndex: 999,
                    map: map
                });

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const pos = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude,
                            };
                            markers[0].setPosition(pos);
                            map.panTo(pos);
                            map.setZoom(16);
                            getDrivers(position.coords.latitude, position.coords.longitude);
                            setInterval(function() {
                                getDrivers(position.coords.latitude, position.coords.longitude);
                            }, 10000);
                        }
                    );
                }
                const options = {
                    fields: ["formatted_address", "geometry", "name"],
                    strictBounds: false,
                };

                const input1 = document.getElementById("inputBoardingPlace");

                const autocomplete1 = new google.maps.places.Autocomplete(input1, options);

                autocomplete1.addListener("place_changed", async () => {
                    place1 = autocomplete1.getPlace();
                    place1["number"] = await getTheNumber(place1, $('#inputBoardingPlace'));
                    $('input[name="boarding_address_data"]').val(JSON.stringify(place1)).trigger('change');
                    map.panTo(place1.geometry.location);
                    placeMarker(1, place1.geometry.location);
                });

                const input2 = document.getElementById("inputDestinationPlace");

                const autocomplete2 = new google.maps.places.Autocomplete(input2, options);

                autocomplete2.addListener("place_changed", async () => {
                    const place2 = autocomplete2.getPlace();
                    place2["number"] = await getTheNumber(place2, $('#inputDestinationPlace'));
                    $('input[name="destination_address_data"]').val(JSON.stringify(place2)).trigger('change');
                    map.panTo(place2.geometry.location);
                    placeMarker(2, place2.geometry.location);
                });
            }

            function setRoute() {
                if (markers[1] != null && markers[2] != null) {

                    directionsService
                        .route({
                            origin: markers[1].position,
                            destination: markers[2].position,
                            optimizeWaypoints: true,
                            travelMode: google.maps.TravelMode.DRIVING,
                        })
                        .then((response) => {
                            console.log(response);
                            directionsRenderer.setDirections(response);
                        });
                }
            }

            window.initMap = initMap;
        </script>
    @endpush
@endsection
