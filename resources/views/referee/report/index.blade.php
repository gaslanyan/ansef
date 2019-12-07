@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header text-capitalize">List of {{($state=='in-progress' ? "Current" : $state). " "}}Reports</div>
                    <div class="card-body card_body" style="overflow:auto;">
                        @include('partials.status_bar')


                        <table class="table table-responsive-md table-sm table-bordered display" id="example">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Competition title</th>
                                <th>Proposal title</th>
                                <th>State</th>
                                <th>Dur date</th>
                                <th>Overall Scope</th>
                                <th class="action long">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($reports))
                                @foreach($reports as $report)
                                    <tr>
                                        <td></td>
                                        <td>{{$report->proposal->competition->title}}</td>
                                        <td>{{$report->proposal->title}}</td>
                                        <td>{{$report->state}}</td>
                                        <td>{{$report->due_date}}</td>
                                        <td>{{$report->overall_score}}</td>
                                        <td>
                                            <a href="{{action('Referee\ReportController@edit', $report->id)}}"
                                               class="edit_full" title="Edit"><i class="fa fa-pencil-alt"></i>
                                            </a>

{{--                                            <a href="{{action('Referee\ReportController@destroy', $report->id)}}"--}}
{{--                                               class="view" title="Remove"><i class="fa fa-trash"></i>--}}
{{--                                            </a>--}}
                                        </td>

                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            var groupColumn = 1;
            var t = $('#example').DataTable({
                "columnDefs": [
                    {"visible": false, "targets": groupColumn}
                ],
                "order": [[groupColumn, 'asc']],
                // "displayLength": 25,
                "drawCallback": function (settings) {
                    var api = this.api();
                    var rows = api.rows({page: 'current'}).nodes();
                    var last = null;

                    api.column(groupColumn, {page: 'current'}).data().each(function (group, i) {
                        if (last !== group) {
                            $(rows).eq(i).before(
                                '<tr class="group"><td colspan="6">' + group + '</td></tr>'
                            );

                            last = group;
                        }
                    });
                }
            });
            $('#example tbody').on('click', 'tr.group', function () {
                var currentOrder = t.order()[0];
                if (currentOrder[0] === groupColumn && currentOrder[1] === 'asc') {
                    t.order([groupColumn, 'desc']).draw();
                }
                else {
                    t.order([groupColumn, 'asc']).draw();
                }
            });
            t.on('order.dt search.dt', function () {
                t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        });


    </script>

@endsection
