@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card">
                    <div class="card-header">Edit a template</div>

                    <div class="card-body card_body">
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                            </div><br />
                        @elseif (\Session::has('wrong'))
                            <div class="alert alert-success">
                                <p>{{ \Session::get('wrong') }}</p>
                            </div><br/>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div><br/>
                        @endif
                        <form method="post" action="{{ action('Base\Books\BookController@update', $id) }}">
                            @csrf
                            <div class="form-group">
                                <input name="_method" type="hidden" value="PATCH">
                                <label for="title">Book Title:</label>
                                <input type="text" class="form-control" name="title" value="{{$book->title}}"
                                       id="title">

                            </div>
                            <div class="form-group">
                                <label for="title">Book Publisher:</label>
                                <input type="text" class="form-control" name="publisher" value="{{$book->publsher}}"
                                       id="title">
                            </div>
                            <div class="form-group">
                                <label for="title">Book Year:</label>
                                <input type="text" class="form-control" name="year" value="{{$book->year}}"
                                       id="title">
                            </div>
                            <button type="submit" class="btn btn-primary">Edit Book</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
