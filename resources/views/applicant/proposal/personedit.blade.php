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

                                        <select id="bcc" class="form-control budgetcategory" name="person_prop">
                                            <option value="0">Choose a Person</option>
                                            @if(!empty($persons))
                                                @foreach($persons as $item)
                                                <option class="text-capitalize" {{old('person_prop')==$item['id'] ? 'selected' : ''}} value = "{{$item['id']}}">{{$item['first_name'] . " ".$item['last_name']." (".$item['type'].")"}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                </div>
                                    <div class="form-group col-lg-4">
                                        <select id="bcc" class="form-control budgetcategory" name="subtype">

                                            <option value="0">Choose a role</option>
                                            @if(!empty($enum))
                                                @foreach($enum as $val=>$item)
                                        <option class="text-capitalize" {{old('subtype')==$item['role'] ? 'selected' : ''}} value="{{$item['role']}}">{{$item['role']=="supportletter" ? "Recommendation letter" : ucfirst($item['role'])}} (for {{$item['type']}} only)</option>
                                                @endforeach
                                            @endif
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
                                                    <select id="person_list" class="form-control" name="person_list[]">
                                                        <option class="text-capitalize" value = "{{$added_p['person_id']}}">{{$persons[$added_p['person_id']]['first_name'] . " ".$persons[$added_p['person_id']]['last_name']." (".$persons[$added_p['person_id']]['type'].")"}}</option>
                                                     </select>
                                                </div>
                                                <div class="form-group col-lg-4">
                                                    <select id="role" class="form-control" name="role_list[]">
                                                        @if(!empty($enum))
                                                            @foreach($enum as $val=>$item)
                                                            <option class="text-capitalize" {{old('subtype')==$item['role'] ? 'selected' : ''}} value="{{$item['role']}}" <?php if($added_p['subtype']==$item['role']) echo "selected"; ?>>{{$item['role']=="supportletter" ? "Recommendation letter" : ucfirst($item['role'])}} (for {{$item['type']}} only)</option>
                                                            @endforeach
                                                        @endif
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
@endsection
