@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-12">
                 <div class="card" >
                    <div class="card-header">Edit a message
                        <br>
                        <i class="fas fa-question-circle text-red all"> {{Lang::get('messages.required_all')}}</i>
                    </div>

                    <div class="card-body card_body" style="overflow:auto;">
                        @include('partials.status_bar')


                        <form method="post" action="{{ action('Admin\TemplateController@update', $id) }}">
                            <div class="form-group">
                                @csrf
                                <input name="_method" type="hidden" value="PATCH">
                                <label for="name">Template Name *:</label>
                                <input type="text" class="form-control" name="name" value="{{$template->name}}" id="name">
                            </div>
                            <div class="form-group">
                                <label for="text">Template Text *:</label>
                                <textarea class="form-control" name="text" id="text">{{$template->text}}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                         <a href = "{{ action('Admin\TemplateController@index') }}" class="btn btn-secondary">Cancel</a>
                       </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
