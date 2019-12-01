<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{asset('css/app.css')}}"/>
    <link rel="stylesheet" href="{{asset('css/AdminLTE.min.css')}}"/>
    {{-- <link rel="stylesheet" href="{{asset('css/skins/_all-skins.min.css')}}"/> --}}
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/solid.css" integrity="sha384-QokYePQSOwpBDuhlHOsX0ymF6R/vLk/UQVz3WHa6wygxI5oGTmDTv8wahFOSspdm" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/fontawesome.css" integrity="sha384-vd1e11sR28tEK9YANUtpIOdjGW14pS87bUBuOIoBILVWLFnS+MCX9T6MMf0VdPGq" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('DataTables/datatables.min.css')}}"/>
    <style>
    th, td { white-space: nowrap; }
    </style>

    @yield('stylesheets')
    <script src="{{ asset('js/jquery/jquery-3.3.1.min.js') }}"></script>


</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    @include('partials.header')

    @php echo $c_user = cookieSign_id(); @endphp
    @if(!empty($c_user))
        @include('partials.'.$c_user->user->role->name.'_sidebar')
    @else
        @include('partials.'.get_Cookie().'_sidebar')
    @endif


    @yield('content')
</div>


{{--<script--}}
{{--src="http://code.jquery.com/jquery-3.3.1.slim.min.js"--}}
{{--integrity="sha256-3edrmyuQ0w65f8gfBsqowzjJe2iM6n0nKciPUp8y+7E="--}}
{{--crossorigin="anonymous"></script>--}}
<script src="{{ asset('js/jquery/dist/popper.min.js') }}"></script>
<script src="{{ asset('js/bootstrap/js/bootstrap.js') }}"></script>
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
