@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">Edit Proposal
                        {{-- <a href="{{action('Applicant\ProposalController@generatePDF',$proposal->id)}}"
                           title="Download"
                           class="add_honors"><i class="fa fa-download"></i>
                        </a> --}}

                    </div>

                    <div class="card-body card_body" style="overflow:auto;">
                        @include('partials.status_bar')

                        <form method="post" action="{{action('Applicant\ProposalController@update', $proposal->id)}}"
                              class="row" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input name="_method" type="hidden" value="PATCH">
                            @if(!empty($budget_message))
                            <div class="form-group col-lg-12">
                                <i class="fas fa-question-circle text-red all">{{$budget_message}}</i>
                            </div>
                            @endif

                            <div class="form-group col-lg-12">
                                <label for="category0"><b>Proposal reference number:</b></label>
                                    <h3>{{getProposalTag($proposal->id)}}</h3>
                            </div>
                            <div class="form-group col-lg-6" style="margin:0px">
                                <b>Category:</b>
                                    {{$cat_parent != null ? $cat_parent->title : 'None'}}
                            </div>
                            <div class="form-group col-lg-6" style="margin:0px">
                                <b>Subcategory:</b>
                                    {{$cat_sub != null ? $cat_sub->title : 'None'}}
                            </div>
                            <div class="form-group col-lg-6" style="margin:0px">
                                <b>Secondary Category:</b>
                                    {{$cat_sec_parent != null ? $cat_sec_parent->title : 'None'}}
                            </div>
                            <div class="form-group col-lg-6" style="margin:0px">
                                <b>Secondary Subcategory:</b>
                                    {{$cat_sec_sub != null ? $cat_sec_sub->title : 'None'}}
                            </div>
                            <div class="form-group col-lg-12" style="margin-top:20px">
                                <label for="title">Proposal Title (<span id="titlecharleft">25</span> words left):</label>
                                <input type="text" class="form-control" name="title" id="title"
                                       value="{{$proposal->title}}" <?php if ($proposal->state == 'awarded' || $proposal->state == 'complete' || $proposal->state == 'unsuccessfull' || $proposal->state == 'disqualified') echo "disabled";?>>
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="abstract">Abstract (<span id="abstractcharleft">250</span> words left):</label>
                                <textarea rows="4" class="form-control" name="abstract"
                            id="abstract"">{{$proposal->abstract}}</textarea>
                            </div>


                            @if(!empty($ins))
                                      <div class="form-group col-lg-12">
                                        <h4>Primary Institution for Proposal (if any):</h4>
                                        <select id="inst" class="form-control" name="institution[]">
                                            <option value="0">Select institution</option>
                                            @if(!empty($institutions))
                                                @foreach($institutions as $val=>$item)
                                                    <option class="text-capitalize" value="{{$val}}"  <?php if($val == $ins['id']) echo 'selected'; ?> >{{$item}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    <input type="text" id="insttext" class="form-control" name="institutionname" value="{{$ins['name']}}" placeholder="If your institution is not in the list, type instead the name here">
                                    </div>
                            @endif

                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="{{ action('Applicant\ProposalController@activeProposal') }}" class="btn btn-secondary"> Cancel</a>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
        <!--  Proposal Reports section whet state is awarded  -->

    </div>

    </section><!-- /.content -->

    <!--- Modal --->
    </div>

    <!-- Modal form-->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
    {{--<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />--}}
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
    <script src="https://ansef.gitc.am/js/jquery.form.js"></script>
    <div class="modal fade" id="myModal_report" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h2> Upload Report</h2>
                    <button type="button" class="close" data-dismiss="modal"><span
                                aria-hidden="true">&times;</span><span
                                class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel"></h4>
                </div>
                {{--<form method="post" action="">--}}
                <div class="modal-body" id="modal-bodyku">

                    <div class="card-body card_body" style="overflow:auto;">
                        <br/>
                        <form method="post" action="{{ route('uploadreport') }}" id="form"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <label>Report Description:</label>
                                    <textarea rows="4" class="form-control" name="report_description"
                                              id="doc_description"></textarea>
                                </div>
                            </div>
                            <br/>
                            <div class="row">
                                <div class="col-md-2" align="right"><h6>Select Image</h6></div>
                                <div class="col-md-6">
                                    <input type="file" name="report_file" id="file"/>
                                    <input type="hidden" name="prop_id_file" value="{{$proposal->id}}"/>
                                </div>
                                <div class="col-md-4">
                                    <input type="submit" name="upload" value="Upload & Save"
                                           class="btn btn-success"/>
                                </div>
                            </div>
                        </form>
                        <br/>
                        <div class="progress">upload
                            <div class="progress-bar" role="progressbar" aria-valuenow=""
                                 aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                                0%
                            </div>
                        </div>
                        <br/>
                        <div id="success">

                        </div>
                        <br/>
                        <div class="row">
                            <div class="col-md-12">
                                <i class="fas fa-question-circle text-red"> For Deleting uploaded file please click Cancel
                                    button </i><br/>
                                <a href="{{action('Applicant\FileUploadController@remove', $proposal->id)}}"
                                   class="btn btn-primary">Cancel</a>
                            </div>
                        </div>

                    </div>


                </div>
                <div class="modal-footer" id="modal-footerq">
                    <button type="button" class="close btn btn-default"
                            data-dismiss="modal">Close
                    </button>
                    {{--<button type="button" class="btn btn-primary"--}}
                    {{--data-dismiss="modal" id="choose">Choose--}}
                    {{--</button>--}}
                </div>
                <input type="hidden" class="form-control form-check-inline aaa"
                       name="hidden_choose_person[]" value="" id="aaa">
                {{--</form>--}}
            </div>
        </div>
    </div>
    <!-- end of modal ------------------------------>

    <script>
        function open_container() {
            var size = 'large'; //small,standart,large document.getElementById('mysize').value;
            var content = '';//<form role="form"><div class="form-group"><label for="exampleInputEmail1">Email address</label><input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email"></div><div class="form-group"><label for="exampleInputPassword1">Password</label><input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password"></div><div class="form-group"><label for="exampleInputFile">File input</label><input type="file" id="exampleInputFile"><p class="help-block">Example block-level help text here.</p></div><div class="checkbox"><label><input type="checkbox"> Check me out</label></div><button type="submit" class="btn btn-default">Submit</button></form>';
            var title = 'Choose a person';
            var footer = '';//'<button type="button" class="btn btn-default" data-dismiss="modal">Close</button><button type="button" class="btn btn-primary">Save changes</button>';
            jQuery.noConflict();
                jQuery('#myModal').attr('class', 'modal fade bs-example-modal-lg')
                    .attr('aria-labelledby', 'myLargeModalLabel');
                jQuery('.modal-dialog').attr('class', 'modal-dialog modal-lg');
            jQuery('#myModal').modal('show');
        }

        jQuery(document).on('click', '#choose', function () {
            //console.log(jQuery('[name="choose_person"]')[0]);
            jQuery('#prop_person').text("");
            var chp = [];
            var checkedIDss = [];
            var step = 0;
            var checkedIDs = [];

            jQuery("input:checkbox:checked").each(function (i, val) {
                // console.log($(this).parent().parent().next().next().find('select').val());
                chp['fullname' + step] = jQuery("#choose_person_name" + step).val();
                chp['type' + step] = jQuery("#choose_person_types" + step).val();
                chp['p_id' + step] = jQuery("#person_id" + step).val();
                $g = "/applicant/person/" + ($(this).val()).split('_')[0] + "/edit";
                var id =($(this).val()).split('_')[0];

                var personname = $(this).next().val();
                var persontype = $(this).parent().parent().next().next().find('select').val();

                if(persontype != "None")
                    jQuery('#prop_person').append('<p><input type="hidden"  value = "' + personname + '"  id = \'prop_person_name\' disabled class="form-control form-check-inline form-group col-lg-5" name="prop_person_name[]"><b>' + personname + ': </b>' +
                        '                          <input type="hidden"  value = "' + persontype + '"  id = \'prop_person_stype\' disabled class="form-control form-check-inline form-group col-lg-2" name="prop_person_stype[]">' + persontype.charAt(0).toUpperCase() + persontype.slice(1) +
                        '&nbsp;&nbsp;<div title="Delete" class="btn-link unchecked" id = "'+id+'" ><i class="fa fa-trash"></i></div></p> ');


                step++;
            });
        });

        jQuery('.tt').change(function(){
            jQuery('.tt').val();
            $val = $(this).parent().prev().prev().find('input:checkbox:checked').val();
            $newval = $val +"_"+ jQuery(this).val();
            $(this).parent().prev().prev().find('input:checkbox:checked').val($newval);
            // $('#choose').removeAttr('disabled');
        });

	function count($field, $span){
	  var txtVal = jQuery($field).val();
	  var words = txtVal.trim().replace(/\s+/gi, ' ').split(' ').length;
// 	  var chars = txtVal.length;
// 	  if(chars===0){words=0;}
	  return words;
	}

        jQuery('#title').on('keyup propertychange paste', function(){
	        jQuery('#titlecharleft').html((25-count('#title'))+'');
        });


        jQuery('#abstract').on('keyup propertychange paste', function(){
	        jQuery('#abstractcharleft').html((250-count('#abstract'))+'');
        });

        var _type = "";

        function open_container_report(type) {
            var size = 'large'; //small,standart,large document.getElementById('mysize').value;
            var content = '';//<form role="form"><div class="form-group"><label for="exampleInputEmail1">Email address</label><input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email"></div><div class="form-group"><label for="exampleInputPassword1">Password</label><input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password"></div><div class="form-group"><label for="exampleInputFile">File input</label><input type="file" id="exampleInputFile"><p class="help-block">Example block-level help text here.</p></div><div class="checkbox"><label><input type="checkbox"> Check me out</label></div><button type="submit" class="btn btn-default">Submit</button></form>';
            var title = 'Upload ' + type;
            var footer = '';//'<button type="button" class="btn btn-default" data-dismiss="modal">Close</button><button type="button" class="btn btn-primary">Save changes</button>';
            _type = type;
            jQuery.noConflict();
            setModalBox(size, title);
            jQuery('#myModal_report').modal('show');
        }

        function setModalBox(title, content, footer, $size) {
            //document.getElementById('modal-bodyku').innerHTML=content;
            document.getElementById('myModalLabel').innerHTML = title;
            // document.getElementById('modal-footerq').innerHTML=footer;

            if ($size == 'large') {
                jQuery('#myModal_report').attr('class', 'modal fade bs-example-modal-lg')
                    .attr('aria-labelledby', 'myLargeModalLabel');
                jQuery('.modal-dialog').attr('class', 'modal-dialog modal-lg');
            }
        }

        $('.close').click(function () {
            document.location.reload(true);
        });
    </script>
    <script>
        $(document).ready(function () {
	        jQuery('#titlecharleft').html((25-count('#title'))+'');
	        jQuery('#abstractcharleft').html((250-count('#abstract'))+'');

            $('#form').ajaxForm({
                beforeSend: function () {
                    $('#success').empty();
                },
                uploadProgress: function (event, position, total, percentComplete) {
                    $('.progress-bar').text(percentComplete + '%');
                    $('.progress-bar').css('width', percentComplete + '%');
                },
                success: function (data) {
                    if (data.errors) {
                        $('.progress-bar').text('0%');
                        $('.progress-bar').css('width', '0%');
                        $('#success').html('<span class="text-danger"><b>' + data.errors + '</b></span>');
                    }
                    if (data.success) {
                        $('.progress-bar').text('Uploaded');
                        $('.progress-bar').css('width', '100%');
                        $('#success').html('<span class="text-success"><b>' + data.success + '</b></span><br /><br />');
                        $('#success').append(data.pdf);
                    }
                }
            });

        });
    </script>
@endsection

