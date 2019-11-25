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
                        @include('partials.status_bar')

                        {{-- @if(!empty($email_list)) --}}
                            <form method="post" action="{{action('Applicant\ProposalController@savepersons', $id) }}">
                                <div class="form-group">
                                    @csrf
                                    <input name="_method" type="hidden" value="PATCH">

                                    <label for="email"><b></b></label>
                                    {{-- @foreach($email_list as $el) --}}
                                        <div class="form-group col-lg-12 emails">
                                            <div class="row">
                                            </div>
                                        </div>
                                    {{-- @endforeach --}}
                                </div>
                                <button type="submit" class="btn btn-primary">Save changes</button>
                            </form>
                        {{-- @endif --}}
                    </div>
                    <hr>
                    <div class="card-body card_body">
                        <p><b>Add New Participant</b></p>
                        <form method="post" action="{{action('Applicant\ProposalController@addperson', $id) }}">
                            @csrf
                            <div class="form-group">
                                <div class="row">
                                    <div class="form-group col-lg-10 emails">
                                    </div>
                                </div>
                                <input type="hidden" class="form-control" name="participant_create_hidden"
                                       value="{{$id}}" id="participant_create_hidden">
                                <button type="submit" class="btn btn-primary">Add participant</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
