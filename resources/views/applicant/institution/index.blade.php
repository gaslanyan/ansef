@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-10 offset-md-2">
                 <div class="card" >
                        @include('partials.status_bar')
                    <div class="card-header">Institution list</div>

                    <div class="card-body card_body">
                        <table class="table table-responsive-md table-sm table-bordered display" id="example"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th></th>
                                <th>Name</th>
                                <th>Country</th>
                                <th>Province</th>
                                <th>City</th>
                                <th>Street</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($institutions as $item)
                                <?php $id = $address[$item->address->country_id - 1];?>
                                <?php $c_id = $cities[$item->address->city_id];?>

                                <tr>
                                    <td></td>
                                    <td data-order="{{$item['content']}}"
                                        data-search="{{$item['content']}}">{{$item['content']}}
                                    </td>
                                    <td data-order="{{$id->country_name}}"
                                        data-search="{{$id->country_name}}">{{$id->country_name}}
                                    </td>
                                    <td data-order="{{$item->address->province}}"
                                        data-search="{{$item->address->province}}">{{$item->address->province}}
                                    </td>
                                    <td data-order="{{$c_id->name}}" data-search="{{$c_id->name}}">{{$c_id->name}}
                                    </td>
                                    <td>{{$item->address->street}}</td>
                                    <td>
                                        <a href="{{action('Admin\InstitutionController@edit', $item['id'])}}"
                                           title="full_edit"
                                           class="full_edit"><i class="fa fa-edit"></i>
                                        </a>

                                        <a href="{{action('Admin\InstitutionController@show', $item['id'])}}"
                                           class="view" title="View"><i class="fa fa-eye"></i>
                                        </a>
                                        <form action="{{action('Admin\InstitutionController@destroy', $item['id'])}}"
                                              method="post">
                                            @csrf
                                            <input name="_method" type="hidden" value="DELETE">
                                            <button class="btn btn-link" type="submit">
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
                "paging": false,
                "columnDefs": [
                    {
                        "targets": [4],
                        "searchable": false
                    }
                ]
            });
            t.on('order.dt search.dt', function () {
                t.column(0, {search: 'applied', order: 'applied'}).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        });

    </script>
@endsection
