@extends('layouts.master')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                <div class="card">
                    <div class="card-header">Edit a message
                        <br>
                        <i class="fa fa-info text-red all"> * {{Lang::get('messages.required_all')}}</i>
                    </div>

                    <div class="card-body card_body">
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

                        <form method="post" action="{{ action('Admin\TemplateController@update', $id) }}">
                            <div class="form-group">
                                @csrf
                                <input name="_method" type="hidden" value="PATCH">
                                <label for="name">Template Name *:</label>
                                <input type="text" class="form-control" name="name" value="{{$template->name}}" id="name">
                            </div>
                            <div class="form-group">
                                <label for="text">Template Text *:</label>
                                <textarea class="form-control" name="text" id="text">{{$template->text}}</textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Add Template</button>
                         <a href = "{{ action('Admin\TemplateController@index') }}" class="btn btn-secondary"> Cancel</a>
                       </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
