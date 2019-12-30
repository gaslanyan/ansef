@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card" >
                    <div class="card-header">List of Past Proposals
                    </div>
                    @include('partials.status_bar')

                    <div class="card-body card_body" style="overflow:auto;">
                        @if(!empty($awards) && count($awards) > 0)
                            <h4>Awarded proposals</h4>
                            <table class="table table-responsive-md table-sm table-bordered display compact" id="example"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th hidden>ID</th>
                                    <th>ID</th>
                                    <th>Proposal Title</th>
                                    <th>Proposal State</th>
                                    <th colspan="2">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($awards as $ap)
                                    <tr>
                                        <td hidden></td>
                                        <td>{{getProposalTag($ap['id'])}}</td>
                                        <td data-order="{{$ap['title']}}" data-search="{{$ap['title']}}">
                                            {{truncate($ap['title'],55)}}
                                        </td>
                                        <td data-order="{{$ap['state']}}" data-search="{{$ap['state']}}">
                                            @if($ap->state == 'awarded')
                                                Awarded - first report due on
                                                {{date('Y-m-d', strtotime("+" . (int)($ap->competition->duration/2) . " month", strtotime($ap->competition->project_start_date)))}}
                                            @elseif($ap->state == 'approved 1')
                                                Awarded - first report approved; second report due on
                                                {{date('Y-m-d', strtotime("+" . (int)($ap->competition->duration) . " month", strtotime($ap->competition->project_start_date)))}}
                                            @elseif($ap->state == 'approved 2' || $ap->state == 'complete')
                                                Awarded - final report approved
                                            @else
                                            @endif
                                        </td>
                                        <td>
                                            <input type="hidden" class="id" value="{{$ap['id']}}">
                                            @if($ap->competition->first_report >= date('Y-m-d'))
                                            <a href="{{action('Applicant\FileUploadController@reportfile', $ap['id'])}}" title="View">
                                                @if($firstreports[$ap['id']])
                                                <span class="fas fa-file-upload myButton">&nbsp;First Report</span>
                                                @else
                                                <span class="fas fa-file-upload myButton" style="color:#e00;">&nbsp;First Report</span>
                                                @endif
                                            </a>
                                            @endif
                                            @if($ap->competition->second_report >= date('Y-m-d') && $ap->competition->first_report < date('Y-m-d'))
                                            <a href="{{action('Applicant\FileUploadController@reportfile', $ap['id'])}}" title="View">
                                                @if($secondreports[$ap['id']])
                                                <span class="fas fa-file-upload myButton">&nbsp;Second Report</span>
                                                @else
                                                <span class="fas fa-file-upload myButton" style="color:#e00;">&nbsp;Second Report</span>
                                                @endif
                                            </a>
                                            @endif
                                            <a href="{{action('Applicant\ProposalController@show', $ap['id'])}}" title="View">
                                                <span class="fa fa-eye myButton">&nbsp;View</span>
                                            </a>
                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <br/><br/>
                        @endif
                        @if(!empty($pastproposals) && count($pastproposals)>0)
                        <h4>Past proposals</h4>
                            <table class="table table-responsive-md table-sm table-bordered display compact" id="example"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th hidden>ID</th>
                                    <th>ID</th>
                                    <th>Proposal Title</th>
                                    <th>Proposal State</th>
                                    <th colspan="2">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($pastproposals as $ap)
                                    <tr>
                                        <td hidden></td>
                                        <td>{{getProposalTag($ap['id'])}}</td>
                                        <td data-order="{{$ap['title']}}" data-search="{{$ap['title']}}">
                                            {{truncate($ap['title'],55)}}
                                        </td>
                                        <td data-order="{{$ap['state']}}" data-search="{{$ap['state']}}">
                                            @if($ap->state == 'in-progress')
                                                Initial processing
                                            @elseif($ap->state == 'submitted')
                                                Preparing for review
                                            @elseif($ap->state == 'in-review')
                                                With referees
                                            @elseif($ap->state == 'complete')
                                                Reviews complete
                                            @elseif($ap->state == 'unsuccessfull')
                                                Unsuccessful
                                            @elseif($ap->state == 'disqualified')
                                                Disqualified
                                            @elseif($ap->state == 'finalist' && $ap->competition->results_date < date('Y-m-d'))
                                                Finalist
                                            @elseif($ap->state == 'finalist' && $ap->competition->results_date >= date('Y-m-d'))
                                                Reviews complete
                                            @else
                                            @endif
                                        </td>
                                        <td>
                                            <input type="hidden" class="id" value="{{$ap['id']}}">
                                            <a href="{{action('Applicant\ProposalController@show', $ap['id'])}}" title="View">
                                                <span class="fa fa-eye myButton">View</span>
                                            </a>
                                        </td>

                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>No past proposals</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
        <script>
            $(document).ready(function () {
                var t = $('#example').DataTable({
                    //"pagingType": "full_numbers",
                    "paging": false,
                    "columnDefs": [
                        {
                            "targets": [3],
                            "searchable": false
                        }, {
                            "targets": [3],
                            "searchable": false
                        }
                    ]
                });
                t.on('order.dt search.dt', function () {
                    t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
                }).draw();
            });

        </script>
@endsection
