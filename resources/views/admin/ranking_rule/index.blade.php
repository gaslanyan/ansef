@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">
                        <a href="{{action('Admin\RankingRuleController@create')}}"
                           class="display float-lg-right btn-primary px-2 myButton">Add a ranking rule</a>
                        <button type="button"
                                title="change state" onclick="executerules()"
                                class="display float-lg-left btn-primary px-2 myButton">
                                <i class="fa fa-comments"></i>
                            Execute rules
                        </button>
                    </div>
                    <div class="card-body card_body" style="overflow:auto;">
                        @include('partials.status_bar')

                        <table class="table table-responsive-md table-sm table-bordered display compact" id="datatable"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>SQL</th>
                                <th>Value</th>
                                <th>Admin</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rules as $rule)
                                <tr>
                                    <td>
                                        {{$rule->id}}
                                    </td>
                                    <td>
                                        {{truncate($rule->sql,50)}}
                                    </td>
                                    <td>
                                        {{$rule->value}}
                                    </td>

                                    <td>
                                        {{$rule->user['person']['first_name']. " ". $rule->user['person']['last_name']}}
                                    </td>
                                    <td>
                                        <a href="{{action('Admin\RankingRuleController@edit', $rule['id'])}}"
                                           class=""><i class="fa fa-pencil-alt"></i></a>
                                        <form action="{{action('Admin\RankingRuleController@destroy', $rule['id'])}}"
                                              method="post">
                                            @csrf
                                            <input name="_method" type="hidden" value="DELETE">
                                            <input name="_id" type="hidden" value="{{$rule['id']}}">
                                            <button class="btn-link delete" type="button"
                                                    data-title="rule">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var t = $('#datatable').DataTable({
                "pagingType": "full_numbers",
                "select": true,
                "scrollX": true,
                "columnDefs": [
                    { "targets": 0, "searchable": true, "visible": true },
                    { "targets": 1, "searchable": true, "visible": true },
                    { "targets": 2, "searchable": true, "visible": true },
                    { "targets": 3, "searchable": true, "visible": true }
                    ]
            });
            t.on('order.dt search.dt', function () {
                t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        });

        function executerules() {
            var t = $('#datatable').DataTable();
            var data = t.rows({'selected': true}).data();
            if (data.length > 0) {
                var checkedIDss = [];
                for(var i=0; i<data.length; i++) {
                    checkedIDss.push(data[i][0]);
                }
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
                        id: checkedIDss
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        if (data.success === -1)
                            console.log('msg' + data);
                        else
                            reloadtable('admin/listproposals');
                    },
                    error: function(data) {
                        console.log('msg' + data);
                    }
                });
            }
        }
    </script>
@endsection
