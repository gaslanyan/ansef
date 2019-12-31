@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                 <div class="card" >
                    <div class="card-header">List of awards for competition :
                        <select name="competition" id="competition" style="width:100px;font-size:24px;">
                            @foreach($competitions as $c)
                                <option value="{{$c['id']}}" {{$c['id']==$cid ? 'selected' : ''}}>{{$c['title']}}</option>
                            @endforeach
                                <option value="-1" {{-1==$cid ? 'selected' : ''}}>All</option>
                        </select>
                    </div>
                    <div class="card-body ajaxdiv" style="overflow:auto;">
                        @include('partials.status_bar')

                        <table class="table table-bordered display compact" id="datatable">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Proposal Title</th>
                                <th>Proposal PI</th>
                                <th>State</th>
                                <th>Score</th>
                                <th class="action long">Actions</th>
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

            var t = $('#datatable').DataTable({
                "pagingType": "full_numbers",
                    "columns": [
                        {"data": "tag"},
                        {"data": "title"},
                        {"data": "pi"},
                        {"data": "state"},
                        {"data": "score"},
                        {
                            "render": function (data, type, full, meta) {
                                var ID = full.id;
                                var viewbutton = '<form action= "<?= action('Viewer\ProposalController@display', '')?>" method="post"> ' +
                                    '<input name="_method" type="hidden" value="POST">' +
                                    '<input type="hidden" name="_token" value="{!! csrf_token() !!}">'+
                                    '<input name="id" type="hidden" value="' + ID + '">' +
                                    '<button class="btn btn-link myButton" type="submit">' +
                                    '<i class="fa fa-eye"></i></button></form>';
                                var firstreportbutton = '';
                                if(full['first'])
                                    firstreportbutton = '<form action= "<?= action('Viewer\ProposalController@downloadfirstreport', '')?>" method="post"> ' +
                                    '<input name="_method" type="hidden" value="POST">' +
                                    '<input type="hidden" name="_token" value="{!! csrf_token() !!}">'+
                                    '<input name="id" type="hidden" value="' + ID + '">' +
                                    '<button class="btn btn-link myButton" type="submit">' +
                                    '<i class="fas fa-italic "></i></button></form>';

                                var secondreportbutton = '';
                                if(full['second'])
                                    secondreportbutton = '<form action= "<?= action('Viewer\ProposalController@downloadsecondreport', '')?>" method="post"> ' +
                                    '<input name="_method" type="hidden" value="POST">' +
                                    '<input type="hidden" name="_token" value="{!! csrf_token() !!}">'+
                                    '<input name="id" type="hidden" value="' + ID + '">' +
                                    '<button class="btn btn-link myButton" type="submit">' +
                                    '<i class="fas fa-italic"></i><i class="fas fa-italic"></i></button></form>';

                                return  '<div style="margin:0px;padding:0px;">' + viewbutton +
                                        firstreportbutton +
                                        secondreportbutton + '</div>';
                            }
                        }
                    ],
                    "columnDefs": [
                    { "width": "120px", "targets": 0, "searchable": true, "orderable": true, "visible": true },
                    { "width": "175px", "targets": 1, "searchable": true, "orderable": true, "visible": true },
                    { "width": "150px", "targets": 2, "searchable": true, "orderable": true, "visible": true },
                    { "width": "100px", "targets": 3, "searchable": true, "orderable": true, "visible": true },
                    { "width": "120px", "targets": 4, "searchable": true, "orderable": true, "visible": true },
                    { "width": "300px", "targets": 5, "searchable": true, "orderable": true, "visible": true },
                ],
                "select": true,
                "scrollX": true,
                "scrollY": 300,
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
            // t.on('order.dt search.dt', function () {
            //     t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
            //         cell.innerHTML = i + 1;
            //     });
            // }).draw();

            reloadtable('viewer/listawards');
            $('#competition').change(function() {
                reloadtable('viewer/listawards');
            });
        });


    </script>

@endsection
