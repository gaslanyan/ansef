@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
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
                    <div class="card-body card_body" style="overflow:auto;">
                        @include('partials.status_bar')
                        <p>
                        @foreach($competitions as $comp)
                        - <a style="color:#{{$comp->id == $cid ? 'f00' : '999'}};" href="{{action('Admin\RankingRuleController@list',['cid' => $comp->id])}}">{{$comp->title}}</a> -
                        @endforeach
                        </p>

                        <table class="table table-responsive-md table-sm table-bordered display compact" id="datatable"
                               style="width:100%">
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
                        id: checkedIDss,
                        cid: '{{$cid}}',
                        cleanup: $('#cleanup').is(":checked")
                    },
                    dataType: 'JSON',
                    success: function(data) {
                        setTimeout(function(){
                            window.location.href = '{{route("proposal_list", $cid)}}';
                        }, 5000);
                    },
                    error: function(data) {
                        console.log('msg' + data);
                    }
                });
            }
        }
    </script>
@endsection
