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
                                </div>
                            @endif
                            @if (\Session::has('error'))
                                <div class="alert alert-danger">
                                    <p>@php echo html_entity_decode(\Session::get('error'), ENT_HTML5) @endphp</p>
                                </div>
                            @endif
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
