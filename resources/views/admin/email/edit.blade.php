@extends('layouts.master')

@section('content')
<div class="container">

    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                @include('partials.status_bar')

                <div class="card-header">Edit the email</div>
                <div class="card-body">
                    <form method="post" action="{{ action('Admin\EmailController@update', $id) }}">
                        <div class="form-group">
                            @csrf
                            <input name="_method" type="hidden" value="PATCH">
                            <label for="email">Email:</label>
                            <input type="text" class="form-control" name="email" value="{{$email->email}}" id="email">
                        </div>
                        <button type="submit" class="btn btn-primary">Save</button>
                        <a href="{{ action('Admin\EmailController@index') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
