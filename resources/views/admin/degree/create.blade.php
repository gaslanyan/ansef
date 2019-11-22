@extends('layouts.master')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="offset-md-2 col-md-10">
                 <div class="card" >
                    <div class="card-header">Add a degree
                        <br>
                        <i class="fas fa-question-circle text-blue all"> {{Lang::get('messages.required_all')}}</i>
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
                            @if (\Session::has('success'))
                                <div class="alert alert-success">
                                    <p>@php echo html_entity_decode(\Session::get('success'), ENT_HTML5) @endphp</p>
                                </div><br />
                            @endif
                        @endif
                        <form method="post" action="{{ action('Admin\DegreeController@store') }}">
                            @csrf
                            <div class="form-group">
                                <label for="degree">Degree name *:</label>
                                @php
                                    $old =  old('text');
                                @endphp
                                <textarea id="degree"  class="form-control" name="text">@if(isset($old)){{$old}} @endif</textarea>
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
