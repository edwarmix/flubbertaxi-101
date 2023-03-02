@extends('layouts.app')
@section('title')
    {{__('Edit User')}}
@endsection
@section('content')
    <section class="section">
            <div class="section-header">
                <h3 class="page__heading m-0">{{__('Edit User')}}</h3>
                <div class="filter-container section-header-breadcrumb row justify-content-md-end">
                    <a href="{{ route('admin.users.index') }}"  class="btn btn-primary">{{__('Back')}}</a>
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
                                {!! Form::model($user, ['route' => ['admin.users.update', $user->id],'files' => true, 'method' => 'patch']) !!}
                                    <div class="row">
                                        @include('admin.users.fields')
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
