@extends('layouts.master')

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('css/account.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap/bootstrap-datepicker.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('packages/DataTables/datatables.min.css') }}">
@stop

@section('content')
    <div class="row l_r">
        <div class="col-4">
            <button type="button" class="btn btn-danger del-btn" disabled>
                刪除
            </button>
        </div>
        <div class="col-8" id="btn-div">
            <button type="button" class="btn btn-primary" id="add-account-btn">新增</button>
            <button type="button" class="btn btn-success" id="import-btn">匯入</button>
            <button type="button" class="btn btn-success" id="export-btn">匯出</button>
        </div>
    </div>
    <div class="row l_r">
        <div class="col">
            <table class="table table-hover" id="account-info-tab">
                <thead>
                    <tr>
                        <th></th>
                        <th style="width: 4%;">
                            <label>
                                <input type="checkbox" class="form-check-input" id="del-all-account-info" />
                            </label>
                        </th>
                        <th>帳號</th>
                        <th>姓名</th>
                        <th>性別</th>
                        <th>生日</th>
                        <th>信箱</th>
                        <th>備註</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($list as $item)
                        <tr class="account-info-tr" data-id="{{ $item['id'] }}">
                            <td></td>
                            <td>
                                <label>
                                    <input type="checkbox" class="form-check-input" name="del-account-info" />
                                </label>
                            </td>
                            <td class="td-account data-row">{{ $item['account'] }}</td>
                            <td class="td-username data-row">{{ $item['username'] }}</td>
                            <td class="td-gender data-row" data-val="{{ getGender($item['gender']) }}">
                                {{ $item['gender'] }}
                            </td>
                            <td class="td-birthday data-row" data-val="{{ getDate4DB($item['birthday']) }}">
                                {{ $item['birthday'] }}
                            </td>
                            <td class="td-email data-row">{{ $item['email'] }}</td>
                            <td class="td-remark data-row">{{ $item['remark'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- 新增修改帳號的 modal -->
    @include('Modal/account')
    <!-- 匯出的 modal -->
    @include('Modal/export')
    <!-- 匯入的 modal -->
    @include('Modal/import')
@stop

@section('script')
{{--    <script src="{{ asset('jquery/jquery.flexibleArea.js') }}"></script>--}}
    <script src="{{ asset('js/jquery/jquery.validate.js') }}"></script>
    <script src="{{ asset('js/bootstrap/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('packages/DataTables/datatables.min.js') }}"></script>
    <script src="{{ asset('js/account.js') }}"></script>
@stop
