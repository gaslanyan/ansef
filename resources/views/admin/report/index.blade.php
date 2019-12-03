@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">List of referee reports for competition :
                        <select name="competition" id="competition" style="width:100px;font-size:24px;">
                            @foreach($competitions as $c)
                                <option value="{{$c['id']}}" {{$c['id']==$cid ? 'selected' : ''}}>{{$c['title']}}</option>
                            @endforeach
                                <option value="-1" {{-1==$cid ? 'selected' : ''}}>All</option>
                        </select>
                    </div>
                    <?php  $d = \Illuminate\Support\Facades\Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . 'object.txt'?>
                    <div class="card-body card_body">
                        @include('partials.status_bar')

                        <div class="col-12" style="margin-bottom:20px;padding-bottom:35px;">
                                <button type="button"
                                        title="delete" onclick="deletereports();"
                                        class="display float-lg-right btn-primary px-2 myButton">
                                        <i class="fa fa-trash-alt" ></i>
                                    Delete
                                </button>
                        </div>
                        <table class="table table-responsive-md table-sm table-bordered display compact" id="datatable"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th width="100px">ID</th>
                                <th>Proposal</th>
                                <th>Referee</th>
                                <th>Admin</th>
                                <th>Due</th>
                                <th>View</th>
                                <th>Score</th>
                                <th>State</th>
                            </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            // var url = "<?= action("Admin\ReportController@destroy", ' + "id" + ')?>";
            if ( $.fn.dataTable.isDataTable( '#datatable' ) ) {
                t = $('#datatable').DataTable();
            }
            else {
                t = $('#datatable').DataTable( {
                    "pagingType": "full_numbers",
                    "columns": [
                        {"data": "tag"},
                        {"data": "title"},
                        {"data": "referee"},
                        {"data": "admin"},
                        {"data": "due_date"},
                        {
                            "className": 'details-control',
                            "orderable": false,
                            "data": null,
                            "defaultContent": ''
                        },
                        {"data": "overall_score"},
                        {"data": "state"}
                    ],
                    "columnDefs": [
                        {
                            "targets": [0],
                            "searchable": false,
                            "orderable": false,
                            "visible": true
                        }
                    ],
                    "select": true,
                    "scrollX": true,
                    "scrollY": 450,
                    "deferRender": false,
                    "scrollCollapse": false,
                    "scroller": false,
                    "colReorder": true,
                    "fixedColumns":   { "leftColumns": 1 },
                    "processing": true,
                    "language": {
                        "loadingRecords": '&nbsp;',
                        "processing": 'Loading...'
                    },
                    "dom": 'Bfrtip',
                    "buttons": [
                        'selectAll', 'selectNone', 'copy', 'csv', 'excel', 'pdf', 'print'
                    ]
                });
                reloadtable('ajax_report');

                // t.on('order.dt search.dt', function () {
                //     t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) { cell.innerHTML = i + 1; });
                // }).draw();
            }

            $('#competition').change(function() {
                reloadtable('ajax_report');
            });

            function format(d) {
                var table = '<table style="table-layout:fixed;float:right;"><thead><tr><th width="150px" style="text-align:center">Score category</th><th width="50px" style="text-align:center">Score</th><th width="200px">Public comments</th><th width="200px">Private comments</th></tr></thead>';
                table += '<tbody>';
                if (d.scores.length) {
                    var s = JSON.parse(d.scores);
                    var flag = true;
                    for (var i in s) {
                        if (s.hasOwnProperty(i)) {
                            table += '<tr>';
                            table += '<td style="text-align:center">' + s[i].name + '</td><td style="text-align:center">' + s[i].value + '</td>';
                            if(flag) {
                                table += '<td width="200px" rowspan="7" style="text-align:left;white-space:pre-wrap;">' + d.public + '</td><td width="200px" rowspan="7" style="text-align:left;white-space:pre-wrap;">' + d.private + '</td>';
                                flag = false;
                            }
                            table += '</tr>';
                        }
                    }
                }

                table += '</tbody></table>';

                return table;
            }

            $('#datatable tbody').on('click', 'td.details-control',
                function () {
                    var tr = $(this).closest('tr');
                    var row = t.row(tr);

                    if (row.child.isShown()) {
                        row.child.hide();
                        tr.removeClass('shown');
                    } else {
                        console.log(row.data());
                        row.child(format(
                            row.data())).show();
                        tr.addClass('shown');
                    }
                });

        });

        function deletereports() {
            var t = $('#datatable').DataTable();
            var data = t.rows({'selected': true}).data();
            if (data.length > 0) {
                if (confirm('Are you sure you want to delete ' + data.length + ' reports?')) {
                    var checkedIDss = [];
                    for(var i=0; i<data.length; i++) {
                        checkedIDss.push(data[i].id);
                    }
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url: '/deleteReport',
                        type: 'POST',
                        data: {
                            token: CSRF_TOKEN,
                            id: checkedIDss
                        },
                        dataType: 'JSON',
                        success: function(data) {
                            console.log(data);
                            if (data.success === -1)
                                console.log('msg' + data);
                            else
                                reloadtable('ajax_report');
                        },
                        error: function(data) {
                            console.log('msg' + data);
                        }
                    });
                }
            }
        }

    </script>
@endsection
