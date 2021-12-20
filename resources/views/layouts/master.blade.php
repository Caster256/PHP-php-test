<!DOCTYPE html>
<html lang="tw">
<head>
    <title>{{ $title }}</title>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicon -->
    <link rel="shortcut icon" type="text/css" href="{{ asset('favicon.png' )}}">

    <!-- bootstrap 5 -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/bootstrap/bootstrap.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{asset('css/op_block.css')}}" />

    <!-- DataTables -->
    <link rel="stylesheet" type="text/css" href="{{ asset('css/DataTables/datatables.min.css') }}">

    @section('style')
    @show
</head>
<body>
    <!-- 導覽列 -->
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd;">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">後台管理</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">帳號</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- 內容 -->
    <div class="container">
        @yield('content')
    </div>

    <!-- 新增修改帳號的 modal -->
    @include('Modal/account')

    <!-- 動作完成的 flag -->
    @include('layouts/op_block')

    <!-- jQuery 3.6 -->
    <script src="{{ asset('js/jquery/jquery-3.6.0.min.js') }}"></script>
    <!-- bootstrap 5 -->
    <script src="{{ asset('js/bootstrap/bootstrap.bundle.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('js/DataTables/datatables.min.js') }}"></script>
    <!-- blockUI -->
    <script src="{{ asset('js/jquery/jquery.blockUI.js') }}"></script>

    @section('script')
    @show
</body>
</html>
