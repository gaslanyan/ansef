@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-12">
                 <div class="card" >
                    <div class="card-header">Edit Proposal
                        {{-- <a href="{{action('Applicant\ProposalController@generatePDF',$proposal->id)}}"
                           title="Download"
                           class="add_honors"><i class="fa fa-download"></i>
                        </a> --}}

                    </div>

                    <div class="card-body" style="overflow:auto;">
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
                                        <select id="institution" class="form-control" name="institution[]">
                                            <option value="0">Select institution</option>
                                            @if(!empty($institutions))
                                                @foreach($institutions as $val=>$item)
                                                    <option class="text-capitalize" value="{{$val}}"  <?php if($val == $ins['id']) echo 'selected'; ?> >{{$item}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    <input type="text" id="institutionname" class="form-control" name="institutionname" value="{{$ins['name']}}" placeholder="If your institution is not in the list, type instead the name here">
                                    </div>
                            @endif

                            <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary">Save</button>
                                <a href="{{ action('Applicant\ProposalController@activeProposal') }}" class="btn btn-secondary"> Cancel</a>
                            </div>
                        </form>
                        <br/><br/><br/>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script>
        function count($field, $span){
        var txtVal = jQuery($field).val();
        var words = txtVal.trim().replace(/\s+/gi, ' ').split(' ').length;
        return words;
        }

        jQuery('#title').on('keyup propertychange paste', function(){
	        jQuery('#titlecharleft').html((25-count('#title'))+'');
        });

        jQuery('#abstract').on('keyup propertychange paste', function(){
	        jQuery('#abstractcharleft').html((250-count('#abstract'))+'');
        });

        $(document).on("change", '#institution', function() {
            if($('#institution').val() != 0)
                $('#institutionname').val('');
        });

        $(document).ready(function () {
	        jQuery('#titlecharleft').html((25-count('#title'))+'');
	        jQuery('#abstractcharleft').html((250-count('#abstract'))+'');
        });
    </script>
@endsection

