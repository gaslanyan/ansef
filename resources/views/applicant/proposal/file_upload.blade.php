@extends('layouts.master')

@section('content')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    {{--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />--}}
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="http://malsup.github.com/jquery.form.js"></script>

<div class="container">
       <div class="row justify-content-center">
        <div class="offset-2 col-md-10">
            <div class="card">
                <div class="card-header">Upload Proposal Document
                    <a href="{{ action('Applicant\ProposalController@activeProposal') }}"
                       class="display float-lg-right btn-box-tool"> Back</a>
                </div>

        <div class="card-body card_body">
            <br />
            <form method="post" action="{{ route('upload') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4" align="right"><h4>Select Document(.pdf)</h4></div>
                    <div class="col-md-5">
                        <input type="file" name="file" id="file" />
                        <input type="hidden" name="prop_id_file"  value="{{$id}}" />
                    </div>
                    <div class="col-md-3">
                        <input type="submit" name="upload" value="Upload&Save" class="btn btn-success" />
                    </div>
                </div>
            </form>
            <br />
            <div class="progress">upload
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
                <i class="fa fa-info text-red"> For Deleting uploaded file please click Cancel button </i><br/>
                <a href="{{action('Applicant\FileUploadController@remove', $id)}}" class="btn btn-primary">Cancel</a>
                <a href="{{ action('Applicant\ProposalController@activeProposal') }}"
                   class="btn btn-primary"> Next</a>
                </div>
             </div>

        </div>
            </div>
</div>
       </div>
</div>
    <script>
    $(document).ready(function(){

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
                if(data.errors)
                {
                    $('.progress-bar').text('0%');
                    $('.progress-bar').css('width', '0%');
                    $('#success').html('<span class="text-danger"><b>'+data.errors+'</b></span>');
                }
                if(data.success)
                {
                    $('.progress-bar').text('Uploaded');
                    $('.progress-bar').css('width', '100%');
                    $('#success').html('<span class="text-success"><b>'+data.success+'</b></span><br /><br />');
                    $('#success').append(data.pdf);
                }
            }
        });

    });
</script>
@endsection