@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-12">
                 <div class="card" >
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                        </div><br/>
                    @elseif (\Session::has('wrong'))
                        <div class="alert alert-success">
                            <p>{{ \Session::get('wrong') }}</p>
                        </div><br/>
                    @endif
                    <div class="card-header">List of Persons</div>

                    <div class="card-body" style="overflow:auto;">
                        @if(!empty($persons))
                            <table class="table table-responsive-md table-sm table-bordered display" id="example"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Birth Date</th>
                                    <th>Birth Place</th>
                                    <th>Sex</th>
                                    <th>State</th>
                                    <th>Nationality</th>
                                    <th>Type</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($persons as $person)
                                    <tr>

                                        <td data-order="{{$person['id']}}" data-search="{{$person['id']}}">{{$person['id']}}</td>
                                        <td data-order="{{$person['first_name']}}" data-search="{{$person['first_name']}}">{{$person['first_name']}}</td>
                                        <td data-order="{{$person['last_name']}}" data-search="{{$person['last_name']}}">{{$person['last_name']}}</td>
                                        <td data-order="{{$person['birthdate']}}" data-search="{{$person['birthdate']}}">{{$person['birthdate']}}</td>
                                        <td data-order="{{$person['birthplace']}}" data-search="{{$person['birthplace']}}">{{$person['birthplace']}}</td>
                                        <td data-order="{{$person['sex']}}" data-search="{{$person['sex']}}">{{$person['sex']}}</td>
                                        <td data-order="{{$person['state']}}" data-search="{{$person['state']}}">{{$person['state']}}</td>
                                        <td data-order="{{$person['nationality']}}" data-search="{{$person['nationality']}}">{{$person['nationality']}}</td>
                                        <td data-order="{{$person['type']}}" data-search="{{$person['type']}}">{{$person['type']}}</td>
                                        <td> <a href="{{action('Viewer\PersonController@show', $person['id'])}}"
                                                class="view" title="View"><i class="fa fa-eye"></i>
                                            </a>

                                            <a href="{{action('Viewer\PersonController@edit', $person['id'])}}"
                                               title="full_edit"
                                               class="full_edit"><i class="fa fa-edit"></i>
                                            </a>
                                            <form action="{{action('Viewer\PersonController@destroy', $person['id'])}}" method="post">
                                                @csrf
                                                <input name="_method" type="hidden" value="DELETE">
                                                <button title="Delete"
                                                        class="editable btn-link"><i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <p>Can't find data</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function () {
            var t = $('#example').DataTable({
                "pagingType": "full_numbers",
                "dom": '<"top"flp>rt<"bottom"i><"clear">',
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
