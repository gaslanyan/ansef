@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                 <div class="card" >
                    <div class="card-header">Edit a template</div>

                    <div class="card-body card_body" style="overflow:auto;">
                        @include('partials.status_bar')
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
                                <input type="text" class="form-control" name="publisher" value="{{$book->publisher}}"
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
