@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">

            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                        </div><br />
                    @elseif (\Session::has('wrong'))
                        <div class="alert alert-success">
                            <p>{{ \Session::get('wrong') }}</p>
                        </div><br/>
                    @endif
                    <div class="card-header">Dashboard</div>

                    <div class="card-body card_body" style="overflow:auto;">
                        @if(!empty($honors))
                        <table class="table table-responsive-md table-sm table-bordered display" id="example"
                               style="width:100%">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Description</th>
                                <th>Year</th>
                                <th>Action</th>

                            </tr>
                            </thead>
                            <tbody>
                            @foreach($honors as $honor)
                                <tr>
                                    <td data-order="{{$honor['id']}}" data-search="{{$honor['id']}}">{{$honor['id']}}</td>
                                    <td data-order="{{$honor['description']}}" data-search="{{$honor['description']}}">{{$honor['description']}}</td>
                                    <td data-order="{{$honor['year']}}" data-search="{{$honor['year']}}">{{$honor['year']}}</td>
                                   <td><a href="{{action('Applicant\HonorsController@edit', $honor['id'])}}" class="btn btn-warning">Edit</a></td>
                                    <td>
                                        <form action="{{action('Applicant\HonorsController@destroy', $honor['id'])}}" method="post">
                                            @csrf
                                            <input name="_method" type="hidden" value="DELETE">
                                            <button class="btn btn-danger" type="submit">Delete Honors&Grants</button>
                                        </form>

                                        <input type="hidden" class="id" value="{{$email['id']}}">
                                        {{--<button title="Edit"--}}
                                        {{--class="edit btn-link"><i class="fa fa-pencil-alt"></i>--}}
                                        {{--</button>--}}
                                        {{--<button title="Cancel"--}}
                                        {{--class="cancel editable btn-link"><i class="fa fa-ban"></i>--}}
                                        {{--</button>--}}
                                        {{--<a href="{{action('Admin\PersonController@show', $email['id'])}}"--}}
                                        {{--class="view" title="View"><i class="fa fa-eye"></i>--}}
                                        {{--</a>--}}

                                        <a href="{{action('Applicant\HonorsController@edit', $honor['id'])}}"
                                           title="full_edit"
                                           class="full_edit"><i class="fa fa-edit"></i>
                                        </a>
                                        <a href="{{action('Applicant\HonorsController@destroy', $honor['id'])}}"
                                           title="delete"
                                           class="full_edit"><i class="fa fa-trash"></i>
                                        </a>

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
                //"pagingType": "full_numbers",
                "paging": false,
                "columnDefs": [
                    {
                        "targets": [2],
                        "searchable": false
                    }, {
                        "targets": [2],
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
