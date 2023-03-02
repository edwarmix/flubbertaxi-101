@extends('layouts.app')
@section('title')
    {{__('Edit Vehicle Type')}}
@endsection
@section('content')
    <section class="section">
            <div class="section-header">
                <h3 class="page__heading m-0">{{__('Edit Vehicle Type')}}</h3>
                <div class="filter-container section-header-breadcrumb row justify-content-md-end">
                    <a href="{{ route('admin.vehicle_types.index') }}"  class="btn btn-primary">{{__('Back')}}</a>
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
                                    {!! Form::model($category, ['route' => ['admin.vehicle_types.update', $category->id], 'method' => 'patch','files' => true]) !!}
                                        <div class="row">
                                            @include('admin.vehicle_types.fields')
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
