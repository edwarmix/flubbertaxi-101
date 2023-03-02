@extends('layouts.app')
@section('title')
    {{ __('Driver Payout Details') }}
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('Driver Payout Details') }}</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ route('admin.driverPayouts.index') }}"
                    class="btn btn-primary form-btn float-right">{{ __('Back') }}</a>
            </div>
        </div>
        @include('flash::message')
        @include('stisla-templates::common.errors')
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @include('admin.driver_payouts.show_fields')
                </div>
            </div>
        </div>
    </section>
@endsection
