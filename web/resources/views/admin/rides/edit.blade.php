@extends('layouts.app')
@section('title')
    {{ __('Edit Ride') }}
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h3 class="page__heading m-0">{{ __('Edit Ride') }}</h3>
            <div class="filter-container section-header-breadcrumb row justify-content-md-end">
                <a href="{{ route('admin.rides.index') }}" class="btn btn-primary">{{ __('Back') }}</a>
            </div>
        </div>
        <div class="content">
            @include('flash::message')
            @include('stisla-templates::common.errors')
            <div class="section-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body ">
                                {!! Form::model($ride, ['route' => ['admin.rides.update', $ride->id], 'method' => 'patch']) !!}
                                <div class="row">
                                    @include('admin.rides.fields')
                                </div>

                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@push('scripts')
    <script
        src="https://maps.googleapis.com/maps/api/js?key={{ setting('google_maps_key') }}&libraries=places&callback=initMap"
        async></script>
    <script type="text/javascript">
        async function getTheNumber(place, theInput) {
            let extractedNumber = '';
            if (place.name.split(', ').length > 1) {
                extractedNumber = place.name.split(', ').pop();
            }
            //input the number manually
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

        function initMap() {
            const input = document.getElementById("inputCollectPlace");
            const options = {
                fields: ["formatted_address", "geometry", "name"],
                strictBounds: false,
            };

            const autocomplete = new google.maps.places.Autocomplete(input, options);

            autocomplete.addListener("place_changed", async () => {
                const place = autocomplete.getPlace();
                place["number"] = await getTheNumber(place, $('#inputCollectPlace'));
                $('input[name="boarding_address_data"]').val(JSON.stringify(place));

                //calculateRide();


            });

            const input2 = document.getElementById("inputRidePlace");

            const autocomplete2 = new google.maps.places.Autocomplete(input2, options);

            autocomplete2.addListener("place_changed", async () => {
                const place2 = autocomplete2.getPlace();
                place2["number"] = await getTheNumber(place2, $('#inputRidePlace'));
                $('input[name="destination_address_data"]').val(JSON.stringify(place2));
                //calculateRide();
            });

        }
        $(document).ready(function() {
            var customer_limit = 20;
            var driver_limit = 20;
            $('#customer_id').select2({
                ajax: {
                    url: '{{ route('admin.customersJson') }}',
                    dataType: 'json',
                    data: function(params) {
                        var query = {
                            'limit': customer_limit,
                            'offset': ((params.page || 1) - 1) * customer_limit
                        };
                        if (params.term != undefined) {
                            query.search = 'name:' + params.term;
                            query.searchFields = 'name:like';
                        }
                        return query;
                    },
                    processResults: function(dataResponse, params) {
                        params.page = params.page || 1;
                        var data = $.map(dataResponse['data'], function(obj) {
                            return {
                                'id': obj.id,
                                'text': obj.name
                            };
                        });
                        return {
                            results: data,
                            pagination: {
                                more: data.length == customer_limit
                            }
                        };
                    }
                }
            });
            $('#driver_id').select2({
                ajax: {
                    url: '{{ route('admin.driversJson') }}',
                    dataType: 'json',
                    data: function(params) {
                        var query = {
                            'limit': driver_limit,
                            'offset': ((params.page || 1) - 1) * driver_limit
                        };
                        if (params.term != undefined) {
                            query.search = 'user.name:' + params.term;
                            query.searchFields = 'user.name:like';
                        }
                        return query;
                    },
                    processResults: function(dataResponse, params) {
                        params.page = params.page || 1;
                        var data = $.map(dataResponse['data'], function(obj) {
                            return {
                                'id': obj.id,
                                'text': obj.name
                            };
                        });
                        return {
                            results: data,
                            pagination: {
                                more: data.length == driver_limit
                            }
                        };
                    }
                }
            });
            $('#inputCollectPlace').on('keyup', function() {
                $('input[name="boarding_address_data"]').val('');
            });
            $('#inputRidePlace').on('keyup', function() {
                $('input[name="destination_address_data"]').val('');
            });


        });
    </script>
@endpush
