@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">List of ranking rules

                        <a href="{{action('Admin\RankingRuleController@create')}}"
                           class="display float-lg-right btn-primary px-2">Add a ranking rule</a>
                        <a href="{{action('Admin\RankingRuleController@execute')}}"
                           class="display float-lg-right btn-primary mx-2 px-2">Execute ranking rules</a>
                    </div>
                    <div class="card-body card_body">
                        @include('partials.status_bar')

                        <div class="btn_add col-md-12">
                            <button type="button" disabled title="delete" id="deleteRule"
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
                                    <label for="rule" class="label">
                                        <input type="checkbox" class="form-control check_all"
                                               id="rule">
                                    </label>
                                </th>
                                <th>SQL</th>
                                <th>Value</th>
                                <th>Admin</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($rules as $rule)
                                <tr>
                                    <td data-order="data-order='[[ 1, &quot;asc&quot; ]]'"></td>
                                    <td><label for="cat{{$rule->id}}" class="label">
                                            <input type="checkbox" class="form-control checkbox" name="id[]"
                                                   value="{{$rule->id}}"
                                                   id="cat{{$rule->id}}">
                                        </label>
                                    </td>
                                    <td>
                                        {{$rule->sql}}
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
            var t = $('#example').DataTable({
                "pagingType": "full_numbers",

                columnDefs: [{
                    targets: [0],
                    orderData: [0, 1]
                }, {
                    targets: [1],
                    orderData: [1, 0]
                }]
            });
            t.on('order.dt search.dt', function () {
                t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        });
    </script>
@endsection
