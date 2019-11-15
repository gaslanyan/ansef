@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-2 col-md-10">
                <div class="card" style="margin-top:20px;">
                    <div class="card-header">Add A New Proposal</div>
                    <div class="card-body card_body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                            </div><br/>
                        @endif
                        @if (\Session::has('error'))
                            <div class="alert alert-danger">
                                <p>@php echo html_entity_decode(\Session::get('error'), ENT_HTML5) @endphp</p>
                            </div>
                        @endif
                        @if (\Session::has('delete'))
                            <div class="alert alert-info">
                                <p>@php echo html_entity_decode(\Session::get('delete'), ENT_HTML5) @endphp</p>
                            </div>
                        @endif

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
                                <label for="category0">Category:</label>
                                <select class="form-control cat" name="category[]" id="category0">
                                    <option value="0">Select Category</option>
                                </select>

                            </div>
                            <div class="form-group col-lg-6">
                                <label for="sub_category0">Subcategory:</label>
                                <select type="text" class="form-control" name="sub_category[]" id="sub_category0">
                                    <option value="0">Select Sub Category</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-6">
                                <label for="category1">Secondary Category(optional):</label>
                                <select class="form-control cat" name="sec_category[]" id="category1">
                                    <option value="0">Select Secondary Category</option>
                                </select>

                            </div>
                            <div class="form-group col-lg-6">
                                <label for="sub_category1"> Secondary Subcategory(optional):</label>
                                <select class="form-control" name="sec_sub_category[]" id="sub_category1">
                                    <option value="0">Select Sub Category</option>
                                </select>
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="title">Proposal Title (<span id="titlecharleft">25</span> words left):</label>
                                <input type="text" class="form-control" name="title" value="{{ old('title') }}" id="title">
                            </div>
                            <div class="form-group col-lg-12">
                                <label for="abstract">Abstract (<span id="abstractcharleft">250</span> words left):</label>
                                <textarea rows="4" class="form-control" name="abstract" id="abstract">{{ old('abstract') }}</textarea>
                            </div>
                            <hr>

                            <div class="col-lg-12">
                                <h4>Project Institutions (if any):</h4>
                                <i class="fa fa-plus pull-right add text-blue"
                                   style="cursor: pointer"></i>
                                <div class="row institution">
                                    <div class="form-group col-lg-4">
                                        <label for="inst"></label>
                                        <select id="inst" class="form-control" name="institution[]">
                                            <option value="0">Select institution</option>
                                            @if(!empty($institutions))
                                                @foreach($institutions as $val=>$item)
                                                    <option class="text-capitalize" value="{{$val}}">{{$item}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <input type="text" id="insttext" class="form-control" name="institutionname[]">
                                        </input>
                                    </div>

                                    {{--<div class="form-group col-lg-2">--}}
                                    {{--<label for="i_type">Institution type:</label>--}}
                                    {{--<select id="i_type" class="form-control" name="i_type[]">--}}
                                    {{--<option value="0">Select type</option>--}}
                                    {{--<option value="affiliation">Affiliation</option>--}}
                                    {{--<option value="employment">Employment</option>--}}
                                    {{--</select>--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group col-lg-2">--}}
                                    {{--<label for="start">Start:</label>--}}
                                    {{--<input type="date" class="form-control" name="start[]" id="start">--}}
                                    {{--</div>--}}
                                    {{--<div class="form-group col-lg-2">--}}
                                    {{--<label for="end">End:</label>--}}
                                    {{--<input type="date" class="form-control" name="end[]" id="end">--}}
                                    {{--</div>--}}
                                </div>
                            </div>
                            <hr>

                        <!--- Modal --->
                            <aside class="right-side">
                                <section class="content">
                                    <div class="box-primary">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h4>Persons associated with project</h4>
                                            </div>
                                        </div>
                                        <!-- Modal form-->
                                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
                                             aria-labelledby="myModalLabel" aria-hidden="true">
                                            <div class="modal-dialog ">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h2> Add persons to project</h2>
                                                        <button type="button" class="close" data-dismiss="modal"><span
                                                                    aria-hidden="true">&times;</span><span
                                                                    class="sr-only">Close</span></button>
                                                        <h4 class="modal-title" id="myModalLabel"></h4>
                                                    </div>
                                                    <div class="modal-body" id="modal-bodyku">
                                                        <div class="form-group col-lg-12 sup">
                                                            <label class="text-red"> <i class="fas fa-question-circle"></i> For this
                                                                competition please create or choose
                                                                persons with support type *</label>
                                                        </div>
                                                            <div class="row">
                                                                <div class="form-group col-lg-1">
                                                                </div>
                                                                <div class="form-group col-lg-4">
                                                                    <b>Name</b>
                                                                </div>
                                                                <div class="form-group col-lg-4">
                                                                    <b>Role</b>
                                                                </div>
                                                                <div class="form-group col-lg-3">
                                                                    <b>Nationality</b>
                                                                </div>
                                                            </div>
                                                        @foreach($persons as $i=>$person)
                                                            <div class="row">
                                                                <div class="form-group col-lg-1">
                                                                    <label for="choose_person_name{{$i}}"
                                                                           class="label">
                                                                        <input type="checkbox" name="choose_person[]"
                                                                               id="choose_person_id{{$i}}"
                                                                               value="{{$person['id']}}"/>
                                                                        <input type="hidden" name="choose_person_name[]"
                                                                               value="{{$person['first_name']." ". $person['last_name']}}"
                                                                               id="person_id{{$i}}"/>
                                                                        <input type="hidden" name="choose_person_t[]"
                                                                               value="{{$person['type']}}"
                                                                               id="person_id{{$i}}"/>
                                                                    </label>
                                                                </div>
                                                                <div class="form-group col-lg-4">
                                                                    {{$person['first_name']." ".$person['last_name']}}
                                                                </div>
                                                                <div class="form-group col-lg-4">
                                                                    <select class="form-control form-check-inline type tt" name="choose_person_ttype[]"
                                                                            id="choose_person_type{{$i}}">
                                                                        <option value = 'None'>Choose role for person</option>
                                                                    @if($person['type'] == 'contributor')
                                                                            <option value = 'PI'>PI</option>
                                                                            <option value = 'collaborator'>Collaborator</option>
                                                                    @else
                                                                            <option value = 'director'>Director</option>
                                                                            <option value = 'support'>Support</option>
                                                                    @endif
                                                                    </select>
                                                                </div>
                                                                <div class="form-group col-lg-3">
                                                                    <input type="text"
                                                                           class="form-control form-check-inline type al"
                                                                           name="choose_person_nationality[]"
                                                                           id="choose_person_nationality{{$i}}"
                                                                           value="{{$person['nationality']}}" disabled
                                                                           style="border:none;background-color: #fff;">
                                                                </div>
                                                            </div>
                                                        @endforeach

                                                    </div>
                                                    <div class="modal-footer" id="modal-footerq">
                                                        <button type="button" class="btn btn-primary"
                                                                data-dismiss="modal" id="choose">Choose
                                                        </button>
                                                    </div>
                                                    <input type="hidden" class="form-control form-check-inline aaa"
                                                           name="hidden_choose_person[]" value="" id="aaa">

                                                </div>
                                            </div>
                                        </div>
                                        <!-- end of modal ------------------------------>
                                    </div>
                                    <div class="form-group row col-lg-12" id="prop_person"></div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <button type="button" class="btn btn-primary chooseperson"
                                                        onClick="open_container();" disabled>Add a person to the project
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </section><!-- /.content -->
                            </aside><!-- /.right-side -->
                            <!--- Modal --->


                            <!--Additional Charge Budget-->
                            <!--<div class="col-lg-12 budgetitem">-->
                                <!--<h4>Budget Items</h4>-->
                                {{--<i class="fa fa-plus pull-right add text-blue"--}}
                                {{--style="cursor: pointer"></i>--}}

                                <!--<div class="row institution">-->
                                    <!--<div class="form-group col-lg-3" id="budget_categories">-->
                                        <!--<label for="inst">Budget Categories:</label>-->
                                        {{--<select class="form-control" name="budget_item_categories[]"--}}
                                        {{--id="budget_categories">--}}
                                        {{--<option value="00">Select Budget Categories:</option>--}}
                                        {{--</select>--}}
                                    <!--</div>-->
                                    <!--<div class="form-group col-lg-5" id="budget_categories_description">-->
                                        <!--<label for="title">Budget Categories Description:</label>-->
                                    <!--</div>-->
                            <!--        <div class="form-group col-lg-4" id="amount">-->
                            <!--            <label for="start">Amount:(Choose Between)</label>-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--</div>-->

                            <!--Additional Charge Budget-->
                            <!--<div class="col-lg-12 additional">-->
                            <!--    <h4>Additional Budget</h4>-->
                            <!--    <div class="row">-->
                            <!--        <div class="form-group col-lg-3" id="additional_charge_name">-->
                            <!--            <label for="inst">Additional Charge Name:</label>-->
                            <!--        </div>-->
                            <!--        <div class="form-group col-lg-3" id="additional_charge">-->
                            <!--            <label for="title">Additional Charge:</label>-->
                            <!--        </div>-->
                            <!--        <div class="form-group col-lg-3" id="additional_persentage_name">-->
                            <!--            <label for="start">Additional Persentage Name</label>-->
                            <!--        </div>-->
                            <!--        <div class="form-group col-lg-3" id="additional_persentage">-->
                            <!--            <label for="start">Additional Persentage</label>-->
                            <!--        </div>-->
                            <!--    </div>-->
                            <!--</div>-->

                            {{--<div class="form-group col-lg-8">--}}
                            {{--<div class="col-xs-8">--}}
                            {{--<div class="box">--}}
                            {{--<div class="box-header">--}}
                            {{--<h3 class="box-title">Score Type</h3>--}}
                            {{--<!-- /.box-header -->--}}
                            {{--<div class="box-body table-responsive no-padding">--}}
                            {{--<table class="table table-hover" id="score_type" >--}}
                            {{--<tbody><tr>--}}
                            {{--<th>Category</th>--}}
                            {{--<th>Score</th>--}}
                            {{--</tr>--}}
                            {{--</tbody></table>--}}
                            {{--</div>--}}
                            {{--<!-- /.box-body -->--}}
                            {{--</div>--}}
                            {{--<!-- /.box -->--}}
                            {{--</div>--}}

                            {{--</div>--}}
                            {{--</div>--}}

                            {{--<div class="form-group col-lg-6">--}}
                            {{--<label for="ref1">Referee #1 comments:</label>--}}
                            {{--<textarea rows="4" class="form-control" id="ref1"></textarea>--}}
                            {{--</div>--}}
                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <button type="reset" class="btn btn-secondary">Cancel</button>
                            </div>
                            </div>


                        </form>

                        <!--<div class="form-group col-lg-12">-->
                        <!--    <label>Request to admin for adding new Institution</label>-->
                        <!--    <a href="{{action('Applicant\ResearchBoardController@index','admin')}}"-->
                        <!--       class="btn btn-primary">Request To admin</a>-->
                        <!--</div>-->

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
