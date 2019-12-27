@extends('layouts.master')

@section('content')
    <div class="container">
             <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">List of possible degrees
                    @if(get_role_cookie() == 'superadmin')
                        <a href="{{action('Admin\DegreeController@create')}}"
                           class="display float-lg-right btn-primary px-2 myButton"><i class="fas fa-plus"></i>&nbsp;Add a degree</a>
                    @endif
                    </div>

                    <div class="card-body card_body" style="overflow:auto;">
                        @include('partials.status_bar')

                        <table class="table table-responsive-md table-sm table-bordered display compact" id="example" >
                            <thead>
                            <tr>
                                <th hidden>#</th>
                                <th>Degree name</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($degrees as $degree)
                                <tr>
                                    <td hidden></td>
                                    <td>{{$degree['text']}}</td>
                                    <td style="width: 100px"><a href="{{action('Admin\DegreeController@edit', $degree['id'])}}" class="">
                                            <i class="fa fa-pencil-alt"></i></a>

                                        <form action="{{action('Admin\DegreeController@destroy', $degree['id'])}}" method="post">
                                            @csrf
                                            <input name="_method" type="hidden" value="DELETE">
                                            <input name="_id" type="hidden" value="{{$degree['id']}}">
                                            <button class="btn-link delete" type="button">
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
