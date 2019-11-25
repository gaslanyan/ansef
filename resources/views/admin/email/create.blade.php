@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >

                    <div class="card-header">Add an email
{{--                        <a href = "{{ action('Admin\EmailController@index') }}" class="display float-lg-right btn-box-tool"> Back</a>--}}
                        <br>
                        <i class="fas fa-question-circle text-red all"> {{Lang::get('messages.required_all')}}</i>
                    </div>

                    <div class="card-body card_body" >
                        @include('partials.status_bar')

                        <form method="post" action="{{ action('Admin\EmailController@store') }}">
                            @csrf
                            <div class="form-group">

                                <div class="col-lg-12 ">
                                    <div class="row">
                                        <div class="form-group col-lg-10 emails">
                                            <label for="email">Emails *:</label>
                                            <i class="fa fa-plus pull-right add  text-blue"
                                               style="cursor: pointer"></i>
                                            <input type="text" class="form-control email" name="email[]"
                                                   id="email">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <a href = "{{ action('Admin\EmailController@index') }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
