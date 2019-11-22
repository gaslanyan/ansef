@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 offset-md-2">
                 <div class="card" >
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                        </div><br />
                    @elseif (\Session::has('wrong'))
                        <div class="alert alert-success">
                            <p>{{ \Session::get('wrong') }}</p>
                        </div><br/>
                    @endif
                    <div class="card-header">Edit a phone number</div>

                    <div class="card-body card_body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div><br/>
                        @endif
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
