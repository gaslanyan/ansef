@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" style="margin-top:20px;">
                    <div class="card-header">Edit a Degree
                        <br>
                        <i class="fas fa-question-circle text-blue all"> {{Lang::get('messages.required_all')}}</i>
                    </div>
                    <div class="card-body cord_body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div><br/>
                            @if (\Session::has('success'))
                                <div class="alert alert-success">
                                    <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                                </div><br/>
                            @endif
                        @endif
                        <form method="post" action="{{ action('Admin\DegreeController@update', $id) }}">
                            @csrf
                            <div class="form-group">
                                <input name="_method" type="hidden" value="PATCH">
                            </div>
                            <div class="form-group">
                                <label for="text">Degree Title *:</label>
                                <textarea class="form-control" name="text" id="text">{{$degree->text}}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Save</button>
                                <a href = "{{ action('Admin\DegreeController@index') }}" class="btn btn-secondary">Cancel</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
