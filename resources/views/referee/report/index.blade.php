@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header text-capitalize">List of {{($state=='in-progress' ? "Current" : $state). " "}}reports assigned to you</div>
                    <div class="card-body card_body" style="overflow:auto;">
                        @include('partials.status_bar')

                        @if(!empty($reports) && count($reports)>0)
                        @if($state == 'in-progress')
                        <table class="table table-responsive-md table-sm table-bordered display" id="example">
                            <thead>
                            <tr>
                                <th hidden></th>
                                <th width="150px">ID</th>
                                <th>Proposal title</th>
                                <th>State</th>
                                <th>Due date</th>
                                <th class="action long">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($reports as $report)
                                    <tr>
                                        <td hidden></td>
                                        <td>{{getProposalTag($report->proposal->id)}}</td>
                                        <td>{{truncate($report->proposal->title,40)}}</td>
                                        <td>{{$report->state}}</td>
                                        <td>{{$report->due_date}}</td>
                                        <td>
                                            <span class="btn-primary myButton"><a href="{{action('Referee\ReportController@edit', $report->id)}}"
                                               title="Edit">
                                               Review and score
                                            </a></span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @elseif($state == 'complete')
                        <table class="table table-responsive-md table-sm table-bordered display" id="example">
                            <thead>
                            <tr>
                                <th hidden></th>
                                <th width="150px">ID</th>
                                <th>Proposal title</th>
                                <th width="100px">Overall score</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($reports as $report)
                                    <tr>
                                        <td hidden></td>
                                        <td>{{getProposalTag($report->proposal->id)}}</td>
                                        <td>{{truncate($report->proposal->title,100)}}</td>
                                        <td>{{overallScore($report->id)}}%</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @else
                        @endif
                        @else
                        <h4>There are no reports</h4>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // var groupColumn = 1;
            // var t = $('#example').DataTable({
            //     "columnDefs": [
            //         {"visible": false, "targets": groupColumn}
            //     ],
            //     "order": [[groupColumn, 'asc']],
            //     // "displayLength": 25,
            //     "drawCallback": function (settings) {
            //         var api = this.api();
            //         var rows = api.rows({page: 'current'}).nodes();
            //         var last = null;

            //         api.column(groupColumn, {page: 'current'}).data().each(function (group, i) {
            //             if (last !== group) {
            //                 $(rows).eq(i).before(
            //                     '<tr class="group"><td colspan="6">' + group + '</td></tr>'
            //                 );

            //                 last = group;
            //             }
            //         });
            //     }
            // });
            // $('#example tbody').on('click', 'tr.group', function () {
            //     var currentOrder = t.order()[0];
            //     if (currentOrder[0] === groupColumn && currentOrder[1] === 'asc') {
            //         t.order([groupColumn, 'desc']).draw();
            //     }
            //     else {
            //         t.order([groupColumn, 'asc']).draw();
            //     }
            // });
            t.on('order.dt search.dt', function () {
                t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        });


    </script>

@endsection
