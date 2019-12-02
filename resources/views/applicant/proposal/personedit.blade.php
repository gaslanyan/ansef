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
                        <p><b>Add New Participant</b></p>
                        <form method="post" action="{{action('Applicant\ProposalController@addperson', $id) }}">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="form-group col-lg-4">

                                        <select id="bcc" class="form-control budgetcategory" name="person_prop">
                                            <option value="0">Choose a Person:</option>
                                            @if(!empty($persons))
                                                @foreach($persons as $item)
                                                    <option class="text-capitalize" value = "{{$item['id']}}">{{$item['first_name'] . " ".$item['last_name']." ".$item['type']}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                </div>
                                    <div class="form-group col-lg-4">
                                        <?php $enum = getEnumValues('person_type', 'subtype');?>
                                        <select id="bcc" class="form-control budgetcategory" name="subtype">

                                            <option value="0">Choose a type:</option>
                                            @if(!empty($enum))
                                                @foreach($enum as $val=>$item)
                                            <option class="text-capitalize" value="{{$item}}">{{$item}}</option>
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

                        {{-- @if(!empty($email_list)) --}}
                            <form method="get" action="{{action('Applicant\ProposalController@savepersons', $id) }}">
                                <div class="form-group">
                                    @csrf
                                    <input name="_method" type="hidden" value="PATCH">

                                    <label><b>Project members</b></label><br/><br/>
                                    @if(!empty($added_persons))
                                      @foreach($added_persons as $added_p)
                                        <div class="form-group col-lg-12 emails">
                                            <div class="row">
                                                <div class="form-group col-lg-4">

                                                    <select id="bcc" class="form-control budgetcategory" name="ed_person_prop">
                                                        <option class="text-capitalize" value = "{{$added_p->id}}">{{$added_p->first_name . " ".$added_p->last_name." ".$added_p->type}}</option>
                                                     </select>
                                                </div>
                                                <div class="form-group col-lg-4">
                                                    <select id="bcc" class="form-control budgetcategory" name="ed_subtype">
                                                        @if(!empty($enum))
                                                            @foreach($enum as $val=>$item)
                                                                <option class="text-capitalize" value="{{$item}}" <?php if($added_p->subtype==$item) echo "selected"; ?>>{{$item}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                      @endforeach
                                    @endif
                                </div>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </form>
                        {{-- @endif --}}
                    </div>
                                    </div>
            </div>
        </div>
    </div>
@endsection
