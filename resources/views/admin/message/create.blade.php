@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">Add an email template
                        <br>
                        <i class="fas fa-question-circle text-blue all"> {{Lang::get('messages.required_all')}}</i>
                    </div>
                    <div class="card-body card_body">
                        @include('partials.status_bar')


                        <form method="post" action="{{ action('Admin\MessageController@store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="message">Message Text *:</label>
                                @php
                                    $old = old('text');
                                @endphp
                                <textarea class="form-control" name="text" id="message">@if(isset($old)) {{$old}}@endif</textarea>
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
