@extends('layouts.public')
@section('title')
    {{ __('My Rides') }}
@endsection
@section('content')
    <style type="text/css">
        .orderDetailsRow {
            border-bottom: 1px solid #eee;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .orderDetailsRow:last-child {
            border-bottom: 0px solid #eee;

        }

        .card {
            margin-bottom: 20px;
        }
    </style>
    @include('flash::message')
    @if (count($rides) == 0)
        <div class=" card card-primary p-5">
            <center>
                <i class="fas fa-info-circle" style="font-size: 48px"></i>
                <br><br>
                <h3>{{ __('You don\'t have rides yet') }}</h3>
            </center>
        </div>
    @endif
    @foreach ($rides as $ride)
        <div class=" card card-primary">
            <div class="card-header d-flex justify-content-between align-items-center"
                style="line-height: 30px;min-height: 25px;padding: 15px 25px 0px 25px">
                <h6 class="card-title">
                    {{ __('Ride #:id - :name', ['id' => $ride->id, 'name' => $ride->driver->user->name]) }}
                </h6>
                <a href="{{ route('rides.show', $ride->id) }}" class="btn btn-primary btn-sm"
                    style="margin-top: -15px;">{{ __('Detalhes') }}</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">

                        <div class="row orderDetailsRow">
                            <div class="col-sm-4 text-sm-right">
                                <h6>{{ __('Ride Person') }}</h6>
                            </div>
                            <div class="col-sm-8 text-left">
                                {{ $ride->driver->user->name }}
                                &nbsp;&nbsp;<a href="{{ url($ride->driver->slug) }}"
                                    class="btn btn-success btn-sm">{{ __('New Ride') }}</a>
                            </div>
                        </div>
                        <div class="row orderDetailsRow">
                            <div class="col-sm-4 text-sm-right">
                                <h6>{{ __('Date') }}</h6>
                            </div>
                            <div class="col-sm-8 text-left">
                                {{ $ride->created_at->format(getLocaleDateFormat(true)) }}
                            </div>
                        </div>
                        <div class="row orderDetailsRow">
                            <div class="col-sm-4 text-sm-right">
                                <h6>{{ __('Status') }}</h6>
                            </div>
                            <div class="col-sm-8 text-left">
                                {{ ucwords($ride->ride_status) }}
                            </div>
                        </div>
                        <div class="row orderDetailsRow">
                            <div class="col-sm-4 text-sm-right">
                                <h6>{{ __('Distance') }}</h6>
                            </div>
                            <div class="col-sm-8 text-left">
                                {{ number_format($ride->distance, 3, ',', '.') }} {{ setting('distance_unit') }}
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
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
@push('scripts')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-bootstrap-4@5.0.6/bootstrap-4.min.css">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
@endpush
