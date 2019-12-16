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
    <script src="{{ asset('js/jquery/jquery.form.min.js') }}"></script>

</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">



<div class="container">
       <div class="row justify-content-center">
        <div class="offset-2 col-md-10">
             <div class="card" >
                <div class="card-header">Recommendation Letter for {{$pi->first_name}} {{$pi->last_name}}
                </div>

        <div class="card-body card_body" style="overflow:auto;">
            <br />
            <p>{{$pi->first_name}} {{$pi->last_name}} is submitting a grant proposal to the Armenian
            National Science and Education Fund as a Principal Investigator and has listed you as a person who would provide a letter
            of support. Please write your letter on an official letterhead if possible, and upload a signed PDF.</p>
            <p>
                Your letter will be treated confidentially and will not be seen by the Principal Investigator.
            </p>
            <p>Click <b>'Choose File'</b> below and select your PDF document. Then click <b>{{!empty($document) ? 'Replace' : 'Upload'}}</b>
            to send us the file. <br/>The file must be in PDF format and must be less than 20Mb in size.</p>

            <div id="oldmessage" class="col-12">
            @if(!empty($document))
                You currently have an uploaded letter. You can:<br/><br/>
                <a class="btn btn-primary" href="\storage\proposal\prop-{{$prop}}\letter-{{$id}}.pdf" target="_blank">
                        <i class="fa fa-download"></i>&nbsp; Download uploaded letter to verify content
                </a><br/><br/>
                <a class="btn btn-secondary" href="{{action('Applicant\FileUploadController@removeletter', $id)}}">
                        <i class="fa fa-trash"></i>&nbsp; Delete uploaded letter
                </a>
            @else
                There is currently no uploaded letter
            @endif
            </div><br/><br/>

            <form method="post" action="{{ route('uploadletter') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <input type="file" name="file" id="file" />
                        <input type="hidden" name="prop_id_file"  value="{{$prop}}" />
                        <input type="hidden" name="rec_id_file"  value="{{$id}}" />
                    </div>
                    <div class="col-md-6" align="right">
                        <input type="submit" name="upload" value="{{!empty($document) ? 'Replace' : 'Upload'}}" class="btn btn-success" />
                    </div>
                </div>
            </form>
            <br />

            <div class="progress">
                <div class="progress-bar" role="progressbar" aria-valuenow=""
                     aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    0%
                </div>
            </div>
            <br />
            @if($errors->any())
            <p style="color:indigo;font-size:22px;">{{$errors->first()}}</p>
            @endif
        </div>
            </div>
</div>
       </div>
</div>

</div>

<script>
    $(document).ready(function(){
        $('#uploaddone').hide();
        $('#newmessage').hide();

        $('form').ajaxForm({
            beforeSend:function(){
                $('#success').empty();
            },
            uploadProgress:function(event, position, total, percentComplete)
            {
                $('.progress-bar').text(percentComplete + '%');
                $('.progress-bar').css('width', percentComplete + '%');
            },
            success:function(data)
            {
                console.log(data);
                if(data.errors)
                {
                    $('.progress-bar').text('0%');
                    $('.progress-bar').css('width', '0%');
                    $('#success').html('<span class="text-danger"><b>'+data.errors+'</b></span>');
                    $('#oldmessage').hide();
                }
                if(data.success)
                {
                    $('.progress-bar').text('Uploaded');
                    $('.progress-bar').css('width', '100%');
                    $('#success').html('<span class="text-success"><b>'+data.success+'</b></span><br /><br />');
                    $('#oldmessage').hide();
                    $('#uploadlater').hide();
                    $('#newmessage').show();
                    $('#uploaddone').show();
                }
            }
        });

    });
</script>


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
</body>
</html>





