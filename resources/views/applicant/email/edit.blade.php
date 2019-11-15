@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" style="margin-top:20px;">
                    @if (\Session::has('success'))
                        <div class="alert alert-success">
                            <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                        </div><br/>
                    @endif
                    @if (\Session::has('error'))
                        <div class="alert alert-danger">
                            <p>@php echo html_entity_decode(\Session::get('error'), ENT_HTML5) @endphp</p>
                        </div><br/>
                    @endif
                    <div class="card-header">Edit the email
                        <a href="{{ action('Applicant\InfoController@index') }}"
                           class="display float-lg-right btn-box-tool"> Back</a>
                    </div>
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
