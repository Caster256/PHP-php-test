@extends('layouts.master')

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/account.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap/bootstrap-datepicker.css') }}" />
@stop

@section('content')
    <div class="row l_r">
        <div class="col" id="add-account-div">
            <button type="button" class="btn btn-primary" id="add-account-btn">新增</button>
        </div>
    </div>
@stop

@section('script')
{{--    <script src="{{ asset('jquery/jquery.flexibleArea.js') }}"></script>--}}
    <script src="{{ asset('js/jquery/jquery.validate.js') }}"></script>
    <script src="{{ asset('js/bootstrap/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('js/account.js') }}"></script>
@stop
