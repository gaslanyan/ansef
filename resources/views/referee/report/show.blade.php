@extends('layouts.master')
@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">View Proposal
                        <a href = "{{action('Referee\ReportController@edit', $id)}}" class="btn btn-secondary float-right">Go back</a>
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
                            'admin' => true
                        ])

                        @include('partials.proposaldetails',[
                            // 'persons' => $proposal,
                            // 'scores' => $cat_parent,
                            // 'reports' => $cat_sub
                        ])


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

