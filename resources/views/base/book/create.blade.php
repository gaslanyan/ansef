@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" style="margin-top:20px;">
                    <div class="card-header">Update Books for {{$person[0]['first_name']." ".$person[0]['last_name']}}
                        <a href = "{{ action('Applicant\InfoController@index') }}" class="display float-lg-right btn-box-tool">Go Back</a>
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
                        @if (\Session::has('delete'))
                            <div class="alert alert-info">
                                <p>@php echo html_entity_decode(\Session::get('delete'), ENT_HTML5) @endphp</p>
                            </div>
                        @endif
                            @if(!empty($books))
                            <form method="post" action="{{ action('Base\BookController@update', $id) }}" class="row">
                                @csrf
                                <p class="col-12"><b>Book titles:</b></p><br/>
                                @foreach($books as $book)
                                <div class="form-group col-lg-4">
                                    <input name="_method" type="hidden" value="PATCH">
                                    <label for="title">Book Title:</label>
                                    <input type="text" class="form-control" name="title[]" value="{{$book['title']}}"
                                           id="title">
                                </div>
                                <div class="form-group col-lg-4">
                                    <label for="title">Book Publisher:</label>
                                    <input type="text" class="form-control" name="publisher[]" value="{{$book['publsher']}}"
                                           id="title">
                                </div>
                                <div class="form-group col-lg-2">
                                    <label for="title">Book Year:</label>
                                    <input type="text" class="form-control" name="year[]" value="{{$book['year']}}"
                                           id="title">
                                </div>
                                    <div class="form-group col-lg-2">
                                        <label>
                                            <a href="{{action('Base\BookController@destroy', $book['id'])}}"
                                               class="btn-link col-lg-2">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </label>
                                        <input type="hidden" class="form-check-inline" name="book_edit_hidden_id[]"
                                               value="{{$book['id']}}"
                                               id="title">
                                    </div>
                                @endforeach
                                <div class="form-group col-lg-12">
                                <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                            @endif
                    </div>

                    <hr>
                    <div class="card-body card_body">
                        <p><b>Add New Book</b></p>
                        <form method="post" action="{{ action('Base\BookController@store') }}" class="row">
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
                                <input type="hidden" class="form-check-inline" name="book_add_hidden_id"
                                       value="{{$id}}"
                                       id="title">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
