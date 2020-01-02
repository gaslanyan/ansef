@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                 <div class="card" >
                    <div class="card-header">Edit an email template
                    </div>
                    <div class="card-body cord_body" style="overflow:auto;">
                        <i class="fas fa-question-circle text-blue all"> </i>&nbsp;<i class="text-blue">{{Lang::get('messages.required_all')}}</i>
                        @include('partials.status_bar')

                        <form method="post" action="{{ action('Admin\MessageController@update', $id) }}">
                            @csrf
                            <div class="form-group">
                                <input name="_method" type="hidden" value="PATCH">
                            </div>
                            <div class="form-group col-12">
                                <label for="title">Title *:</label><br/>
                                <input size="50" id="title" name="title" type="text" value="{{$message->title}}"><br/>
                            </div>
                            <div class="form-group col-12">
                                <label for="subject">Subject *:</label><br/>
                                <input size="50" id="subject" name="subject" type="text" value="{{$message->subject}}"><br/>
                            </div>
                            <div class="form-group">
                                <label for="text">Message Text *:</label>
                                <textarea class="form-control" name="text" id="text">{{$message->text}}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ action('Admin\MessageController@index') }}" class="btn btn-secondary"> Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
