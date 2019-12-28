@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">Add a message
                    </div>

                    <div class="card-body card_body" style="overflow:auto;">
                        <i class="fas fa-question-circle text-blue all"> {{Lang::get('messages.required_all')}}</i>
                        @include('partials.status_bar')


                        <form method="post" action="{{ action('Admin\TemplateController@store') }}">
                            @csrf
                            <div class="form-group">

                                <label for="name">Message Name *:</label>
                                <input type="text" class="form-control" name="name">
                            </div>
                            <div class="form-group">
                                @php
                                    $old = old('text');
                                @endphp
                                <label for="template">Message Text *:</label>
                                <textarea class="form-control" name="text" id="template">@if(isset($old)) {{$old}}@endif</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                        <a href = "{{ action('Admin\TemplateController@index') }}" class="btn btn-secondary"> Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
