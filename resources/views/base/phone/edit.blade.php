@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 offset-md-2">
                 <div class="card" >
                        @include('partials.status_bar')
                    <div class="card-header">Edit a phone number</div>

                    <div class="card-body card_body">
                        <form method="post" action="{{ action('Base\PhoneController@update', $id) }}">
                            @csrf
                            <input name="_method" type="hidden" value="PATCH">
                            <div class="form-group">

                                <label for="code">Country code:</label>
                                <input type="text" class="form-control" name="country_code[]"
                                       value="{{$phone->country_code}}"
                                       id="code">
                            </div>
                            <div class="form-group">

                                <label for="phone">Phone number:</label>
                                <input type="text" class="form-control" name="phone[]" value="{{$phone->number}}"
                                       id="phone">
                            </div>
                            <button type="submit" class="btn btn-primary">Edit number</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
