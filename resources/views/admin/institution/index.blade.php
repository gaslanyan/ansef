@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-10 offset-md-2">
                 <div class="card" >
                    <div class="card-header"> List of institutions
                        <a href="{{action('Admin\InstitutionController@create')}}"
                           class="display float-lg-right btn-primary px-2">Add an institution</a>
                    </div>

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
                        <table class="table table-responsive-md table-sm table-bordered display" id="example"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>City</th>
                                <th>Country</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($institutions as $item)
                                @php

                                    if(!empty($item->address)){
                                        $id = $address[$item->address->country_id - 1];
                                        $c_id = $cities[$item->address->city_id];
                                }
                                @endphp

                                <tr>
                                    <td></td>
                                    <td data-order="{{$item['content']}}"
                                        data-search="{{$item['content']}}">{{$item['content']}}
                                    </td>
                                    <td data-order="@if(!empty($item->address)) {{$c_id}} @endif"
                                        data-search="@if(!empty($item->address)){{$c_id}} @endif">
                                        @if(!empty($item->address)) {{$c_id}}@endif
                                    </td>
                                    <td data-order="@if(!empty($item->address)) {{$id->country_name}} @endif"
                                        data-search="@if(!empty($item->address)) {{$id->country_name}} @endif">
                                        @if(!empty($item->address)){{$id->country_name}}@endif
                                    </td>
                                    <td>
                                        <a href="{{action('Admin\InstitutionController@edit', $item['id'])}}"
                                           title="full_edit"
                                           class="full_edit"><i class="fa fa-edit"></i>
                                        </a>

                                        <form action="{{action('Admin\InstitutionController@destroy', $item['id'])}}"
                                              method="post">
                                            @csrf
                                            <input name="_method" type="hidden" value="DELETE">
                                            <input name="_id" type="hidden" value="{{$item['id']}}">
                                            <button class="btn-link delete" type="button"
                                                    data-title="institution">
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
