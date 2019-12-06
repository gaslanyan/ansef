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
                <div class="card-header">Recommendation Document
                </div>

        <div class="card-body card_body">
            <br />
            <p>Click <b>'Choose File'</b> and select a PDF document. Then click <b>{{!empty($document) ? 'Replace' : 'Upload'}}</b>
            to send us the file. <br/>The file must be in PDF format and must be less than 20Mb in size.<br/>
            If you prefer to upload the document later, click <b>Upload later</b> below.</p>

            <div id="oldmessage" class="col-12">
            @if(!empty($document))
                You currently have an uploaded document. You can:<br/><br/>
                <a class="btn btn-primary" href="\storage\proposal\prop-{{$id}}\document.pdf" target="_blank">
                        <i class="fa fa-download"></i>&nbsp; Download uploaded document
                </a><br/><br/>
                <a class="btn btn-secondary" href="{{action('Applicant\FileUploadController@remove', $id)}}">
                        <i class="fa fa-trash"></i>&nbsp; Delete uploaded document
                </a>
            @else
                There are currently no uploaded documents
            @endif
            </div><br/><br/>

            <form method="post" action="{{ route('uploadletter') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <input type="file" name="file" id="file" />
                        <input type="hidden" name="prop_id_file"  value="{{$id}}" />
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
            <div id="success">
            </div>
            <div id="newmessage">
                <a class="btn btn-primary" href="\storage\proposal\prop-{{$id}}\letter.pdf" target="_blank">
                        <i class="fa fa-download"></i>&nbsp; Download and verify uploaded document
                </a><br/><br/>
                <a class="btn btn-secondary" href="{{action('Applicant\FileUploadController@removeletter', $id)}}">
                        <i class="fa fa-trash"></i>&nbsp; Delete uploaded document
                </a>
            </div>
            <br />
            <div class="row">
                <div class="col-md-12">
                    <div id="uploaddone"><a href="{{action('Applicant\ProposalController@activeProposal', $id)}}" class="btn btn-primary">Done</a></div>
                </div>
             </div>
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





