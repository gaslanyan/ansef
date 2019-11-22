@extends('layouts.master')

@section('content')
    <div class="container">
        @if (\Session::has('success'))
            <div class="alert alert-success">
                <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
            </div><br />
        @elseif (\Session::has('wrong'))
            <div class="alert alert-success">
                <p>{{ \Session::get('wrong') }}</p>
            </div><br/>
        @endif
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">Dashboard</div>

                    <div class="card-body card_body">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Description</th>
                                <th>Year</th>
                                <th>Domestic</th>
                                <th>Ansef</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($meetings as $meeting)
                                <tr>
                                    <td>{{$meeting['id']}}</td>
                                    <td>{{$meeting['description']}}</td>
                                    <td>{{$meeting['year']}}</td>
                                    <td>{{$meeting['domestic']}}</td>
                                    <td>{{$meeting['ansef_supported']}}</td>
                                    <td><a href="{{action('Base\MeetingController@edit', $meeting['id'])}}" class="btn btn-warning">Edit</a></td>
                                    <td>
                                        <form action="{{action('Base\MeetingController@destroy', $meeting['id'])}}" method="post">
                                            @csrf
                                            <input name="_method" type="hidden" value="DELETE">
                                            <button class="btn btn-danger" type="submit">Delete Meeting</button>
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
@endsection
