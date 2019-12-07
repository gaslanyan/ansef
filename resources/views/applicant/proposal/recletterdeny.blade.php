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



<div class="container">
    <div class="row justify-content-center">
        <div class="offset-2 col-md-10">
             <div class="card" >
                <div class="card-header">Recommendation Letter
                </div>
                <div class="card-body card_body" style="overflow:auto;">
                Incorrect confirmation code. Please contact <a href="mailto:dopplerthepom@gmail.com">dopplerthepom@gmail.com</a>.
                </div>
            </div>
        </div>
    </div>
</div>

</div>

</body>

<script src="{{ asset('js/bootstrap/js/bootstrap.min.js') }}"></script>
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

</html>
