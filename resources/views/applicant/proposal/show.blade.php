@extends('layouts.master')
@section('content')
<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">View Proposal {{getProposalTag($pid)}}
                    <a href="{{URL::previous()}}" class="display float-lg-right btn-box-tool">Go Back</a>
                </div>

                <div class="card-body" style="overflow:auto;">
                    @include('partials.status_bar')

                    @if($proposal->competition->results_date < date('Y-m-d'))
                        @include('partials.refereereports',[ 'reports'=> $reports,
                        'private' => false
                        ])
                        @endif

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
                        'recommendations' => null,
                        'showdownloads' => true
                        ])


                        <div class="box-primary">
                            <div class="col-lg-12" style="margin-top:30px;">
                                <a href="{{action('Applicant\ProposalController@generatePDF', $proposal->id)}}"
                                    class="btn btn-primary">Download PDF</a>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
