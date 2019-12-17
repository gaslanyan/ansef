@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">Create Proposal
                        <a href="{{URL::previous()}}"
                           class="display float-lg-right btn-box-tool">Go Back</a>
                    </div>
                    <div class="card-body card_body" style="overflow:auto;">
                        @include('partials.status_bar')

                        To add a proposal, you need to first add at least one person in the role of project participant.
                        This person is typically the Principal Investigator (PI) of the proposal.<br/><br/>
                        Go back to the Persons tab, and add a new person in the role of project participant.
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
