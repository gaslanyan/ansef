@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Update Books for {{$person[0]['first_name']." ".$person[0]['last_name']}}
                    <a href="{{action('Applicant\AccountController@index')}}"
                        class="display float-lg-right btn-box-tool">Go Back</a>
                </div>
                <div class="card-body" style="overflow:auto;">
                    <p><b>Add New Book</b></p>
                    <form method="post" action="{{ action('Applicant\BookController@store') }}" class="row">
                        @csrf
                        <div class="form-group col-lg-4">
                            <label for="title">Book Title:</label>
                            <input type="text" class="form-control" name="title" id="title">
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="publisher">Book Publisher:</label>
                            <input type="text" class="form-control" name="publisher" id="publisher">
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="year">Year:</label>
                            <input type="text" class="form-control" name="year" id="year">
                        </div>
                        <div class="form-group col-lg-12">
                            <button type="submit" class="btn btn-primary">Add Book</button>
                            <input type="hidden" class="form-check-inline" name="book_add_hidden_id" value="{{$id}}"
                                id="title">
                        </div>
                    </form>
                    @include('partials.status_bar')
                    <hr>
                    @if(!empty($books))
                    <form method="post" action="{{ action('Applicant\BookController@update', $id) }}" class="row">
                        @csrf
                        <p class="col-12"><b>Book titles:</b></p><br />
                        @foreach($books as $book)
                        <div class="form-group col-lg-4">
                            <input name="_method" type="hidden" value="PATCH">
                            <label for="title">Book Title:</label>
                            <input type="text" class="form-control" name="title[]" value="{{$book['title']}}"
                                id="title">
                        </div>
                        <div class="form-group col-lg-4">
                            <label for="title">Book Publisher:</label>
                            <input type="text" class="form-control" name="publisher[]" value="{{$book['publisher']}}"
                                id="title">
                        </div>
                        <div class="form-group col-lg-2">
                            <label for="title">Book Year:</label>
                            <input type="text" class="form-control" name="year[]" value="{{$book['year']}}" id="title">
                        </div>
                        <div class="form-group col-lg-2">
                            <label>
                                <a href="{{action('Applicant\BookController@destroy', $book['id'])}}"
                                    class="btn-link col-lg-2">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </label>
                            <input type="hidden" class="form-check-inline" name="book_edit_hidden_id[]"
                                value="{{$book['id']}}" id="title">
                        </div>
                        @endforeach
                        <div class="form-group col-lg-12">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                    <br /><br /><br />
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
