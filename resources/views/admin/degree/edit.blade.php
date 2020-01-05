@extends('layouts.master')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Edit a Degree
                </div>
                <div class="card-body cord_body">
                    <i class="fas fa-question-circle text-blue all"> </i>&nbsp;<i
                        class="text-blue">{{Lang::get('messages.required_all')}}</i>
                    @include('partials.status_bar')

                    <form method="post" action="{{ action('Admin\DegreeController@update', $id) }}">
                        @csrf
                        <div class="form-group">
                            <input name="_method" type="hidden" value="PATCH">
                        </div>
                        <div class="form-group">
                            <label for="text">Degree Title *:</label>
                            <textarea class="form-control" name="text" id="text">{{$degree->text}}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ action('Admin\DegreeController@index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
