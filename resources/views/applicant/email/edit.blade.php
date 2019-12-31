@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                 <div class="card" >
                        @include('partials.status_bar')
                    <div class="card-header">Edit the email
                        <a href="{{ action('Applicant\AccountController@index') }}"
                           class="display float-lg-right btn-box-tool"> Back</a>
                    </div>
                    <div class="card-body" style="overflow:auto;">
                        <form method="post" action="{{ action('Applicant\EmailController@update', $id) }}">
                            <div class="form-group">
                                @csrf
                                <input name="_method" type="hidden" value="PATCH">
                                <label for="email">Email:</label>
                                <input type="text" class="form-control" name="email" value="{{$email->email}}"
                                       id="email">
                            </div>
                            <button type="submit" class="btn btn-primary">Edit Email</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
