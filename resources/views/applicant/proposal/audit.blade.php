@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card" >
                    <div class="card-header">Proposal {{$proposaltag}} review
                        <a href="{{action('Applicant\ProposalController@activeProposal')}}"
                           class="display float-lg-right btn-box-tool">Go Back</a>
                    </div>
                    <div class="card-body card_body">
                        <p>We scanned the content of the proposal.</p>
                        @if(count($messages) > 0)
                        The following problems were identified:<br/><br/>
                        <ul>
                        @foreach($messages as $message)
                            <li>{!! $message !!}</li>
                        @endforeach
                            @if(count($missingrecs) > 0) 
                            <li>
                                We have not yet received recommendation letters from:
                                @foreach($missingrecs as $missingrec)
                                <ul>
                                    <li>{{$missingrec['name']}}</li>
                                </ul>
                                @endforeach
                            </li>
                            <a href="" class="myButton">Click here</a> to send them a reminder to submit their letters support.
                            @endif
                        </ul>
                        The deadline to fix all issues is {{$competition->submission_end_date}}. After that date,
                        the proposal will be automatically disqualified if the issues have not been resolved.
                        @else
                        No problems were found with the proposal.
                        @if(count($warnings))

                        You might however want to review the following warnings:
                        <ul>
                        @foreach($warnings as $warning)
                            <li>{!! $warning !!}</li>
                        @endforeach
                        </ul>
                        @endif
                        <p>
                        The proposal is complete and will be reviewed after {{$competition->submission_end_date}}.
                        </p>
                        @if(count($submittedrecs) > 0) 
                            <li>
                                We have already received recommendation letters from:
                                @foreach($submittedrecs as $submittedrec)
                                <ul>
                                    <li>{{$submittedrec['name']}}</li>
                                </ul>
                                @endforeach
                            </li>
                        @endif                        
                        <p>
                        No further action is needed on your part. You may however continue editing the proposal until that date. If you make changes,
                        make sure you run this check again to assure the proposal is still complete. 
                        </p>
                        <p>
                        The results of the competition will be announced within 4 months of {{$competition->submission_end_date}} on the ANSEF website; and
                        the PI will be notified by email as well. If the
                        proposal is successful, the project should start on {{$competition->submission_start_date}}
                        with a duration of {{$competition->duration}} months.
                        </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
