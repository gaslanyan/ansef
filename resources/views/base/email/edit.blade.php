@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (\Session::has('success'))
                    <div class="alert alert-success">
                        <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                    </div><br/>
                @endif
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-8">
                 <div class="card" >
                    <div class="card-header">Edit the email</div>

                    <div class="card-body">
                        @include('partials.status_bar')
                        <form method="post" action="{{ action('Admin\EmailController@update', $id) }}">
                            <div class="form-group">
                                @csrf
                                <input name="_method" type="hidden" value="PATCH">
                                <label for="email">Email:</label>
                                <input type="text" class="form-control" name="email" value="{{$email->email}}"
                                       id="email">
                            </div>
                            <button type="submit" class="btn btn-primary">Add Template</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
