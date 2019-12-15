@extends('layouts.master')

@section('content')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    {{--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />--}}
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="http://malsup.github.com/jquery.form.js"></script>

<div class="container">
       <div class="row justify-content-center">
        <div class="offset-2 col-md-10">
             <div class="card" >
                <div class="card-header">Proposal Document
                </div>

        <div class="card-body card_body" style="overflow:auto;">
            <br />
            <p>Click <b>'Choose File'</b> and select a PDF document that describes your proposal. Then click <b>{{!empty($proposal->document) ? 'Replace' : 'Upload'}}</b>
            to send us the file. <br/>The file must be in PDF format and must be less than 20Mb in size.<br/>
            If you prefer to upload the document later, click <b>Upload later</b> below.</p>
            <p>To read the instructions for preparing the proposal document, <a href="{{action('Applicant\ProposalController@instructions', $id)}}">click here</a>.</p><br/>

            <div id="oldmessage" class="col-12">
            @if(!empty($proposal->document))
                You currently have an uploaded document. You can:<br/><br/>
            <a class="btn btn-primary" href="{{route('download', $proposal->document)}}" target="_blank">
                        <i class="fa fa-download"></i>&nbsp; Download uploaded document
                </a><br/><br/>
                <a class="btn btn-secondary" href="{{route('deletefile', $proposal->document)}}">
                        <i class="fa fa-trash"></i>&nbsp; Delete uploaded document
                </a>
            @else
                There are currently no uploaded documents
            @endif
            </div><br/><br/>

            <form method="post" action="{{ route('upload') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <input type="file" name="file" id="file" />
                        <input type="hidden" name="prop_id_file"  value="{{$id}}" />
                    </div>
                    <div class="col-md-6" align="right">
                        <input type="submit" name="upload" value="{{!empty($proposal->document) ? 'Replace' : 'Upload'}}" class="btn btn-success" />
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
            <br />
            <div class="row">
                <div class="col-md-12">
                    <div id="uploadlater"><a href="{{action('Applicant\ProposalController@activeProposal', $id)}}" class="btn btn-primary">Upload later</a></div>
                    <div id="uploaddone"><a href="{{action('Applicant\ProposalController@activeProposal', $id)}}" class="btn btn-primary">Go back</a></div>
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
                setTimeout(function(){
                    window.location.href = '{{route("currentproposals")}}';
                }, 1000);
            }
        });

    });
</script>
@endsection
