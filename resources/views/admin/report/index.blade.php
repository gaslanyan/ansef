@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card">
                    <div class="card-header">List of referee reports
                    </div>
                    <?php  $d = \Illuminate\Support\Facades\Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix() . 'object.txt'?>
                    <div class="card-body card_body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                            </div><br/>
                        @endif
                        @if (\Session::has('error'))
                            <div class="alert alert-danger">
                                <p>@php echo html_entity_decode(\Session::get('error'), ENT_HTML5) @endphp</p>
                            </div>
                        @endif
                        @if (Session::has('delete'))
                            <div class="alert alert-info">
                                <p>{{ Session::get('delete') }}</p>
                            </div>
                        @endif
                        <div class="btn_add col-md-12">
                            <button type="button" disabled title="delete" id="deleteReport"
                                    class="btn-link btn delete_cats offset-lg-6 col-lg-2 col-md-3"><i
                                        class="fa fa-trash-alt"></i>
                                Delete
                            </button>
                        </div>
                        <table class="table table-responsive-md table-sm table-bordered display" id="example"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th>
                                    <label for="report" class="label">
                                        <input type="checkbox" class="form-control check_all"
                                               id="report">
                                    </label>
                                </th>
                                <th>Proposal</th>
                                <th>Referee</th>
                                <th>Admin</th>
                                <th>Due</th>
                                <th>Score</th>
                                <th>Overall</th>
                                <th>State</th>
                                <th>Comments</th>
                                <th>Actions</th>
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
            var url = "<?= action("Admin\ReportController@destroy", ' + "id" + ')?>";
            var t = $('#example').DataTable({
                "pagingType": "full_numbers",
                "ajax": '/ajax_report',

                "columns": [
                    {"defaultContent": ""},
                    {
                        "render": function (data, type, full, meta) {
                            var ID = full.id;
                            return '<label for="report' + ID + '" class="label">' +
                                '<input type="checkbox" class="form-control checkbox" name="id[]"   value="' + ID + '"  id="report' + ID + '">' +
                                '</label>';
                        },

                    },
                    {"data": "title"},
                    {"data": "referee"},
                    {"data": "admin"},
                    {"data": "dur_date"},
                    {
                        "className": 'details-control',
                        "orderable": false,
                        "data": null,
                        "defaultContent": ''
                    },
                    {"data": "overall_scope"},
                    {"data": "state"},
                    {"data": "comment"},
                    {
                        "render": function (data, type, full, meta) {
                            var ID = full.id;
                            return '<form action= "<?= action('Admin\ReportController@destroy', '')?>" method="post"> ' +
                                '<input name="_method" type="hidden" value="DELETE">' +
                                '<input type="hidden" name="_token" value="{!! csrf_token() !!}">'+
                                '<input name="_id" type="hidden" value="' + ID + '">' +
                                '<button class="btn-link delete" type="button" data-title="report">' +
                                '<i class="fa fa-trash"></i></button></form>';
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
                "scrollX": true
            });
            t.on('order.dt search.dt', function () {
                t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();


            function format(d) {

                var table = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;">';

                if (d.scores.length) {
                    var s = JSON.parse(d.scores);
                    for (var i in s) {
                        if (s.hasOwnProperty(i)) {
                            table += '<tr>';
                            table += '<td>' + s[i].name + '</td><td>' + s[i].value + '</td>';
                            table += '</tr>';
                        }
                    }
                }

                table += '</table>';

                return table;


            }

            $('#example tbody').on('click', 'td.details-control',
                function () {
                    var tr = $(this).closest('tr');
                    var row = t.row(tr);

                    if (row.child.isShown()) {
                        // This row is already open - close it
                        row.child.hide();
                        tr.removeClass('shown');
                    } else {
                        // Open this row
                        console.log(row.data());
                        row.child(format(
                            row.data())).show();
                        tr.addClass('shown');
                    }
                });

        });


    </script>
@endsection
