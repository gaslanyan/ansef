@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" style="margin-top:20px;">
                    <div class="card-header">Create Emails
                        <br>
                        <i class="fas fa-question-circle text-blue all"> {{Lang::get('messages.required_all')}}</i>
                    </div>

                    <div class="card-body card_body" >
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (\Session::has('success'))
                            <div class="alert alert-success">
                                <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                            </div><br/>
                        @endif
                        @if (\Session::has('error'))
                            <div class="alert alert-danger">
                                <p>@php echo html_entity_decode(\Session::get('error'), ENT_HTML5) @endphp</p>
                            </div>
                        @endif

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
                                                        <option value="{{$message->id}}" @if(old('template') == $message->id) {{'selected'}} @endif>{{$message->text}}</option>
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
