@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card" >
                    <div class="card-header">Add a category
                        <br><i class="fas fa-question-circle text-blue all"> {{Lang::get('messages.required_all')}}</i>
                    </div>
                    <div class="card-body card_body" style="overflow:auto;">
                        @include('partials.status_bar')

                        <form method="post" action="{{ action('Admin\CategoryController@store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="title">Category title *:</label>
                                <input id="title" type="text" class="form-control" name="title" value="{{old('title')}}">
                            </div>
                            <div class="form-group">
                                <label for="abbreviation">Category abbreviation *:</label>
                                <input id="abbreviation" type="text" class="form-control" name="abbreviation" value="{{old('abbreviation')}}">
                            </div>

                            <div class="form-group">
                                <label for="parent">Parent Category:</label>
                                @if(!empty($parents))

                                    <select class="form-control" name="parent_id" id="parent">
                                        <option value="0">Select parent category</option>
                                        @foreach($parents as $item)
                                            <option class="text-capitalize" @if(old('parent_id') == $item->id) {{'selected'}} @endif
                                                    value="{{$item->id}}">{{$item->title}}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                            <a href = "{{ action('Admin\CategoryController@index') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
