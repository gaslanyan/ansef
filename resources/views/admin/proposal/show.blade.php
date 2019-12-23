@extends('layouts.master')
@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">View Proposal
                        <a href="{{URL::previous()}}"
                           class="display float-lg-right btn-box-tool">Go Back</a>
                    </div>

                    <div class="card-body card_body" style="overflow:auto;">
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
                            'showdownloads' => true
                        ])

                        @include('partials.proposaldetails',[
                            'persons' => $persons,
                            'recommendations' => $recommendations,
                            'showdownloads' => true
                        ])

                        @include('partials.refereereports',[
                            'reports' => $reports,
                            'private' => true
                        ])
                        <div class="box-primary">
                            <div class="col-lg-12" style="margin-top:30px;">
                                <a href="{{action('Admin\ProposalController@generatePDF', $proposal->id)}}" class="btn btn-primary">Download PDF</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

