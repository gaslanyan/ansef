@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                 <div class="card" >
                    @php
                    if (Cookie::get('cid') !== null)
                        $cid = Cookie::get('cid');
                    else {
                        $cid = empty(\App\Models\Competition::latest('created_at')->first()) ? -1 : \App\Models\Competition::latest('created_at')->first()->id;
                    }
                    @endphp
                    <div class="card-header">
                        <a href="{{action('Admin\RankingRuleController@create')}}"
                           class="display float-lg-right btn-primary px-2 myButton"><i class="fas fa-plus"></i>&nbsp;Add a ranking rule</a>
                        <button type="button"
                                title="change state" onclick="executerules()"
                                class="display float-lg-left btn-primary px-2 myButton">
                                <i class="fas fa-cogs"></i>
                            Execute rules
                        </button>
                        <input style="margin-left:20px;margin-top:12px;" type="checkbox" name="cleanup" value="0" id="cleanup"> Cleanup before execution
                    </div>
                    <div class="card-body ajaxdiv" style="overflow:auto;">
                        @include('partials.status_bar')
                        For competition &nbsp;
                        <select name="competition" id="competition" style="width:100px;font-size:24px;">
                            @foreach($competitions as $c)
                                <option value="{{$c['id']}}" {{$c['id']==$cid ? 'selected' : ''}}>{{$c['title']}}</option>
                            @endforeach
                                <option value="-1" {{-1==$cid ? 'selected' : ''}}>All</option>
                        </select>

                        <div class="row">
                        <div class="col-8">
                        <table class="table table-bordered display compact" id="datatable">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Rules</th>
                                <th>Value</th>
                                <th>Admin</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                        </div><br/>
                        <div class="col-4" style="margin:0;padding:0;">
                            <div class="row" style="margin:0;padding:0;">
                                <a class=" myButton" onclick="showstats({{$cid}},'pi')">Show PI stats</a>
                                <a class=" myButton" onclick="showstats({{$cid}},'participants')">Show participants stats</a>
                                <a class=" myButton" onclick="showstats({{$cid}},'budget')">Show budget stats</a>
                                <a class=" myButton" onclick="showstats({{$cid}},'score')">Show score stats</a>
                            </div>
                            <div class="row" style="margin:0;padding:0;">
                                <span id="stats" style="min-height:200px;margin:0;padding:0;"></span>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            setCookie("cid", "{{$cid}}", 2);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var t = $('#datatable').DataTable({
                "pagingType": "full_numbers",
                "columns": [
                    {"data": "id"},
                    {
                        "render": function (data, type, full, meta) {
                            var r = JSON.parse(full.sql);
                            return r.name;
                        }
                    },
                    {"data": "value"},
                    {
                        "render": function (data, type, full, meta) {
                            return full.name;
                        }
                    },
                    {
                        "render": function (data, type, full, meta) {
                            editform =      '<form action="<?= action('Admin\RankingRuleController@edit')?>" method="post">' +
                                            '<input type="hidden" name="_token" value="{!! csrf_token() !!}">' +
                                            '<input name="_method" type="hidden" value="POST">' +
                                            '<input name="id" type="hidden" value="' + full.id + '">' +
                                            '<button class="btn-link" type="submit">' +
                                            '<i class="fa fa-pencil-alt"></i></button></form>';
                            deleteform =    '<form action="<?= action('Admin\RankingRuleController@remove')?>" method="post">' +
                                            '<input type="hidden" name="_token" value="{!! csrf_token() !!}">' +
                                            '<input name="_method" type="hidden" value="POST">' +
                                            '<input name="id" type="hidden" value="' + full.id + '">' +
                                            '<button class="btn-link delete" type="submit" data-title="rule">' +
                                            '<i class="fa fa-trash"></i></button></form>';
                            return editform + deleteform;
                        }
                    },
                ],
                "columnDefs": [
                    { "width": "30px", "targets": 0, "searchable": true, "orderable": true, "visible": true },
                    { "width": "250px", "targets": 1, "searchable": true, "orderable": true, "visible": true },
                    { "width": "30px", "targets": 2, "searchable": true, "orderable": true, "visible": true },
                    { "width": "200px", "targets": 3, "searchable": true, "orderable": true, "visible": true },
                    { "width": "100px", "targets": 4, "searchable": false, "orderable": false, "visible": true }
                ],
                "select": true,
                "scrollX": true,
                "scrollY": 300,
                "deferRender": true,
                "scrollCollapse": false,
                "scroller": true,
                "colReorder": false,
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

            reloadtable('admin/rankings/competition');

            $('#competition').change(function() {
                reloadtable('admin/rankings/competition');
            });
        });

        function showstats(cid, t) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#stats').html("<br/><h4>Computing...</h4>");
            $.ajax({
                url: '/admin/rankingstats',
                type: 'POST',
                context: {element: $(this)},
                data: {
                    _token: CSRF_TOKEN,
                    cid: cid,
                    type: t
                },
                dataType: 'JSON',
                success: function (data) {
                    $('#stats').html("<br/><b>Statistics for " + $('#competition option:selected').text() + "</b>" + data.content);
                },
                error: function (data) {
                    $('#stats').html("Error");
                }
            });
        }

        function executerules() {
            var t = $('#datatable').DataTable();
            var data = t.rows({'selected': true}).data();
            if (data.length > 0) {
                var checkedIDss = [];
                for(var i=0; i<data.length; i++) {
                    checkedIDss.push(data[i]["id"]);
                }
                $('#stats').html("<br/><h4>Computing...</h4>");
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: '/admin/execute',
                    type: 'POST',
                    data: {
                        token: CSRF_TOKEN,
                        id: checkedIDss,
                        cid: '{{$cid}}',
                        cleanup: $('#cleanup').is(":checked")
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        $('#stats').html(data.content);
                        // setTimeout(function(){
                        //     window.location.href = '{{route("proposal_list")}}';
                        // }, 500);
                    },
                    error: function(data) {
                        console.log('msg' + data);
                    }
                });
            }
        }
    </script>
@endsection
