@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-md-12">
                 <div class="card" >
                    <div class="card-header">Create Emails
                    </div>

                    <div class="card-body" >
                        <i class="fas fa-question-circle text-blue all"> </i>&nbsp;<i class="text-blue">{{Lang::get('messages.required_all')}}</i>
                        @include('partials.status_bar')


                        <form method="post" action="{{ action('Admin\InvitationController@store') }}">
                            @csrf
                            <div class="form-group">
                                <div class="col-lg-12 ">
                                    <div class="row">
                                        <div class="form-group col-lg-10 emails">
                                            <label for="email">Emails *:</label>
                                            <i class="fa fa-plus pull-right add  text-blue"
                                               style="cursor: pointer"></i>
                                            <input type="text" class="form-control email" name="email[]"
                                                   id="email" value="{{old('email[0]')}}">
                                        </div>
                                        <div class="form-group col-lg-10 emails">
                                            <label for="template">Email Templates *:</label>
                                            <select id="template" class="form-control" name="template">
                                                <option value="">Select template</option>
                                                @if(!empty($messages))
                                                    @foreach($messages as $key => $message)
                                                        <option value="{{$message->id}}" @if(old('template') == $message->id) {{'selected'}} @endif>{{$message->title}}</option>
                                                    @endforeach
                                                    @endif
                                            </select>

                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Send Invitation</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
