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
                                <th>Title</th>
                                <th colspan="2">Publisher</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($books as $book)
                                <tr>
                                    <td>{{$book['id']}}</td>
                                    <td>{{$book['title']}}</td>
                                    <td>{{$book['publsher']}}</td>
                                    <td><a href="{{action('Base\Books\BookController@edit', $book['id'])}}" class="btn btn-warning">Edit</a></td>
                                    <td>
                                        <form action="{{action('Base\Books\BookController@destroy', $book['id'])}}" method="post">
                                            @csrf
                                            <input name="_method" type="hidden" value="DELETE">
                                            <button class="btn btn-danger" type="submit">Delete Book</button>
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
