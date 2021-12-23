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

    <!-- blockUI 訊息內容 -->
    <link rel="stylesheet" type="text/css" href="{{asset('css/op_block.css')}}" />

    <!-- icon -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
          integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p"
          crossorigin="anonymous"/>

    @section('style')
    @show
</head>
<body>
    <!-- 導覽列 -->
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd;">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><b>後台管理</b></a>
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

    <!-- 動作完成的 flag -->
    @include('layouts/op_block')

    <!-- jQuery 3.6 -->
    <script src="{{ asset('js/jquery/jquery-3.6.0.min.js') }}"></script>
    <!-- bootstrap 5 -->
    <script src="{{ asset('js/bootstrap/bootstrap.bundle.js') }}"></script>
    <!-- blockUI -->
    <script src="{{ asset('js/jquery/jquery.blockUI.js') }}"></script>

    @section('script')
    @show
</body>
</html>
