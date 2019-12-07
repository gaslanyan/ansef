@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-2 col-md-10">
                <div class="card" >
                    <div class="card-header">Add A New Proposal</div>
                    <div class="card-body card_body" style="overflow:auto;">
                        @include('partials.status_bar')

                        @if(count($competitions) == 0)
                            <h5>There are no competitions that you can apply to at this time. Check back again later.</h5>
                                <br/>
                                <a href="{{ action('Applicant\ProposalController@activeProposal') }}" class="btn btn-secondary">Go Back</a>
                        @else
                        <h5>Choose a competition first</h5>
                        <form method="post" action="{{action('Applicant\ProposalController@store')}}" class="row"
                              enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="form-group col-lg-12 align-items-center" id="comp_container">
                                <select class="form-control -align-center comp_prop" name="comp_prop" id="comp_prop">
                                    <option value="choosecompetition">Choose the competition</option>
                                    @foreach($competitions as $competition)
                                        <option value="{{$competition->id}}">{{$competition->title}}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" value="" name="domesticorforeign" class="domesticorforeign"/>
                                <input type="hidden" value="" name="recommendation" class="recommendation"/>
                            </div>

                            <div class="form-group col-lg-6">
                                <label for="category0">Category *:</label>
                                <select class="form-control cat" name="category[]" id="category0">
                                    <option value="0">Select Category</option>
                                </select>

                            </div>
                            <div class="form-group col-lg-6">
                                <label for="sub_category0">Subcategory *:</label>
                                <select type="text" class="form-control" name="sub_category[]" id="sub_category0">
                                    <option value="0">Select Sub Category</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="category1">Secondary Category (optional):</label>
                                <select class="form-control cat" name="sec_category[]" id="category1">
                                    <option value="0">Select Secondary Category</option>
                                </select>

                            </div>
                            <div class="form-group col-lg-6">
                                <label for="sub_category1"> Secondary Subcategory (optional):</label>
                                <select class="form-control" name="sec_sub_category[]" id="sub_category1">
                                    <option value="0">Select Sub Category</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="title">Proposal Title * (<span id="titlecharleft">25</span> words left):</label>
                                <input type="text" class="form-control" name="title" value="{{ old('title') }}" id="title">
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="abstract">Abstract * (<span id="abstractcharleft">250</span> words left):</label>
                                <textarea rows="4" class="form-control" name="abstract" id="abstract">{{ old('abstract') }}</textarea>
                            </div>
                            <hr>

                            <div class="col-lg-12">
                                <h4>Primary Institution for Proposal (if any):</h4>
                                <div class="row institution">
                                    <div class="form-group col-lg-12">
                                        <label for="inst"></label>
                                        <select id="inst" class="form-control" name="institution">
                                            <option value="0">Select institution</option>
                                            @if(!empty($institutions))
                                                @foreach($institutions as $val=>$item)
                                                    <option class="text-capitalize" value="{{$val}}" <?php $val == old('institution') ? 'selected' : '' ?>>{{$item}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    <input type="text" id="insttext" class="form-control" name="institutionname" value="{{old('institutionname')}}" placeholder="If your institution is not in the list, type instead the name here">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="{{ action('Applicant\ProposalController@activeProposal') }}" class="btn btn-secondary"> Cancel</a>
                            </div>
                            </div>


                        </form>
                        @endif


                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>


        function open_container() {
            var size = 'large'; //small,standart,large document.getElementById('mysize').value;
            var content = '';//<form role="form"><div class="form-group"><label for="exampleInputEmail1">Email address</label><input type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email"></div><div class="form-group"><label for="exampleInputPassword1">Password</label><input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password"></div><div class="form-group"><label for="exampleInputFile">File input</label><input type="file" id="exampleInputFile"><p class="help-block">Example block-level help text here.</p></div><div class="checkbox"><label><input type="checkbox"> Check me out</label></div><button type="submit" class="btn btn-default">Submit</button></form>';
            var title = 'Choose a person';
            var footer = '';//'<button type="button" class="btn btn-default" data-dismiss="modal">Close</button><button type="button" class="btn btn-primary">Save changes</button>';
            jQuery.noConflict();
            jQuery.noConflict();
            jQuery('#myModal').attr('class', 'modal fade bs-example-modal-lg')
                .attr('aria-labelledby', 'myLargeModalLabel');
            jQuery('.modal-dialog').attr('class', 'modal-dialog modal-lg');
            jQuery('#myModal').modal('show');
        }

        function setModalBox(title, content, footer, $size) {
            //document.getElementById('modal-bodyku').innerHTML=content;
            //document.getElementById('myModalLabel').innerHTML=title;
            // document.getElementById('modal-footerq').innerHTML=footer;
            if ($size == 'large') {
                jQuery('#myModal').attr('class', 'modal fade bs-example-modal-lg')
                    .attr('aria-labelledby', 'myLargeModalLabel');
                jQuery('.modal-dialog').attr('class', 'modal-dialog modal-lg');
            }
            if ($size == 'standart') {
                jQuery('#myModal').attr('class', 'modal fade')
                    .attr('aria-labelledby', 'myModalLabel');
                jQuery('.modal-dialog').attr('class', 'modal-dialog');
            }
            if ($size == 'small') {
                jQuery('#myModal').attr('class', 'modal fade bs-example-modal-sm')
                    .attr('aria-labelledby', 'mySmallModalLabel');
                jQuery('.modal-dialog').attr('class', 'modal-dialog modal-sm');
            }
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
                chp['type' + step] = jQuery("#choose_person_type" + step).val();
                chp['p_id' + step] = jQuery("#person_id" + step).val();
                $g = "/applicant/person/" + ($(this).val()).split('_')[0] + "/edit";
                var id =($(this).val()).split('_')[0];

                var personname = $(this).next().val();
                var persontype = $(this).parent().parent().next().next().find('select').val();

                if(persontype != "None")
                jQuery('#prop_person').append('<p><input type="hidden"  value = "' + personname + '"  id = \'prop_person_name\' disabled class="form-control form-check-inline form-group col-lg-5" name="prop_person_name[]"><b>' + personname + ': </b>' +
                    // '                          <input type="text"  value = "' + $(this).next().next().val() + '"  id = \'prop_person_type\' disabled class="form-control form-check-inline form-group col-lg-2" name="prop_person_type">' +
                    '                          <input type="hidden"  value = "' + persontype + '"  id = \'prop_person_stype\' disabled class="form-control form-check-inline form-group col-lg-2" name="prop_person_stype[]">' + persontype.charAt(0).toUpperCase() + persontype.slice(1) +
                    // '                          <a href="' + $g + '" title="Edit" class="btn-link full_edit" target="_blank"><i class="fa fa-edit"></i> </a> ' +
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

      $(document).on('click', '.unchecked', function () {
            var val = $(this).attr('id');
             $(this).prev().prev().prev().prev().remove();
             $(this).prev().prev().prev().remove();
             $(this).prev().prev().remove();
             $(this).prev().remove();
             $(this).remove();

          $("input[value^='"+val+"']" ).prop("checked", false);
         });

    </script>
@endsection
