@extends('layouts.app')
@section('title')
    {{__('Vehicle Types')}}
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{__('Vehicle Types')}}</h1>

            <div class="section-header-breadcrumb">
                <a href="{{ route('admin.vehicle_types.create')}}" class="btn btn-primary form-btn">{{__('Vehicle Type')}} <i class="fas fa-plus"></i></a>
            </div>
        </div>
    <div class="section-body">
       <div class="card">
            <div class="card-body">
                @include('flash::message')
                @include('stisla-templates::common.errors')
                @include('admin.vehicle_types.table')
            </div>
       </div>
   </div>

    </section>
@endsection

