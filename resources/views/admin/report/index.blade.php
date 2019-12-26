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
                    <div class="card-body card_body" style="overflow:auto;">
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
                                <th>Score</th>
                                <th>State</th>
                                <th>Action</th>
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

    <!-- Modal form-->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog"
         aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h4></h4>
                    <button type="button" class="close" data-dismiss="modal">
                        <span aria-hidden="true">&times;</span>
                        <span class="sr-only">Close</span></button>
                    <h5 class="modal-title" id="myModalLabel"></h5>
                </div>
                <div class="modal-body" id="modal-bodyku">
                    <div>
                        <span id="content">Report content</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- end of modal ------------------------------>


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
                        {"data": "overall_score"},
                        {"data": "state"},
                        {
                            "render": function (data, type, full, meta) {
                                var ID = full.id;
                                return '<button class="btn btn-link" onclick="open_container(' + meta.row + ')">' +
                                    '<i class="fa fa-eye"></i></button>';
                            }
                        }
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
                    "deferRender": true,
                    "scrollCollapse": false,
                    "scroller": true,
                    "colReorder": true,
                    // "fixedColumns":   { "leftColumns": 1 },
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
                reloadtable('admin/listreports');

                // t.on('order.dt search.dt', function () {
                //     t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) { cell.innerHTML = i + 1; });
                // }).draw();
            }

            $('#competition').change(function() {
                reloadtable('admin/listreports');
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
                        url: '/admin/deleteReport',
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
                                reloadtable('admin/listreports');
                        },
                        error: function(data) {
                            console.log('msg' + data);
                        }
                    });
                }
            }
        }

        function open_container(i) {
            var size = 'small',
                content = ''+i,
                title = '',
                footer = '';
            jQuery.noConflict();
            setModalBox(title, content, footer, size);
            jQuery('#myModal').modal('show');
        }

        function setModalBox(title, content, footer, $size) {
            var row = parseInt(content);
            var t = $('#datatable').DataTable();
            var data = t.rows().data();
            var tabledata = format(data[row]);
            $('#myModal').find('.modal-header h4').text('Report for ' + data[row].tag);
            $('#myModal').find('#content').replaceWith(tabledata);
        }

        function format(d) {
            var table = '<table style="width:100%;"><thead><tr><th width="" style="text-align:left;color:#999;">Score category</th><th width="" style="text-align:center;color:#999;">Score</th></thead>';
            table += '<tbody>';
            if (d.scores.length) {
                var s = JSON.parse(d.scores);
                for (var i in s) {
                    if (s.hasOwnProperty(i)) {
                        table += '<tr>';
                        table += '<td style="text-align:left;color:#048;"><b>' + s[i].name + '</b></td><td style="text-align:center">' + s[i].value + '</td>';
                        table += '</tr>';
                    }
                }
                table += '<tr><td colspan="2" style="text-align:left;white-space:pre-wrap;padding:20px;"><b>Public comments</b><br/>' + d.public + '</td></tr>';
                table += '<tr><td colspan="2" style="text-align:left;white-space:pre-wrap;padding:20px;"><b>Private comments</b><br/>' + d.private + '</td></tr>';
                flag = false;
            }

            table += '</tbody></table>';

            return table;
        }

    </script>
@endsection
