@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" style="margin-top:20px;">
                    <div class="card-header">List of users</div>

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

                        <table class="table table-responsive-md table-sm table-bordered display" id="example"  style="width:100%">
                            <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Email</th>
                                <th>State</th>
                                <th >Actions</th>
                                <th >Actions</th>
                                <th >Delete</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($persons as $person)
                                <tr class="active">
                                    <td>{{$person['first_name']}}</td>
                                    <td>{{$person['last_name']}}</td>
                                    <td>{{$person->user->email}}</td>
                                    <td>{{$person['type']}}</td>
                                    <td><a href="{{action('Admin\PersonController@show', $person['id'])}}"
                                    class="btn btn-info">View</a></td>

                                    <td><a href="{{action('Admin\PersonController@edit', $person['id'])}}"
                                    class="btn btn-warning">Edit</a></td>

                                    {{--<td><a href="{{action('Admin\PersonController@disable', $person['id'])}}"--}}
                                    {{--class="btn btn-warning">Disable</a></td>--}}
                                    <td>
                                        <form action="{{action('Admin\PersonController@destroy', $person['id'])}}"
                                              method="post">
                                            @csrf
                                            <input name="_method" type="hidden" value="DELETE">
                                            <button class="btn btn-danger" type="submit">Delete</button>
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
        $(document).ready(function() {
            $('#example').DataTable( {
                "pagingType": "full_numbers",
                "columnDefs": [
                    {
                        "targets": [ 4 ],
                        "searchable": false
                    } , {
                        "targets": [ 4 ],
                        "searchable": false
                    }
                ]
            } );
        } );

    </script>

@endsection
