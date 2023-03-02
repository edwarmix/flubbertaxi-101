@extends('vendor.installer.layouts.master')

@section('template_title')

@endsection

@section('title')
    <i class="fas fa-cog fa-fw" aria-hidden="true"></i>
    {!! trans('installer_messages.environment.menu.title') !!}
@endsection

@section('container')
    @if(request()->session()->has('error'))
        <div class="alert alert-danger">
            <p>{{ request()->session()->get('error') }}</p>
        </div>
    @endif
    <h4 class="text-center">
        Complete your purchase on <a href="https://codecanyon.net/user/branchminer" target="_blank">CodeCanyon</a> to get your purchase code.
    </h4>
@endsection
