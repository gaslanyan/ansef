<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}"/>
    {{-- <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}"/> --}}
    <link rel="stylesheet" href="{{asset('css/AdminLTE.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/skins/skin-blue-light.min.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/solid.min.css') }}"/>
    <link rel="stylesheet" href="{{asset('css/fontawesome.min.css') }}"/>
    <link rel="stylesheet" href="{{asset('bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('DataTables/datatables.min.css')}}"/>

    @yield('stylesheets')
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('js/jquery/jquery.form.min.js') }}"></script>
    <script src="{{ asset('js/jquery.slimscroll.min.js') }}"></script>
    <style>
    th, td { white-space: nowrap; }
    </style>


</head>
{{-- <body class="hold-transition sidebar-mini"> --}}
<body class="skin-blue-light fixed">
<div class="wrapper">
    @include('partials.header')

    <div class="sidebar-wrapper">
        @include('partials.'.get_role_cookie().'_sidebar')
    </div>
    <div id="" class="content-wrapper">
        @yield('content')
    </div>
</div>

{{-- <script src="{{ asset('js/bootstrap/js/bootstrap.min.js') }}"></script> --}}
<script src="{{ asset('bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('js/adminlte.min.js') }}"></script>
<script src="{{ asset('DataTables/datatables.min.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/init.js') }}"></script>

<script>
    $.noConflict();
    $('.datepicker').datepicker({
        uiLibrary: 'bootstrap4',
        format: 'yyyy-mm-dd'
    });
</script>

@yield('scripts')
</body>
</html>
