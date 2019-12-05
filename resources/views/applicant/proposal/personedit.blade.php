@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card" >
                    <div class="card-header">Update participants for {{$proposaltag}}
                        <a href="{{action('Applicant\ProposalController@activeProposal')}}"
                           class="display float-lg-right btn-box-tool">Go Back</a>
                    </div>
                    <div class="card-body card_body">
                    <div class="card-body card_body">
                        <p><b>Add New Participant</b></p>
                        <form method="post" action="{{action('Applicant\ProposalController@addperson', $id) }}">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <select id="bcc" class="form-control person" name="theperson">
                                            <option value="0" role="">Choose a Person</option>
                                            @if(!empty($persons))
                                                @foreach($persons as $item)
                                                <option class="text-capitalize" role="{{$item['type']}}" {{old('theperson')==$item['id'] ? 'selected' : ''}} value = "{{$item['id']}}">{{$item['first_name'] . " ".$item['last_name']}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <select id="sp" class="form-control participant" name="subtypeparticipant">
                                            <option value="0">Choose a role</option>
                                            @foreach($participant as $val=>$item)
                                            <option class="text-capitalize" {{old('subtype')==$item ? 'selected' : ''}} value="{{$item}}">{{$item=="supportletter" ? "Recommendation letter" : ucfirst($item)}}</option>
                                            @endforeach
                                        </select>
                                        <select id="ss" class="form-control support" name="subtypesupport">
                                            <option value="0">Choose a role</option>
                                            @foreach($support as $val=>$item)
                                            <option class="text-capitalize" {{old('subtype')==$item ? 'selected' : ''}} value="{{$item}}">{{$item=="supportletter" ? "Recommendation letter" : ucfirst($item)}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-4">
                                    <input type="hidden" class="form-control" name="participant_create_hidden"
                                        value="{{$id}}" id="participant_create_hidden">
                                    <button type="submit" class="btn btn-primary">Add participant</button>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                        @include('partials.status_bar')
<hr>

                        @if(!empty($added_persons))
                            <form method="get" action="{{action('Applicant\ProposalController@savepersons', $id) }}">
                                <div class="form-group">
                                    @csrf
                                    <input name="_method" type="hidden" value="PATCH">

                                    <label><b>Project members</b></label><br/><br/>
                                      @foreach($added_persons as $added_p)
                                        <div class="form-group col-lg-12 emails">
                                            <div class="row">
                                                <div class="form-group col-lg-4">
                                                    <select id="person_list" class="form-control person" name="person_list[]">
                                                        <option class="text-capitalize" role="{{$persons[$added_p['person_id']]['type']}}" value = "{{$added_p['person_id']}}">{{$persons[$added_p['person_id']]['first_name'] . " ".$persons[$added_p['person_id']]['last_name']}}</option>
                                                     </select>
                                                </div>
                                                <div class="form-group col-lg-4">
                                                    <select id="role1" class="form-control participant" name="subtypeparticipant[]">
                                                        @foreach($participant as $val=>$item)
                                                        <option class="text-capitalize" value="{{$item}}" <?php if($added_p['subtype']==$item) echo "selected"; ?>>{{$item=="supportletter" ? "Recommendation letter" : ucfirst($item)}}</option>
                                                        @endforeach
                                                    </select>
                                                    <select id="role2" class="form-control support" name="subtypesupport[]">
                                                        @foreach($support as $val=>$item)
                                                        <option class="text-capitalize" value="{{$item}}" <?php if($added_p['subtype']==$item) echo "selected"; ?>>{{$item=="supportletter" ? "Recommendation letter" : ucfirst($item)}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <a href="{{action('Applicant\ProposalController@removeperson', $added_p['id'])}}"
                                                   class="btn-link col-lg-2">
                                                    <i class="fa fa-trash"></i></a>
                                                    <input type="hidden" class="form-control" name="person_list_hidden[]" value="{{$added_p['id']}}" id="person">
                                            </div>
                                        </div>
                                      @endforeach
                                </div>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(window).on('load', function() {
                $('select.participant').hide();
                $('select.support').hide();
                $('select.person').each(function() {
                    var role = $(this).children("option:selected").attr('role');
                    if(role == 'participant') {
                        $(this).parent().siblings().children('select.participant').show();
                        $(this).parent().siblings().children('select.support').hide();
                    }
                    else if(role == 'support') {
                        $(this).parent().siblings().children('select.participant').hide();
                        $(this).parent().siblings().children('select.support').show();
                    }
                    else {
                        $(this).parent().siblings().children('select.participant').hide();
                        $(this).parent().siblings().children('select.support').hide();
                    }
                });
        });

        $(document).ready(function(){
                $('select.person').on('change',function(){
                    var role = $(this).children("option:selected").attr('role');
                    if(role == 'participant') {
                        $(this).parent().siblings().children('select.participant').show();
                        $(this).parent().siblings().children('select.support').hide();
                    }
                    else if(role == 'support') {
                        $(this).parent().siblings().children('select.participant').hide();
                        $(this).parent().siblings().children('select.support').show();
                    }
                    else {
                        $(this).parent().siblings().children('select.participant').hide();
                        $(this).parent().siblings().children('select.support').hide();
                    }
                });
        });
    </script>
@endsection
