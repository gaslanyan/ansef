@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card" >
                    <div class="card-header">Add a category
                    </div>
                    <div class="card-body" style="overflow:auto;">
                        <i class="fas fa-question-circle text-blue all"> </i>&nbsp;<i class="text-blue">{{Lang::get('messages.required_all')}}</i>
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
