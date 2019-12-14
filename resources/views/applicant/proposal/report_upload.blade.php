@extends('layouts.master')

@section('content')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="http://malsup.github.com/jquery.form.js"></script>

<div class="container">
       <div class="row justify-content-center">
        <div class="offset-2 col-md-10">
             <div class="card" >
                <div class="card-header">Upload Report Document
                <a href="{{ action('Applicant\ProposalController@pastProposal') }}"
                    class="display float-lg-right btn-box-tool"> Go Back</a>
                </div>

        <div class="card-body card_body" style="overflow:auto;">
            <br />
            <p>Click <b>'Choose File'</b> and select a PDF document that summarizes the achievements. Then click <b>{{!empty($document) ? 'Replace' : 'Upload'}}</b>
            to send us the file. <br/>The file must be in PDF format and must be less than 20Mb in size.<br/>
            </p>

            <div id="oldmessage" class="col-12">
            @if(!empty($document))
                You currently have an uploaded document. You can:<br/><br/>
                <a class="btn btn-primary" href="\storage\proposal\prop-{{$id}}\report-{{$repid}}.pdf" target="_blank">
                        <i class="fa fa-download"></i>&nbsp; Download uploaded document
                </a><br/><br/>
                <a class="btn btn-secondary" href="{{action('Applicant\FileUploadController@removereport', $repid)}}">
                        <i class="fa fa-trash"></i>&nbsp; Delete uploaded document
                </a>
            @else
                There are currently no uploaded documents
            @endif
            </div><br/><br/>

            <form method="post" action="{{route('uploadreport')}}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <input type="file" name="file" id="file" />
                        <input type="hidden" name="prop_id"  value="{{$id}}" />
                        <input type="hidden" name="rep_id"  value="{{$repid}}" />
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
                    window.location.href = '{{route("pastproposals")}}';
                    // var newloc = 'http://' + window.location.host + '/report-upload/' + '{{$repid}}';
                    // window.location.replace(newloc);
                }, 1000);

            }
        });

    });
</script>
@endsection
