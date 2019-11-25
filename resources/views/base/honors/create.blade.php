@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">Show Past Honors and Grants
                        for {{$person_id[0]['first_name']." ".$person_id[0]['last_name']}}
                        <a href="{{ action('Applicant\InfoController@index') }}"
                           class="display float-lg-right btn-box-tool">Go Back</a>
                    </div>

                    <div class="card-body card_body">
                        @include('partials.status_bar')

                        @if(!empty($honors))
                            <form method="post" action="{{ action('Base\HonorsController@update', $id) }}">
                                @csrf
                                {{--<input name="_method" type="hidden" value="PATCH">--}}
                                <div class="col-lg-12 ">
                                    <p class="col-12"><b>List of honors and grants:</b></p><br/>
                                    <div class="row">
                                        @foreach($honors as $honor)
                                            <div class="form-group col-lg-8">
                                                <input name="_method" type="hidden" value="PATCH">
                                                <label for="description">HoDescription of Honor or Grant:</label>
                                                <input type="text" class="form-control" name="description[]"
                                                       id="description" value="{{$honor['description']}}">
                                            </div>
                                            <div class="form-group col-lg-3">
                                                <label for="year">Year:</label>
                                                <input type="text" class="form-control" name="year[]"
                                                       value="{{$honor['year']}}"
                                                       id="year">
                                            </div>
                                            <div class="form-group col-lg-1">
                                                <label for="year"> <br/>
                                                    <a href="{{action('Base\HonorsController@destroy', $honor['id'])}}"
                                                       class="btn-link">
                                                        <i class="fa fa-trash"></i></a>
                                                </label>
                                            </div>
                                            <input type=hidden value="{{$honor['id']}}" name="honor_hidden_id[]">
                                        @endforeach
                                    </div>
                                </div>
                                <div class="form-group col-lg-12">
                                    <button type="submit" class="btn btn-primary">Save Changes</button>
                                </div>
                            </form>
                        @endif
                    </div>
                    <hr>
                    <div class="card-body card_body">
                        <p class="col-12"><b>Add an Honor</b></p><br/>

                        <form method="post" action="{{ action('Base\HonorsController@store') }}">
                            @csrf
                            <div class="row">
                            <div class="form-group col-10">
                                <label for="description">Description of Honors or Grant:</label>
                            <input type="text" class="form-control" name="description" id="description" value="{{old('description')}}">
                            </div>
                            <div class="form-group col-2">
                                <label for="year">Year:</label>
                                <input type="text" class="form-control" name="year" id="year" value="{{old('year')}}">
                            </div>
                            </div>
                            <input type=hidden value="{{$id}}" name="honor_hidden_id[]">
                            <button type="submit" class="btn btn-primary">Add Honor or Grant</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
