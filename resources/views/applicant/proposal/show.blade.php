@extends('layouts.master')
@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">View Proposal

                    </div>

                    <div class="card-body card_body" style="overflow:auto;">
                        @include('partials.status_bar')

                        @include('partials.proposal',[
                            'pid' => $pid,
                            'proposal' => $proposal,
                            'cat_parent' => $cat_parent,
                            'cat_sub' => $cat_sub,
                            'cat_sec_parent' => $cat_sec_parent,
                            'cat_sec_sub' => $cat_sec_sub,
                            'institution' => $institution,
                            'persons' => $persons,
                            'pi' => $pi,
                            'budget_items' => $budget_items,
                            'budget' => $budget,
                            'admin' => false
                        ])

                        <div class="box-primary">

                        <div class="col-lg-12" style="margin-top:30px;">
                            <a href="{{action('Applicant\ProposalController@generatePDF', $proposal->id)}}" class="btn btn-primary">Download</a>
                            <a href="{{action('Applicant\ProposalController@activeProposal') }}" class="btn btn-secondary">Go Back</a>
                        </div>

                            </div>




                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

