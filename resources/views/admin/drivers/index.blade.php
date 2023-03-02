@extends('layouts.app')
@section('title')
    {{ __('Drivers') }}
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <div class="col-6 text-left">
                <h1>{{ __('Drivers') }}</h1>
            </div>
        </div>

        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @include('flash::message')
                    @include('stisla-templates::common.errors')
                    @include('admin.drivers.table')
                </div>
            </div>
        </div>

    </section>
@endsection
